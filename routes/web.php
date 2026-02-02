<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Livewire\BrandSetup;
use App\Livewire\Category;
use App\Livewire\OrderSummary;
use App\Livewire\ProductSetup;
use App\Livewire\SetupShippingAddress;
use App\Livewire\UserManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/home');
    }

    return redirect('/login');
});

Route::get('/verify-otp/{email}', [LoginController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/verify-otp/{email}', [LoginController::class, 'verifyOtpPost'])->name('verify.otp.post');
Auth::routes();

Route::middleware(['auth'])->prefix('/home')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('user.dashboard');
    Route::get('/brands', [App\Http\Controllers\HomeController::class, 'brands'])->name('brand.list');
    // Route::get('/products', [App\Http\Controllers\HomeController::class, 'products'])->name('products.list');
    Route::get('/products/{slug}', [App\Http\Controllers\HomeController::class, 'productShow'])->name('product.detail');

    Route::get('/shipping-address', SetupShippingAddress::class)->name('shipping.address');

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/checkout', [App\Http\Controllers\HomeController::class, 'checkout'])->name('checkout');
    Route::get('/order-success/{pid}', [OrderController::class, 'successPage'])->name('success.page');
    Route::post('/cancel-order', [OrderController::class, 'orderCancel'])->name('client.order.cancel');
    Route::get('/order-email/{pid}', [OrderController::class, 'ResendOrderConfirmationEmail']);

    Route::get('/invoice/{pid}', function () {
        return view('backend.checkout.invoice');
    })->name('invoice');

    Route::get('/order-tracking/{pid}', function () {
        return view('backend.checkout.tracking');
    })->name('order.tracking');

});

Route::middleware(['auth', 'admin.check'])->prefix('/home')->group(function () {
    Route::get('/users', UserManagement::class)->name('user.index');
    Route::get('/category', Category::class)->name('category.index');
    Route::get('/brand', BrandSetup::class)->name('brand.index');
    Route::resource('/product', ProductController::class)->except(['show']);
    Route::get('/product/create', ProductSetup::class)->name('product.create');
    Route::get('/order-summary', OrderSummary::class)->name('order.summary');

    Route::put('/order-status/{id}', [OrderController::class, 'complete'])->name('order.status');
    Route::put('/delivery-status/{id}', [OrderController::class, 'delivery'])->name('delivery.status');
    Route::put('/pending-status/{id}', [OrderController::class, 'pending'])->name('pending.status');
    Route::put('/pay-status/{id}', [OrderController::class, 'payStatus'])->name('order.pay.status');
    Route::get('/order/{pid}', [OrderController::class, 'show'])->name('order.show');
    Route::put('/update-notes', [OrderController::class, 'updateNotes'])->name('order.notes.update');
    
    Route::post('/tinymce/upload-image', [App\Http\Controllers\HomeController::class, 'uploadImage'])->name('tinymce.uploadImage');

    Route::get('/site/settings/{slug}', [SettingController::class, 'view'])->name('site.view');
    Route::post('/site/settings', [SettingController::class, 'update'])->name('site.update');

});
