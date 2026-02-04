<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('clients', \App\Http\Controllers\Dashboard\ClientController::class);
    Route::resource('orders', \App\Http\Controllers\Dashboard\OrderController::class)->only(['index', 'show']);
    Route::resource('integrations', \App\Http\Controllers\Dashboard\IntegrationController::class);
    Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);
    Route::resource('products', \App\Http\Controllers\Dashboard\ProductController::class);
});

// Superadmin Routes
Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('agencies', \App\Http\Controllers\Admin\AgencyController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';