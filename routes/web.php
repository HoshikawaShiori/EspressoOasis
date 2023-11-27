<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\UserController;


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
    Route::get('/', [CoffeeController::class, 'getIndex'])->name('coffee.index');
    Route::get('/shop', [CoffeeController::class, 'getShop'])->name('coffee.shop');
    Route::get('login/google/callback',[UserController::class, 'handleGoogleCallback'])->name('google.callback');

    

Route::group(['prefix'=> 'user'], function () {
    Route::group(['middleware'=> 'guest'], function () {
        Route::get('/signup', [UserController::class, 'getSignup'])->name('user.signup');
        Route::post('/signup', [UserController::class, 'postSignup'])->name('user.signup');
        Route::get('/signin', [UserController::class, 'getSignin'])->name('user.signin');
        Route::post('/signin', [UserController::class, 'postSignin'])->name('user.signin');

        Route::get('login/google', [UserController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('login/google/callback',[UserController::class, 'handleGoogleCallback'])->name('google.callback');

    });
        Route::group(['middleware' => ['auth']], function () {
        Route::get('/logout', [UserController::class,'getLogout'])->name('user.logout');
        Route::get('/profile', [UserController::class,'getProfile'])->name('user.profile');
        Route::get('/cart', [CoffeeController::class, 'getCart'])->name('coffee.cart');
        Route::get('/addToCart/{id}/{sizeIndex}/{brew}', [CoffeeController::class, 'getAddToCart'])->name('coffee.addToCart');
        Route::get('/reduce/{combinedKey}', [CoffeeController::class, 'getReduce'])->name('coffee.reduce');
        Route::get('/increase/{combinedKey}', [CoffeeController::class, 'getIncrease'])->name('coffee.increase');
        Route::get('/remove/{combinedKey}', [CoffeeController::class, 'getRemove'])->name('coffee.remove');
        Route::get('/checkoutForm', [CoffeeController::class, 'getcheckOutForm'])->name('coffee.checkoutForm');
        Route::post('/checkoutForm', [CoffeeController::class, 'getcheckOutForm'])->name('coffee.checkoutForm');
        Route::post('/checkout', [CoffeeController::class, 'checkout'])->name('coffee.checkout');
        Route::get('/success', [CoffeeController::class, 'successPayment'])->name('payment.success');
    });
});

Route::group(['prefix'=> 'a'], function () {
    Route::group(['middleware'=> 'guest'], function () {
        
        Route::get('/login', [adminController::class,'getSignin'])->name('signin');
        Route::post('/login', [adminController::class,'postSignin'])->name('signin');
        });

    Route::group(['middleware' => ['auth', 'role:admin']], function () {
        Route::get('/dashboard', [dashboardController::class,'getDashboard'])->name('dashboard');
        
    });
});

