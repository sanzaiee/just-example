<?php

namespace App\Providers;

use App\Livewire\Cart\CartDropdown;
use App\Livewire\Cart\CartSetup;
use App\Livewire\Cart\Checkout;
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
        Livewire::component('cart-setup',CartSetup::class);
        Livewire::component('cart-dropdown',CartDropdown::class);
        Livewire::component('cart-checkout',Checkout::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(App::isLocal());
        Model::shouldBeStrict(App::isLocal());

    }
}
