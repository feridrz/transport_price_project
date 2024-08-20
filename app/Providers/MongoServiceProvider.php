<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoDB\Client as MongoClient;

class MongoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MongoClient::class, function ($app) {
            return new MongoClient(env('MONGO_DB_CONNECTION_URI'));
        });
    }
}
