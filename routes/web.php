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
//Route::get('/login', [App\Http\Controllers\HomeController::class, 'login'])->name('login');



Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('user.dashboard');
    Route::match(['post', 'get'], 'invoke', [App\Http\Controllers\User\MediationController::class, 'invoke'])->name('user.invoke');
    Route::match(['post', 'get'], 'newcase', [App\Http\Controllers\User\MediationController::class, 'newcase'])->name('user.newcase');
    Route::match(['get'], 'newrequest', [App\Http\Controllers\User\MediationController::class, 'newrequest'])->name('user.newrequest');
});


//mediator

Route::prefix('mediator')->middleware(['auth', 'mediator'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Mediator\DashboardController::class, 'index'])->name('mediator.dashboard');
    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('mediator.newrequest');
    Route::get('newjson', [App\Http\Controllers\Mediator\DashboardController::class, 'newjson'])->name('mediator.newjson');
    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('mediator.ongoing');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('mediator.closed');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('mediator.closed');
    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('mediator.profile');
    Route::get('users', [App\Http\Controllers\Mediator\DashboardController::class, 'users'])->name('mediator.users');

    Route::post('edit-profile/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'updateProfile'])->name('mediator.profile.update');

    Route::get('change-password/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'changePassword'])->name('mediator.change.password');
    Route::post('activate-deactivate', [App\Http\Controllers\Mediator\DashboardController::class, 'statusChange'])->name('mediator.activeDeactive');
    Route::post('add-session', [App\Http\Controllers\Mediator\DashboardController::class, 'addSession'])->name('mediator.addSession');
    Route::post('get-add-session', [App\Http\Controllers\Mediator\DashboardController::class, 'getAddedSesion'])->name('mediator.getAddedSesion');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    //users
    Route::get('users/list/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'index'])->defaults('role', "user")->name('admin.users.list');
    Route::get('users/json/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'json'])->defaults('role', 0)->name('admin.users.json');
    Route::post('users/edit', [App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('admin.users.edit');
    Route::post('users/update', [App\Http\Controllers\Admin\UsersController::class, 'update'])->name('admin.users.update');
    Route::post('users/statusChange', [App\Http\Controllers\Admin\UsersController::class, 'statusChange'])->name('admin.users.status_change');
//    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('admin.newrequest');
//    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('admin.ongoing');
//    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('admin.closed');
//    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('admin.profile');
    //case
    Route::get('case/new-request', [App\Http\Controllers\Admin\CaseController::class, 'index'])->name('admin.case.newrequest');
    Route::get('case/ongoing-request', [App\Http\Controllers\Admin\CaseController::class, 'ongoingRequest'])->name('admin.case.ongoingrequest');
    Route::get('case/closed-request', [App\Http\Controllers\Admin\CaseController::class, 'closedRequest'])->name('admin.case.closedrequest');
    Route::get('case/rjected-request', [App\Http\Controllers\Admin\CaseController::class, 'rjectedRequest'])->name('admin.case.rjectedrequest');
    Route::get('case/json/{confirm_status?}', [App\Http\Controllers\Admin\CaseController::class, 'json'])->defaults('confirm_status', 0)->name('admin.case.json');
    Route::post('case/confirm-status', [App\Http\Controllers\Admin\CaseController::class, 'confirmStatus'])->name('admin.case.confirm_status');
    Route::post('case/reject-status', [App\Http\Controllers\Admin\CaseController::class, 'rejectStatus'])->name('admin.case.reject_status');
    Route::post('case/midater-add', [App\Http\Controllers\Admin\CaseController::class, 'midaterAdd'])->name('admin.case.midater_add');
    Route::post('case/add-session', [App\Http\Controllers\Admin\CaseController::class, 'addSession'])->name('admin.case.addSession');
    Route::post('case/get-add-session', [App\Http\Controllers\Admin\CaseController::class, 'getAddedSesion'])->name('admin.case.getAddedSesion');
});
