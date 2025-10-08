<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('payNowProcessed', [App\Http\Controllers\API\PaymentController::class, 'payNowProcessed']);
Route::post('offerCheck', [App\Http\Controllers\API\PaymentController::class, 'offerCheck']);   
Route::post('storerestructure', [App\Http\Controllers\API\PaymentController::class, 'storeRestructure']);   
Route::post('getcasedetails', [App\Http\Controllers\API\PaymentController::class, 'getcasedetails']);  
Route::post('casereplydetails', [App\Http\Controllers\API\PaymentController::class, 'casereplydetails']); 
Route::post('paydirect', [App\Http\Controllers\API\PaymentController::class, 'payDirect']); 
Route::post('addDisputeReply', [App\Http\Controllers\API\PaymentController::class, 'addDisputeReply']); 
Route::get('/payment/success', [App\Http\Controllers\API\PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/webhook', [App\Http\Controllers\API\PaymentController::class, 'paymentWebhook'])->name('payment.webhook');
Route::post('/otpgenerate', [App\Http\Controllers\API\PaymentController::class, 'otpGenerate']);
Route::post('/otpverify', [App\Http\Controllers\API\PaymentController::class, 'otpVerify']);
Route::post('/directDownloadSecure', [App\Http\Controllers\API\PaymentController::class, 'directDownloadSecure']);

Route::post('/whatsapbotreply', [App\Http\Controllers\API\WhatsappChatbotController::class, 'whatsappbotReply']);
Route::post('/botmisreport', [App\Http\Controllers\API\WhatsappChatbotController::class, 'botmisreport']);

Route::post('/whatsappconsentreply', [App\Http\Controllers\API\WhatsappChatbotController::class, 'whatsappconsentreply']);


// Whatsapp chat log API
Route::post('medwhatsappbotlog', [App\Http\Controllers\API\WhatsappChatbotController::class, 'medwhatsappbotlog']);

// Whatsapp chat log API
Route::post('medwhatsappbotlogmtalkz', [App\Http\Controllers\API\WhatsappChatbotController::class, 'medwhatsappbotlogmtalkz']);



//Whatsapp Webhook
Route::get('whatsapp_webhook_status', [App\Http\Controllers\Webhook\WhatsappWebhookController::class, 'WhatsappStatus']);

Route::get('whresmovetos3', [App\Http\Controllers\Webhook\WhatsappWebhookController::class, 'whresmovetos3']);



Route::get('whatsapp_webhook_incoming_log', [App\Http\Controllers\Webhook\WhatsappWebhookController::class, 'WhatsappLog']);

//Route::post('medwhatsappbotlog', [App\Http\Controllers\WhatsappBot\WhatsappBotController::class, 'medwhatsappbotlog']);




/************************ UK Version API Section : START **************************************************/

Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\UserController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\API\UserController::class, 'logout']);

Route::get('/gentoken', [App\Http\Controllers\API\AuthController::class, 'gentoken']);
Route::middleware(['apiauth'])->group(function () {

    Route::get('/authcheck', [App\Http\Controllers\API\AuthController::class, 'checkAuth']);
    Route::post('/download-document', [App\Http\Controllers\API\DownloadDocument::class, 'downloadSecure']);
    Route::post('/preview-document', [App\Http\Controllers\API\DownloadDocument::class, 'previewSecure']);
});

 // Admin API Routes
