<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\FaqController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\Backend\RecycleBinController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubPageController;
use App\Livewire\BrandSetup;
use App\Livewire\Category;
use App\Livewire\OrderSummary;
use App\Livewire\ProductSetup;
use App\Livewire\SetupShippingAddress;
use App\Models\Order;
use App\Models\OrderProductList;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Category as ModelsCategory;

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

Route::get('/', function () {
    $categories = ModelsCategory::with('product','product.user')->get();
    return view('backend.index',compact('categories'));
});

Auth::routes();

  // Site Settings
  Route::get('/site/settings/{slug}', [SettingController::class, 'view'])->name('site.view');
  Route::post('/site/settings', [SettingController::class, 'update'])->name('site.update');


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('/admin')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');

    Route::get('/category',Category::class)->name('category.index');
    Route::get('/brand',BrandSetup::class)->name('brand.index');
    Route::get('/tag',\App\Livewire\TagSetup::class)->name('tag.index');
    //    Route::resource('/tag',TagController::class);

    Route::resource('/banner',BannerController::class);
    Route::resource('/product',ProductController::class);
    Route::get('/product/create',ProductSetup::class)->name('product.create');
    Route::get('/checkout',[App\Http\Controllers\HomeController::class, 'checkout'])->name('checkout');
    Route::get('/shipping-address',SetupShippingAddress::class)->name('shipping.address');
    Route::get('/order-summary',OrderSummary::class)->name('order.summary');

    Route::get('/order-success/{pid}',[OrderController::class,'successPage'])->name('success.page');
    Route::get('/order',[OrderController::class,'index'])->name('order.index');
    Route::put('/delivery-status/{id}', [OrderController::class, 'delivery'])->name('delivery.status');
    Route::put('/pending-status/{id}', [OrderController::class, 'pending'])->name('pending.status');
    Route::put('/pay-status/{id}', [OrderController::class, 'payStatus'])->name('order.pay.status');
    Route::get('/order/{pid}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/invoice/{pid}', function () {
        return view('frontend.client.invoice');
    })->name('invoice');

    Route::get('/order-tracking/{pid}', function () {
        return view('frontend.client.order.tracking');
    })->name('order.tracking');

Route::post('/cancel-order', [App\Http\Controllers\Frontend\FrontendController::class, 'orderCancel'])->name('client.order.cancel');

    // Menu

    Route::resource('/blog',BlogController::class);
    Route::resource('/faq',FaqController::class);
    Route::resource('/testimonial',TestimonialController::class);
    Route::resource('/team',TeamController::class);
    Route::resource('/gallery',GalleryController::class);
    Route::get('/gallery-image/{id}/delete', [GalleryController::class, 'deleteProductConfigImage'])->name('delete.gallery.image');

    Route::resource('/page',PageController::class);
    Route::get('/sub-pages/{slug}', [SubPageController::class, 'index'])->name('sub-pages.index');
    Route::get('/sub-pages/{slug}/create', [SubPageController::class, 'create'])->name('sub-pages.create');
    Route::post('/sub-pages/{slug}/store', [SubPageController::class, 'store'])->name('sub-pages.store');
    Route::get('/sub-pages/{slug}/edit/{id}', [SubPageController::class, 'edit'])->name('sub-pages.edit');
    Route::put('/sub-pages/{slug}/update/{id}', [SubPageController::class, 'update'])->name('sub-pages.update');
    Route::delete('/sub-pages/{slug}/delete/{id}', [SubPageController::class, 'destroy'])->name('sub-pages.destroy');
    // Route::get('/sub-pages/{slug}/destroy/{id}', [SubPageController::class, 'create'])->name('sub-pages.create');
    // Route::resource('/sub-pages/{slug}',SubPageController::class);

    Route::post('/tinymce/upload-image', [App\Http\Controllers\HomeController::class, 'uploadImage'])->name("tinymce.uploadImage");


    Route::get('/recently-deleted',[RecycleBinController::class,'index'])->name('recycleBin');
    Route::get('/recently-deleted/{parameter}',[RecycleBinController::class,'show'])->name('recycle.bin');
    Route::put('/restore/{id}/{parameter}',[RecycleBinController::class,'restore'])->name('restore');


    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/sub-menu', [MenuController::class, 'subMenu'])->name('submenu.index');
    Route::post('/sub-menu', [MenuController::class, 'subMenuStore'])->name('submenu.store');


});

