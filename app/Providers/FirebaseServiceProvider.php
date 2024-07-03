<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use App\Services\FirebaseAuthService;


class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase.auth', function ($app) {
            $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
            return $factory->createAuth();
        });
    }

   /* public function registerData()
    {
        $this->app->singleton('firebase.auth', function ($app) {
            $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
            $this->app->singleton('firebase.auth', function ($app) {
                return new FirebaseAuthService();
            });
            //return $factory->createAuth();
        });
    } */

    public function boot()
    {
        //
    }
}
