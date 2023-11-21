<?php

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

Route::get('/shop', [CoffeeController::class, 'getIndex'])->name('coffee.shop');

Route::group(['prefix'=> 'user'], function () {
    Route::group(['middleware'=> 'guest'], function () {
        Route::get('/signup', [UserController::class, 'getSignup'])->name('user.signup');
        Route::post('/signup', [UserController::class, 'postSignup'])->name('user.signup');
        Route::get('/signin', [UserController::class, 'getSignin'])->name('user.signin');
        Route::post('/signin', [UserController::class, 'postSignin'])->name('user.signin');
    });
    Route::group(['middleware'=> 'auth'], function () {
        Route::get('/profile', [UserController::class, 'getProfile'])->name('user.profile');
        Route::get('/logout', [UserController::class,'getLogout'])->name('user.logout');

        Route::get('/cart', [CoffeeController::class, 'getCart'])->name('coffee.cart');
        Route::get('/addToCart/{id}', [CoffeeController::class, 'getAddToCart'])->name('coffee.addToCart');
        Route::get('/reduce/{id}', [CoffeeController::class, 'getReduce'])->name('coffee.reduce');
        Route::get('/increase/{id}', [CoffeeController::class, 'getIncrease'])->name('coffee.increase');
        Route::get('/remove/{id}', [CoffeeController::class, 'getRemove'])->name('coffee.remove');
        Route::post('/checkout', [CoffeeController::class, 'checkout'])->name('coffee.checkout');

    });
});