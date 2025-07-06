<?php

namespace App\Providers;

use App\Services\Cart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cart', function ($app) {
            return new Cart();
        });
    }

    public function boot()
    {
        //
    }
}
