<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/cart', function () {
    return view('cart');
});
Route::get('add-to-cart/{id}',[App\Http\Controllers\ProductController::class,'getAddToCart'])->name('themgiohang');
Route::get('del-cart/{id}',[App\Http\Controllers\ProductController::class,'getDelItemCart'])->name('xoagiohang');


Route::get('check-out',[App\Http\Controllers\ProductController::class,'getCheckout'])->name('dathang');
Route::post('check-out',[App\Http\Controllers\ProductController::class,'postCheckout'])->name('dathang');