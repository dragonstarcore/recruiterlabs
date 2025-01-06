<?php

namespace App\Providers;

use App\Xero\UserStorageProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use DB;
use Illuminate\Foundation\Application;
use Log;
use Illuminate\Support\Facades\Auth;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
   {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        $this->app->bind(OauthCredentialManager::class, function(Application $app) {
            return new UserStorageProvider(
                Auth::user(), // Storage Mechanism
                $app->make('session.store'), // Used for storing/retrieving oauth 2 "state" for redirects
                $app->make(\Webfox\Xero\Oauth2Provider::class) // Used for getting redirect url and refreshing token
            );
        });

        Schema::defaultStringLength(191);

        //START Logs for Update and delete queries
        $userid = "";
        if(Auth::user())
        {
            $userid = Auth::user()->id;
        }
        DB::listen(function($query) use($userid){
            if( (str_contains($query->sql, "update") !== false || str_contains($query->sql, "delete") !== false) && str_contains($query->sql, "insert into") == false && str_contains($query->sql, "create") == false)
            {
                Log::channel('custom')->info(
                    "UserId : " . $userid ." | ".$query->sql,
                    $query->bindings,
                    $query->time
                );
            }
        });
        //END Logs for Update and delete queries
    }


}