Route::middleware(['apiauth', 'apiadmin'])->group(function () {

    Route::post('/admin/dashboard', [App\Http\Controllers\API\Admin\AdminController::class, 'dashboard']);
    Route::post('/admin/cases/ongoing', [App\Http\Controllers\API\Admin\CaseController::class, 'ongoing']);
    Route::post('/admin/cases/newreq', [App\Http\Controllers\API\Admin\CaseController::class, 'newreq']);
    Route::post('/admin/cases/closed', [App\Http\Controllers\API\Admin\CaseController::class, 'closed']);
    Route::post('/admin/cases/rejected', [App\Http\Controllers\API\Admin\CaseController::class, 'rejected']);
    Route::get('/admin/cases/cases-count', [App\Http\Controllers\API\Admin\CaseController::class, 'casesCount']);
    // Case approve api
    Route::get('/admin/cases/mediator-listing', [App\Http\Controllers\API\Admin\CaseController::class, 'mediatorList']);
    Route::post('/admin/cases/case-approve', [App\Http\Controllers\API\Admin\CaseController::class, 'mediatorAssign']);

    // Case details api
    Route::post('/admin/cases/view-case-details', [App\Http\Controllers\API\Admin\CaseController::class, 'viewCaseDetails']);

    // Case update api
    Route::post('/admin/cases/case-edit', [App\Http\Controllers\API\Admin\CaseController::class, 'caseUpdate']);
    Route::post('/admin/cases/fetch-casedata', [App\Http\Controllers\API\Admin\CaseController::class, 'fetchCase']);

    // Case reject api
    Route::post('/admin/cases/case-reject', [App\Http\Controllers\API\Admin\CaseController::class, 'caseReject']);

    // MOM api
    Route::post('/admin/cases/get-mom-data', [App\Http\Controllers\API\Admin\CaseController::class, 'showMomSession']);
    Route::post('/admin/cases/mom-data-submit', [App\Http\Controllers\API\Admin\CaseController::class, 'momDataSubmit']);

    // Case track api
    Route::post('admin/cases/case-track', [App\Http\Controllers\API\Admin\CaseController::class, 'caseTrack']);

    // Mediator edit api
    Route::post('/admin/cases/mediator-edit', [App\Http\Controllers\API\Admin\CaseController::class, 'mediatorEdit']);

    // Ongoing listing - close case api
    Route::post('/admin/cases/close-case', [App\Http\Controllers\API\Admin\CaseController::class, 'closeCaseStatus']);

    // Get notifications api
    Route::get('/admin/notifications', [App\Http\Controllers\API\Admin\DashboardController::class, 'getNotifications']);

    // Profile update api
    Route::get('/admin/show-profile', [App\Http\Controllers\API\Admin\ProfileController::class, 'getProfileData']);
    Route::post('/admin/edit-profile-data', [App\Http\Controllers\API\Admin\ProfileController::class, 'editProfileData']);
    Route::post('/admin/change-password', [App\Http\Controllers\API\Admin\ProfileController::class, 'changePassword']);
    
    Route::post('/admin/case/add-session', [App\Http\Controllers\API\Admin\CaseController::class, 'addSession']);
    Route::post('/admin/cases/meeting-sessions', [App\Http\Controllers\API\Admin\CaseController::class, 'getMeetingSession']);
    Route::post('/admin/cases/edit-session', [App\Http\Controllers\API\Admin\CaseController::class, 'SendforEditSession']);
    Route::post('/admin/cases/update-session', [App\Http\Controllers\API\Admin\CaseController::class, 'UpdateSession']);
    Route::post('/admin/cases/delete-session', [App\Http\Controllers\API\Admin\CaseController::class, 'deleteSession']);
    Route::post('admin/cases/download-session-details', [App\Http\Controllers\API\Admin\CaseController::class, 'sessionPdf']);

    Route::post('/admin/cases/consentdisclosures', [App\Http\Controllers\API\Admin\CaseController::class, 'getConsentDisclosures']);
    Route::post('/admin/cases/download-disclosures', [App\Http\Controllers\API\Admin\CaseController::class, 'downloadDisclosures']);
    Route::post('admin/cases/preview-disclosures', [App\Http\Controllers\API\Admin\CaseController::class, 'previewDisclosures']);

    Route::post('/admin/cases/viewsupporting', [App\Http\Controllers\API\Admin\UploadController::class, 'viewSupporting']);
    Route::post('/admin/cases/upload-files', [App\Http\Controllers\API\Admin\UploadController::class, 'storeMultiFile']);
    Route::post('/admin/cases/uploaddocument', [App\Http\Controllers\API\Admin\UploadController::class, 'documentUpload']);
    Route::post('/admin/cases/view-settlement', [App\Http\Controllers\API\Admin\CaseController::class, 'viewSettelment']);
    Route::post('/admin/cases/settlement-upload', [App\Http\Controllers\API\Admin\CaseController::class, 'settlementUpload']);


    Route::post('admin/download-document', [App\Http\Controllers\API\DownloadDocument::class, 'downloadSecure']);
    Route::post('admin/preview-document', [App\Http\Controllers\API\DownloadDocument::class, 'previewSecure']);

    Route::post('/admin/users/user-approve', [App\Http\Controllers\API\Admin\UsersController::class, 'userApprove']);
    Route::post('/admin/users/user-newreq', [App\Http\Controllers\API\Admin\UsersController::class, 'userNewreq']);
    Route::post('/admin/users/user-rejected', [App\Http\Controllers\API\Admin\UsersController::class, 'userRejected']);
    Route::post('/admin/users/update', [App\Http\Controllers\API\Admin\UsersController::class, 'update']);
    Route::post('/admin/users/user-delete', [App\Http\Controllers\API\Admin\UsersController::class, 'deleteUser']);
    Route::post('/admin/users/changeRole', [App\Http\Controllers\API\Admin\UsersController::class, 'ChangeRole']);
    Route::post('/admin/users/status-change-active', [App\Http\Controllers\API\Admin\UsersController::class, 'statusChange']);
    Route::post('/admin/users/status-change-approve', [App\Http\Controllers\API\Admin\UsersController::class, 'statusChangeApprove']);
    Route::post('/admin/users/add-notes', [App\Http\Controllers\API\Admin\UsersController::class, 'addNotes']);
    Route::get('/admin/users/getuserdata/{id}', [App\Http\Controllers\API\Admin\UsersController::class, 'getuserdata']);

    Route::post('/admin/users/mediator-approve', [App\Http\Controllers\API\Admin\UsersController::class, 'mediatorApprove']);
    Route::post('/admin/users/mediator-newreq', [App\Http\Controllers\API\Admin\UsersController::class, 'mediatorNewreq']);
    Route::post('/admin/users/mediator-rejected', [App\Http\Controllers\API\Admin\UsersController::class, 'mediatorRejected']);
    Route::get('/admin/users/getmediatordata/{id}', [App\Http\Controllers\API\Admin\UsersController::class, 'getMediatordata']);
    Route::get('/admin/users/area-specialization', [App\Http\Controllers\API\Admin\UsersController::class, 'getAreaOfSpecialization']);

});
 
 // User API Routes
