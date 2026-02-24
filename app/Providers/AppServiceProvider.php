<?php

namespace App\Providers;

use App\Livewire\Cart\CartDropdown;
use App\Livewire\Cart\CartSetup;
use App\Livewire\Cart\Checkout;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);

        Livewire::component('cart-setup', CartSetup::class);
        Livewire::component('cart-dropdown', CartDropdown::class);
        Livewire::component('cart-checkout', Checkout::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(App::isLocal());
        Model::shouldBeStrict(App::isLocal());

        // Register Product Observer to prevent deletion of products with orders
        Product::observe(ProductObserver::class);
    }
}
