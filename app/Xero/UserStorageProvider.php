<?php


namespace App\Xero;

use App\Models\User;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Webfox\Xero\Oauth2Provider;
use Webfox\Xero\OauthCredentialManager;

class UserStorageProvider implements OauthCredentialManager
{
    protected Oauth2Provider $oauthProvider;

    protected Store $session;

    protected User $user;

    public function __construct(User $user, Store $session, Oauth2Provider $oauthProvider)
    {
        // print('__construct');
        $this->user          = $user;
        $this->oauthProvider = $oauthProvider;
        $this->session       = $session;

        if($this->user->xero_oauth && time() >= $this->data('expires')){
            $this->refresh();
        }

    }

    public function getAccessToken(): string
    {
        return $this->data('token');
    }

    public function getRefreshToken(): string
    {
        return $this->data('refresh_token');
    }

    public function getTenantId(int $tenant =0): string
    {
        if(!isset($this->data('tenants')[$tenant]))
        {
            throw new \Exception("No such tenant exists");
        }
        return $this->data('tenants')[$tenant]['Id'];
    }
    public function getTenants(): ?array
    {
        return $this->data('tenants');
    }

    public function getExpires(): int
    {
        return $this->data('expires');
    }

    public function getState(): string
    {
        return $this->session->get('xero_oauth2_state');
    }

    public function getAuthorizationUrl(): string
    {
        $redirectUrl = $this->oauthProvider->getAuthorizationUrl(['scope' => config('xero.oauth.scopes')]);
        $this->session->put('xero_oauth2_state', $this->oauthProvider->getState());

        return $redirectUrl;
    }

    public function getData(): array
    {
        return $this->data();
    }

    public function exists(): bool
    {
        return !!$this->user->xero_oauth;
    }

    public function isExpired(): bool
    {
        return time() >= $this->data('expires');
    }

    public function refresh(): void
    {
        $newAccessToken = $this->oauthProvider->getAccessToken('refresh_token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->getRefreshToken(),
        ]);
        $this->store($newAccessToken);
    }

    public function store(AccessTokenInterface $token, array $tenants = null): void
    {
        $this->user->xero_oauth = [
            'token'         => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'id_token'      => $token->getValues()['id_token'],
            'expires'       => $token->getExpires(),
            'tenants'       => $tenants ?? $this->getTenants()
        ];

        $this->user->save();
    }

    public function delete(): void
    {
        $this->user->xero_oauth = null;
        $this->user->saveOrFail();
    }

    public function getUser(): ?array
    {

        try {
            $jwt = new \XeroAPI\XeroPHP\JWTClaims();
            $jwt->setTokenId($this->data('id_token'));
            $decodedToken = $jwt->decode();

            return [
                'given_name'  => $decodedToken->getGivenName(),
                'family_name' => $decodedToken->getFamilyName(),
                'email'       => $decodedToken->getEmail(),
                'user_id'     => $decodedToken->getXeroUserId(),
                'username'    => $decodedToken->getPreferredUsername(),
                'session_id'  => $decodedToken->getGlobalSessionId()
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function data($key = null)
    {
        if (!$this->exists()) {
            throw new \Exception('Xero oauth credentials are missing');
        }

        $cacheData = $this->user->xero_oauth;

        return empty($key) ? $cacheData : ($cacheData[$key] ?? null);
    }
}

