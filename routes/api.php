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
});

 // Admin API Routes
Route::middleware(['apiauth', 'apiadmin'])->group(function () {

    Route::post('/admin/dashboard', [App\Http\Controllers\API\Admin\AdminController::class, 'dashboard']);
    Route::post('/admin/cases/ongoing', [App\Http\Controllers\API\Admin\CaseController::class, 'ongoing']);
    Route::post('/admin/cases/newreq', [App\Http\Controllers\API\Admin\CaseController::class, 'newreq']);
    Route::post('/admin/cases/closed', [App\Http\Controllers\API\Admin\CaseController::class, 'closed']);
    Route::post('/admin/cases/rejected', [App\Http\Controllers\API\Admin\CaseController::class, 'rejected']);

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
    
    Route::post('/admin/case/add-session', [App\Http\Controllers\API\Admin\CaseController::class, 'addSession']);
    Route::post('/admin/cases/consentdisclosures', [App\Http\Controllers\API\Admin\CaseController::class, 'getConsentDisclosures']);
    Route::post('/admin/cases/download-disclosures', [App\Http\Controllers\API\Admin\CaseController::class, 'downloadDisclosures']);
    Route::post('/admin/cases/meeting-sessions', [App\Http\Controllers\API\Admin\CaseController::class, 'getMeetingSession']);

    Route::post('/admin/cases/viewsupporting', [App\Http\Controllers\API\Admin\UploadController::class, 'viewSupporting']);
    Route::post('/admin/cases/upload-files', [App\Http\Controllers\API\Admin\UploadController::class, 'storeMultiFile']);
    Route::post('/admin/cases/uploaddocument', [App\Http\Controllers\API\Admin\UploadController::class, 'documentUpload']);


    Route::post('admin/download-document', [App\Http\Controllers\API\DownloadDocument::class, 'downloadSecure']);

});







/************************ UK Version API Section : END ****************************************************/