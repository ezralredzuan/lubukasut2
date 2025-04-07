<?php

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InquiriesController;
use App\Http\Controllers\CustomerAuthController;

Route::get('/', function () {
    return view('products');
});

Route::get('/event/{id}', function ($id) {
    $event = Event::findOrFail($id);
    return view('event.show', compact('event'));
})->name('event.show');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::post('/customer/logout', function () {
    Session::forget('customer');
    return redirect()->route('home');
})->name('customer.logout');

Route::get('/product/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    return response()->json($product);
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('product');

Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon']);
Route::get('/checkout', function () {return view('checkout');})->name('checkout');
Route::post('/checkout/process', function (Illuminate\Http\Request $request) {session(['cart' => json_decode($request->cart_items, true)]);return redirect()->route('checkout');})->name('checkout.process');
Route::get('/order-success', function () {return view('order.success');})->name('order.success');


Route::post('/inquiries', [InquiriesController::class, 'store'])->name('inquiries.store');

Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product', [ProductController::class, 'index'])->name('product');




