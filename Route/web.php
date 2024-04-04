<?php
// Replace 'HostingController' with your actual controller name

// Routes for Views
Route::group(['middleware' => ['auth']], function () {
    Route::get('/hosting', 'HostingController@index');
    Route::post('/hosting/remove_duplicates', 'HostingController@removeDuplicates');
    Route::get('/hosting/gifwalletapi', 'HostingController@gifwalletapi');
    Route::get('/hosting/verifykey', 'HostingController@verifykey');
    Route::get('/hosting/createssotoken', 'HostingController@createssotoken');
    Route::get('/hosting/accesspass', 'HostingController@accesspass');
    Route::get('/hosting/whmcsorders', 'HostingController@whmcsorders');
    Route::get('/hosting/completewhmcsorders', 'HostingController@completewhmcsorders');
    Route::get('/hosting/purchasewhmcsorders', 'HostingController@purchasewhmcsorders');
    Route::get('/hosting/user_hosting_renew', 'HostingController@userHostingRenew');
    Route::get('/hosting/user_hosting_payment', 'HostingController@userHostingPayment');

});
