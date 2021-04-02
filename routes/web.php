<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/admin/login', function() {
    return view('admin.login');
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('user.dashboard');
});

Route::prefix('arbitrator')->middleware(['auth', 'arbitrator'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Arbitrator\DashboardController::class, 'index'])->name('arbitrator.dashboard');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});
