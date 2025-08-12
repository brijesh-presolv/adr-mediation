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
// Route::group([
//     'prefix' => 'auth',
// ], function ($router) {
   
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//     Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);
});

 //Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);

 Route::post('/register', [App\Http\Controllers\API\UserController::class, 'register']);


 // Admin API Routes
 Route::middleware(['apiauth', 'admin'])->group(function () {

 });

/************************ UK Version API Section : END ****************************************************/