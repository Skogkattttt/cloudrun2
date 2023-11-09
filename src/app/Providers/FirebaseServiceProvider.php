<?php
namespace App\Providers;

use Kreait\Firebase\Factory;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FirebaseAuth::class, function ($app) {
            $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
            return $factory->createAuth();
        });
    }
}
