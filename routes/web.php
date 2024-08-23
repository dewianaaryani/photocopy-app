<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route to handle registration form submission
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', function () {
    return view('auth.login');
})->name('home');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
    Route::patch('/profile/user', [UserController::class, 'updateUser'])->name('updateUser');
});

Route::middleware(['auth', 'user-access:user'])->group(function () {
    
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/photocopy', [ProductController::class, 'photocopyAdd'])->name('photocopy.add');
    Route::get('/cetakfoto', [ProductController::class, 'cetakfotoAdd'])->name('cetakfoto.add');
    Route::get('/printout', [ProductController::class, 'printoutAdd'])->name('printout.add');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/category/{category}', [ProductController::class, 'productsByCategory'])->name('products.by.category');
    Route::post('/photocopyAddToSelected', [CartController::class, 'photocopyAddToSelected'])->name('photocopyAddToSelected');
    Route::post('/printoutAddToSelected', [CartController::class, 'printoutAddToSelected'])->name('printoutAddToSelected');
    Route::get('/photocopyChoose/{id}', [CartController::class, 'photocopyChoose'])->name('photocopyChoose');
    Route::get('/printoutChoose/{id}', [CartController::class, 'printoutChoose'])->name('printoutChoose');
    Route::post('/photocopyChoosed/{id}', [CartController::class, 'photocopyChoosed'])->name('photocopyChoosed');
    Route::post('/printoutChoosed/{id}', [CartController::class, 'printoutChoosed'])->name('printoutChoosed');
    Route::post('/cart/{id}/photocopyCancel', [CartController::class, 'photocopyCancel'])->name('photocopyCancel');
    Route::post('/cart/{id}/printoutCancel', [CartController::class, 'printoutCancel'])->name('printoutCancel');

    Route::post('/cetakfotoAddToCart', [CartController::class, 'cetakfotoAddToCart'])->name('cetakfotoAddToCart');
    Route::post('/product/{product}', [CartController::class, 'productAddToCart'])->name('productAddToCart');

    
    Route::prefix('cart')->name('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('.index');
        Route::post('/delete/{id}', [CartController::class, 'deleteCartItem'])->name('.delete');
        Route::get('/proceed-checkout', [OrderController::class, 'checkoutForm'])->name('.checkoutForm');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('.checkout');
    });
    Route::prefix('orders')->name('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('.index');
        Route::get('/{id}', [OrderController::class, 'view'])->name('.show');
        Route::post('/{id}/upload-payment-proof', [OrderController::class, 'uploadPaymentProof'])->name('.uploadPaymentProof');
    });
});
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::prefix('orders')->name('orders.')->group(function () {

            Route::get('/', [AdminOrderController::class, 'index'])->name('index');

            Route::get('/{id}', [AdminOrderController::class, 'view'])->name('show');

            Route::post('/{id}/pembayaran-diterima', [AdminOrderController::class, 'pembayaranDiterima'])->name('pembayaran-diterima');

            Route::post('/{id}/pesanan-jadi', [AdminOrderController::class, 'pesananJadi'])->name('pesanan-jadi');

            Route::post('/{id}/pesanan-selesai', [AdminOrderController::class, 'pesananSelesai'])->name('pesanan-selesai');

        });

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products', AdminProductController::class);
        
    });
    
});