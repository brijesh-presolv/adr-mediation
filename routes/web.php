<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
Route::get('/admin/login', function () {
    Auth::logout();
    return view('admin.login');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::match(['POST'], '/mediation', [App\Http\Controllers\HomeController::class, 'mediation'])->name('mediation');

Route::match(['POST', 'GET'], '/forgotpassword', [App\Http\Controllers\HomeController::class, 'forgotpassword'])->name('forgotpassword');
Route::match(['POST', 'GET'], '/forgotusername', [App\Http\Controllers\HomeController::class, 'forgotusername'])->name('forgotusername');
Route::match(['POST', 'GET'], '/resendotp', [App\Http\Controllers\HomeController::class, 'resendotp'])->name('resendotp');




Route::match(['GET', 'POST'], '/verify', [App\Http\Controllers\HomeController::class, 'verify'])->name('verify');
Route::match(['GET', 'POST'], '/whatsapp_status', [App\Http\Controllers\WhatsappStatus::class, 'status'])->name('whatsapp_status');


//Route::get('/login', [App\Http\Controllers\HomeController::class, 'login'])->name('login');

Route::get('/sendInvitation', [App\Http\Controllers\WhatsappStatus::class, 'SendInvitation'])->name('sendInvitation');

Route::get('/ivr/acceptcase', [App\Http\Controllers\IvrController::class, 'acceptcase']);

Route::post('download-document', [App\Http\Controllers\DownloadDocument::class, 'downloadSecure'])->name('downloadSecure');

Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('notification', [App\Http\Controllers\User\MediationController::class, 'Notification'])->name('user.notification');

    Route::match(['post', 'get'], 'invoke', [App\Http\Controllers\User\MediationController::class, 'invoke'])->name('user.invoke');
    Route::match(['post', 'get'], 'newcase', [App\Http\Controllers\User\MediationController::class, 'newcase'])->name('user.newcase');
    Route::match(['get'], 'newrequest', [App\Http\Controllers\User\MediationController::class, 'newrequest'])->name('user.newrequest');
    Route::match(['get'], 'ongoing', [App\Http\Controllers\User\MediationController::class, 'ongoing'])->name('user.ongoing');
    Route::match(['get'], 'closed', [App\Http\Controllers\User\MediationController::class, 'closed'])->name('user.closed');
    Route::match(['get'], 'rejected', [App\Http\Controllers\User\MediationController::class, 'rejected'])->name('user.rejected');

    Route::post('case/json/{confirm_status?}', [App\Http\Controllers\User\MediationController::class, 'json'])->defaults('confirm_status', 0)->name('user.case.json');


    Route::match(['post', 'get'], 'sessions', [App\Http\Controllers\User\MediationController::class, 'sessions'])->name('user.sessions');
    Route::match(['post', 'get'], 'join', [App\Http\Controllers\User\MediationController::class, 'join'])->name('user.join');

    Route::get('consent-and-disclosures/{id}', [App\Http\Controllers\User\MediationController::class, 'getConsentAndDisclosures'])->name('user.disclosures');

    Route::post('case/comment-view', [App\Http\Controllers\Admin\CaseController::class, 'commentView'])->name('user.case.comment_view');
    Route::post('case/comment-action', [App\Http\Controllers\Admin\CaseController::class, 'commentAction'])->name('user.case.comment');
    Route::get('comment-pdf/{id}/{type?}', [App\Http\Controllers\Admin\CaseController::class, 'generatePDF'])->name('user.case.commentPDF');
    Route::get('view-session/{id}', [App\Http\Controllers\Admin\CaseController::class, 'sessionPdf'])->name('user.case.sessionPdf');
    Route::post('case/downloadfilebulk', [App\Http\Controllers\Admin\CaseController::class, 'downloadfilebulk'])->name('user.case.downloadfilebulk');


    Route::get('casedetails/{id}', [App\Http\Controllers\User\MediationController::class, 'casedetails'])->name('user.casedetails');

    Route::post('withdraw', [App\Http\Controllers\User\MediationController::class, 'withdraw'])->name('user.case.withdraw');

    Route::post('view-settelment', [App\Http\Controllers\User\MediationController::class, 'viewSettelment'])->name('user.viewSettelment');


    Route::post('view-supporting', [App\Http\Controllers\User\MediationController::class, 'viewSupporting'])->name('user.viewSupporting');
    Route::post('upload-files', [App\Http\Controllers\User\MediationController::class, 'storeMultiFile'])->name('user.storeMultiFile');


    //profile

    Route::get('profile', [App\Http\Controllers\User\ProfileController::class, 'profile'])->name('user.profile');

    Route::post('edit-profile/{id}', [App\Http\Controllers\User\ProfileController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('change-password/{id}', [App\Http\Controllers\User\ProfileController::class, 'changePassword'])->name('user.change.password');

    //bulk upload
    Route::post('bulkupload', [App\Http\Controllers\User\MediationController::class, 'bulkUpload'])->name('user.bulkUpload');
    Route::put('uploaddocument/{id}', [App\Http\Controllers\User\MediationController::class, 'documentUpload'])->name('user.documentUpload');
});


//mediator

Route::prefix('mediator')->middleware(['auth', 'mediator'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Mediator\DashboardController::class, 'index'])->name('mediator.dashboard');
    Route::get('notification', [App\Http\Controllers\Mediator\DashboardController::class, 'Notification'])->name('mediator.notification');

    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('mediator.newrequest');
    Route::post('newjson', [App\Http\Controllers\Mediator\DashboardController::class, 'newjson'])->name('mediator.newjson');
    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('mediator.ongoing');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('mediator.closed');
    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('mediator.closed');
    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('mediator.profile');
    Route::get('profile/update', [App\Http\Controllers\Mediator\ProfileController::class, 'profileUpdate'])->name('mediator.profile.firstupdate');
    Route::post('profile/profile-save', [App\Http\Controllers\Mediator\ProfileController::class, 'profileSave'])->name('mediator.profile_save');
    Route::get('casedetails/{id}', [App\Http\Controllers\Mediator\DashboardController::class, 'casedetails'])->name('mediator.casedetails');


    Route::post('edit-profile/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'updateProfile'])->name('mediator.profile.update');

    Route::get('change-password/{id}', [App\Http\Controllers\Mediator\ProfileController::class, 'changePassword'])->name('mediator.change.password');

    Route::post('activate-deactivate', [App\Http\Controllers\Mediator\DashboardController::class, 'statusChange'])->name('mediator.activeDeactive');

    Route::post('add-session', [App\Http\Controllers\Mediator\DashboardController::class, 'addSession'])->name('mediator.addSession');
    Route::post('view-supporting', [App\Http\Controllers\Mediator\DashboardController::class, 'viewSupporting'])->name('mediator.viewSupporting');
    Route::post('view-settelment', [App\Http\Controllers\Mediator\DashboardController::class, 'viewSettelment'])->name('mediator.viewSettelment');
    Route::post('settelmen-save-close', [App\Http\Controllers\Mediator\DashboardController::class, 'settelmenSaveClose'])->name('mediator.settelmenSaveClose');

    Route::post('get-add-session', [App\Http\Controllers\Mediator\DashboardController::class, 'getAddedSesion'])->name('mediator.getAddedSesion');
    Route::get('view-session/{id}', [App\Http\Controllers\Admin\CaseController::class, 'sessionPdf'])->name('mediator.case.sessionPdf');

    Route::post('case/edit-session', [App\Http\Controllers\Admin\CaseController::class, 'SendforEditSession'])->name('mediator.SendforEditSession');
    Route::post('case/update-session', [App\Http\Controllers\Admin\CaseController::class, 'UpdateSession'])->name('mediator.UpdateSession');
    Route::post('case/delete-session', [App\Http\Controllers\Admin\CaseController::class, 'deleteSession'])->name('mediator.DeleteSession');


    Route::get('rejected-case', [App\Http\Controllers\Mediator\DashboardController::class, 'rejectedCaseView'])->name('mediator.rejectCase');

    Route::post('upload-files', [App\Http\Controllers\Mediator\DashboardController::class, 'storeMultiFile'])->name('mediator.storeMultiFile');

    Route::post('case/json/{confirm_status?}', [App\Http\Controllers\Mediator\DashboardController::class, 'json'])->defaults('confirm_status', 0)->name('mediator.case.json');
    Route::post('case/json-ongoing/{confirm_status?}', [App\Http\Controllers\Mediator\DashboardController::class, 'jsonOngoing'])->defaults('confirm_status', 0)->name('mediator.case.jsonOngoing');
    Route::post('case/comment-view', [App\Http\Controllers\Admin\CaseController::class, 'commentView'])->name('mediator.case.comment_view');
    Route::post('case/comment-action', [App\Http\Controllers\Admin\CaseController::class, 'commentAction'])->name('mediator.case.comment');
    Route::post('case/add-session', [App\Http\Controllers\Mediator\DashboardController::class, 'addSession'])->name('mediator.case.addSession');
    Route::post('case/withdraw-status', [App\Http\Controllers\Admin\CaseController::class, 'withdrawStatus'])->name('mediator.case.withdraw');
    Route::post('case/get-add-session', [App\Http\Controllers\Admin\CaseController::class, 'getAddedSesion'])->name('mediator.case.getAddedSesion');
    Route::get('consent-and-disclosures/{id}', [App\Http\Controllers\Mediator\DashboardController::class, 'getConsentAndDisclosures'])->name('mediator.getConsentAndDisclosures');

    Route::get('comment-pdf/{id}/{type?}', [App\Http\Controllers\Admin\CaseController::class, 'generatePDF'])->name('mediator.case.commentPDF');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('notification', [App\Http\Controllers\Admin\DashboardController::class, 'Notification'])->name('admin.notification');

    //users
    Route::post('users/changeRole', [App\Http\Controllers\Admin\UsersController::class, 'ChangeRole'])->name('admin.users.changerole');
    Route::get('users/list/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'index'])->defaults('role', "user")->name('admin.users.list');
    Route::get('users/jsonApprove/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'jsonApprove'])->defaults('role', 0)->name('admin.users.jsonApprove');
    Route::get('users/jsonNewreq/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'jsonNewreq'])->defaults('role', 0)->name('admin.users.jsonNewreq');
    Route::get('users/jsonUnapprove/{role?}', [App\Http\Controllers\Admin\UsersController::class, 'jsonUnapprove'])->defaults('role', 0)->name('admin.users.jsonUnapprove');
    Route::match(['post', 'get'], 'users/edit/{id}', [App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('admin.users.edit');
    Route::post('users/update', [App\Http\Controllers\Admin\UsersController::class, 'update'])->name('admin.users.update');
    Route::post('users/statusChange', [App\Http\Controllers\Admin\UsersController::class, 'statusChange'])->name('admin.users.status_change');
    Route::post('users/status-change-approve', [App\Http\Controllers\Admin\UsersController::class, 'statusChangeApprove'])->name('admin.users.status_change_approvel');
    Route::post('user/delete', [App\Http\Controllers\Admin\UsersController::class, 'deleteUser'])->name('admin.user.delete');

    //    Route::get('new', [App\Http\Controllers\Mediator\DashboardController::class, 'newrequest'])->name('admin.newrequest');
    //    Route::get('ongoing', [App\Http\Controllers\Mediator\DashboardController::class, 'ongoing'])->name('admin.ongoing');
    //    Route::get('closed', [App\Http\Controllers\Mediator\DashboardController::class, 'closed'])->name('admin.closed');
    //    Route::get('profile', [App\Http\Controllers\Mediator\DashboardController::class, 'profile'])->name('admin.profile');
    Route::get('profile', [App\Http\Controllers\Admin\DashboardController::class, 'profile'])->name('admin.profile');
    Route::get('change-password/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('admin.change.password');
    Route::post('edit-profile/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'updateProfile'])->name('admin.profile.update');

    Route::post('users/docsAccessChange', [App\Http\Controllers\Admin\CaseController::class, 'docsAccessChange'])->name('admin.users.docs_access_change');
    Route::post('users/mediatorAccessChange', [App\Http\Controllers\Admin\CaseController::class, 'mediatorAccessChange'])->name('admin.users.mediator_access_change');
    Route::get('comment-pdf/{id}/{type?}', [App\Http\Controllers\Admin\CaseController::class, 'generatePDF'])->name('admin.case.commentPDF');

    // batch wise approve
    Route::post('case/getbatchwiseapprove', [App\Http\Controllers\Admin\CaseController::class, 'GetBatchWiseApprove'])->name('admin.case.getbatchwiseapprove');
    Route::post('case/batchwiseapprove', [App\Http\Controllers\Admin\CaseController::class, 'BatchWiseApprove'])->name('admin.case.batchwiseapprove');
    Route::post('case/countbatchwiseapprove', [App\Http\Controllers\Admin\CaseController::class, 'CountBatchWiseApprove'])->name('admin.case.countbatchwiseapprove');


    //case
    Route::get('casedetails/{id}', [App\Http\Controllers\Admin\CaseController::class, 'casedetails'])->name('admin.case.casedetails');
    Route::get('case/new-request', [App\Http\Controllers\Admin\CaseController::class, 'index'])->name('admin.case.newrequest');
    Route::get('case/ongoing-request', [App\Http\Controllers\Admin\CaseController::class, 'ongoingRequest'])->name('admin.case.ongoingrequest');
    Route::get('case/closed-request', [App\Http\Controllers\Admin\CaseController::class, 'closedRequest'])->name('admin.case.closedrequest');
    Route::get('case/rjected-request', [App\Http\Controllers\Admin\CaseController::class, 'rjectedRequest'])->name('admin.case.rjectedrequest');
    Route::post('case/json/{confirm_status?}', [App\Http\Controllers\Admin\CaseController::class, 'json'])->defaults('confirm_status', 0)->name('admin.case.json');
    Route::post('case/confirm-status', [App\Http\Controllers\Admin\CaseController::class, 'confirmStatus'])->name('admin.case.confirm_status');
    Route::post('case/reject-status', [App\Http\Controllers\Admin\CaseController::class, 'rejectStatus'])->name('admin.case.reject_status');
    Route::post('case/withdraw-status', [App\Http\Controllers\Admin\CaseController::class, 'withdrawStatus'])->name('admin.case.withdraw');
    Route::post('case/midater-add', [App\Http\Controllers\Admin\CaseController::class, 'midaterAdd'])->name('admin.case.midater_add');
    Route::post('case/add-session', [App\Http\Controllers\Admin\CaseController::class, 'addSession'])->name('admin.case.addSession');
    Route::post('case/get-add-session', [App\Http\Controllers\Admin\CaseController::class, 'getAddedSesion'])->name('admin.case.getAddedSesion');
    Route::post('case/comment-action', [App\Http\Controllers\Admin\CaseController::class, 'commentAction'])->name('admin.case.comment');
    Route::post('case/comment-view', [App\Http\Controllers\Admin\CaseController::class, 'commentView'])->name('admin.case.comment_view');
    Route::post('case/settelmen-upload', [App\Http\Controllers\Admin\CaseController::class, 'settelmenUpload'])->name('admin.case.settelmen_upload');
    Route::post('view-settelment', [App\Http\Controllers\Admin\CaseController::class, 'viewSettelment'])->name('admin.case.viewSettelment');
    Route::post('view-supporting', [App\Http\Controllers\Admin\CaseController::class, 'viewSupporting'])->name('admin.case.viewSupporting');
    Route::get('consent-and-disclosures/{id}', [App\Http\Controllers\Admin\CaseController::class, 'getConsentAndDisclosures'])->name('admin.case.getConsentAndDisclosures');
    Route::get('view-session/{id}', [App\Http\Controllers\Admin\CaseController::class, 'sessionPdf'])->name('admin.case.sessionPdf');
    Route::match(['post', 'get'], 'updatecase/{id}', [App\Http\Controllers\Admin\CaseController::class, 'updatecase'])->name('admin.case.update');
    Route::post('upload-files', [App\Http\Controllers\Admin\CaseController::class, 'storeMultiFile'])->name('admin.case.storeMultiFile');
    Route::post('case/downloadfilebulk', [App\Http\Controllers\Admin\CaseController::class, 'downloadfilebulk'])->name('admin.case.downloadfilebulk');

    Route::post('case/edit-session', [App\Http\Controllers\Admin\CaseController::class, 'SendforEditSession'])->name('admin.case.SendforEditSession');
    Route::post('case/update-session', [App\Http\Controllers\Admin\CaseController::class, 'UpdateSession'])->name('admin.case.UpdateSession');
    Route::post('case/delete-session', [App\Http\Controllers\Admin\CaseController::class, 'deleteSession'])->name('admin.case.DeleteSession');
    Route::post('case/downloadLogInviation', [App\Http\Controllers\Admin\CaseController::class, 'downloadLogInviation'])->name('admin.case.downloadLogInviation');

    //track
    Route::get('track/{id}', [App\Http\Controllers\Admin\CaseController::class, 'track'])->name('admin.case.track');

    //cases bulk upload
    Route::post('cases/bulkupload', [App\Http\Controllers\Admin\CaseController::class, 'bulkUpload'])->name('admin.bulkUpload');
    Route::put('uploaddocument/{id}', [App\Http\Controllers\Admin\CaseController::class, 'documentUpload'])->name('admin.documentUpload');
});

//notification
Route::get('notification/email/send', [App\Http\Controllers\Notification\EmailController::class, 'send']);

Route::get('notification/whatsapp/send', [App\Http\Controllers\Notification\WhatsappController::class, 'send']);