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

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('user.dashboard');
    Route::match(['post','get'],'invoke', [App\Http\Controllers\User\MediationController::class, 'invoke'])->name('user.invoke');

});


//mediator

Route::prefix('mediator')->middleware(['auth', 'mediator'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Mediator\DashboardController::class, 'index'])->name('mediator.dashboard');
    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('mediator.newrequest');
    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('mediator.ongoing');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('mediator.closed');
    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('mediator.profile');
    Route::get('users', [App\Http\Controllers\Mediator\DashboardController::class, 'users'])->name('mediator.users');

    Route::post('edit-profile/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'updateProfile'])->name('mediator.profile.update');

    Route::get('change-password/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'changePassword'])->name('mediator.change.password');

});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('users/list', [App\Http\Controllers\Admin\UsersController::class, 'index'])->name('admin.users.list');
    Route::get('users/json', [App\Http\Controllers\Admin\UsersController::class, 'json'])->name('admin.users.json');
    Route::post('users/statusChange', [App\Http\Controllers\Admin\UsersController::class, 'statusChange'])->name('admin.users.status_change');
    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('admin.newrequest');
    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('admin.ongoing');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('admin.closed');
    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('admin.profile');
});
