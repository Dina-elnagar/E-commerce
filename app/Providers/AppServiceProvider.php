<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use App\Events\ProductOutOfStock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      //
    }
}
