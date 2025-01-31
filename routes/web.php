<?php
// Replace 'HostingController' with your actual controller name

use Illuminate\Support\Facades\Route;
use Modules\Hosting\Http\Controllers\HostingController;

// Ensure the HostingController class exists and is correctly namespaced
// If the class does not exist, create it in the specified namespace

// Routes for Views
Route::group(['middleware' => ['auth']], function () {
    Route::get('/hosting',[HostingController::class, 'index']);
    Route::post('/hosting/remove_duplicates',[HostingController::class, 'removeDuplicates'] );
    Route::get('/hosting/gifwalletapi',[HostingController::class, 'gifwalletapi'] );
    Route::get('/hosting/verifykey',[HostingController::class, 'verifykey'] );
    Route::get('/hosting/createssotoken', [HostingController::class, 'createssotoken']);
    Route::get('/hosting/accesspass', [HostingController::class, 'accesspass']);
    Route::get('/hosting/whmcsorders', [HostingController::class, 'whmcsorders']);
    Route::get('/hosting/completewhmcsorders', [HostingController::class, 'completewhmcsorders']);
    Route::get('/hosting/purchasewhmcsorders',[HostingController::class, 'purchasewhmcsorders'] );
    Route::get('/hosting/user_hosting_renew',[HostingController::class, 'userHostingRenew'] );
    Route::get('/hosting/user_hosting_payment', [HostingController::class, 'userHostingPayment']);

});
