<?php

namespace App\Providers;

use App\Xero\UserStorageProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Application;
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

//        $this->app->bind(OauthCredentialManager::class, function(Application $app) {
//            return new UserStorageProvider(
//                Auth::user(), // Storage Mechanism
//                $app->make('session.store'), // Used for storing/retrieving oauth 2 "state" for redirects
//                $app->make(\Webfox\Xero\Oauth2Provider::class) // Used for getting redirect url and refreshing token
//            );
//        });
//
        Schema::defaultStringLength(191);

        //START Logs for Update and delete queries
//        $userid = auth()->hasUser() ? auth()->id() : "";
//        DB::listen(function($query) use($userid){
//            if ((str_contains($query->sql, "update") || str_contains($query->sql, "delete")) && !str_contains($query->sql, "insert into") && !str_contains($query->sql, "create"))
//            {
//                Log::channel('custom')->info(
//                    "UserId : " . $userid ." | " . $query->sql,
//                    $query->bindings,
//                    $query->time
//                );
//            }
//        });
        //END Logs for Update and delete queries
    }


}