Route::middleware(['apiauth', 'apiuser'])->group(function () {

    Route::post('/user/cases/newreq', [App\Http\Controllers\API\User\CaseController::class, 'newreq']);
    Route::post('/user/cases/ongoing', [App\Http\Controllers\API\User\CaseController::class, 'ongoing']);
    Route::post('/user/cases/closed', [App\Http\Controllers\API\User\CaseController::class, 'closed']);
    Route::post('/user/cases/rejected', [App\Http\Controllers\API\User\CaseController::class, 'rejected']);
    Route::post('/user/cases/uploaddocument', [App\Http\Controllers\API\User\UploadController::class, 'documentUpload']);

    Route::post('/user/cases/meeting-sessions', [App\Http\Controllers\API\User\CaseController::class, 'getMeetingSession']);
    Route::post('user/download-document', [App\Http\Controllers\API\DownloadDocument::class, 'downloadSecure']);
    Route::post('user/preview-document', [App\Http\Controllers\API\DownloadDocument::class, 'previewSecure']);

    // User profile update api
    Route::get('/user/show-profile', [App\Http\Controllers\API\User\ProfileController::class, 'getProfileData']);
    Route::post('/user/edit-profile-data', [App\Http\Controllers\API\User\ProfileController::class, 'editProfileData']);
    Route::get('/user/change-password', [App\Http\Controllers\API\User\ProfileController::class, 'changePassword']);

    // Case register api
    Route::post('/user/new-case', [App\Http\Controllers\API\User\MediationController::class, 'newCase']);

    // User notification api
    Route::get('/user/notifications', [App\Http\Controllers\API\User\MediationController::class, 'getNotifications']);

    // User track api
    Route::post('/user/case-track', [App\Http\Controllers\API\User\MediationController::class, 'caseTrack']);

    // User join code api
    Route::post('/user/join', [App\Http\Controllers\API\User\MediationController::class, 'caseJoin']);

    // User case details view api
    Route::post('/user/view-case-details', [App\Http\Controllers\API\User\MediationController::class, 'viewCaseDetails']);
});

  

Route::middleware(['apiauth', 'apimediator'])->group(function () {

    Route::post('/mediator/cases/newreq', [App\Http\Controllers\API\Mediator\CaseController::class, 'newreq']);
    Route::post('/mediator/cases/ongoing', [App\Http\Controllers\API\Mediator\CaseController::class, 'ongoing']);
    Route::post('/mediator/cases/closed', [App\Http\Controllers\API\Mediator\CaseController::class, 'closed']);
    Route::post('/mediator/cases/rejected', [App\Http\Controllers\API\Mediator\CaseController::class, 'rejected']);

    Route::post('/mediator/cases/upload-files', [App\Http\Controllers\API\Mediator\UploadController::class, 'storeMultiFile']);
    Route::post('/mediator/cases/uploaddocument', [App\Http\Controllers\API\Mediator\UploadController::class, 'documentUpload']);
    Route::post('/mediator/cases/settlement-upload', [App\Http\Controllers\API\Mediator\CaseController::class, 'settlementUpload']);

    Route::post('/mediator/case/add-session', [App\Http\Controllers\API\Mediator\CaseController::class, 'addSession']);
    Route::post('/mediator/cases/meeting-sessions', [App\Http\Controllers\API\Mediator\CaseController::class, 'getMeetingSession']);
    Route::post('/mediator/cases/update-session', [App\Http\Controllers\API\Mediator\CaseController::class, 'UpdateSession']);
    Route::post('/mediator/cases/delete-session', [App\Http\Controllers\API\Mediator\CaseController::class, 'deleteSession']);

    Route::post('/mediator/cases/close-case', [App\Http\Controllers\API\Mediator\CaseController::class, 'closeCaseStatus']);

    // Update mediator profile data api
    Route::get('/mediator/show-profile', [App\Http\Controllers\API\Mediator\ProfileController::class, 'getProfileData']);
    Route::post('/mediator/edit-profile-data', [App\Http\Controllers\API\Mediator\ProfileController::class, 'editProfileData']);
    Route::get('/mediator/change-password', [App\Http\Controllers\API\Mediator\ProfileController::class, 'changePassword']);
});


// User otp api
Route::post('/otp-verify', [App\Http\Controllers\API\UserController::class, 'otpVerify']);

// Forgot password api
Route::post('/forgot-pwd', [App\Http\Controllers\API\UserController::class, 'forgotPassword']);



 




/************************ UK Version API Section : END ****************************************************/