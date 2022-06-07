<?php

use Illuminate\Support\Facades\Route;


// Laravel Auth Package Routes
Auth::routes();
Auth::routes(['verify' => true]);

// Home Route
Route::get('/', 'HomeController@index')->name('welcome');


/* <MBI> */

// Fully verified user
Route::group([
    'prefix'     => 'user',
    'as'         => 'user.',
    'namespace'  => 'User',
    'middleware' => ['auth','user','verified', 'verifiedphone']], function () {

    # dashboard
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    # transaction 
    Route::post('/transaction/multiple-users', 'TransactionController@multipleTransfer')->name('transaction.multipleTransfer');
    Route::post('/transaction/credit', 'TransactionController@creditInternal')->name('transaction.credit');
    Route::get('transfer/between-accounts', 'TransferController@between_accounts')->name('transfer.between_accounts');
    Route::get('transfer/another-user', 'TransferController@other_user')->name('transfer.other_user');
    Route::get('transfer/multiple-users', 'TransferController@multiple_users')->name('transfer.multiple_users');
    Route::get('transfer/wire', 'TransferController@wire')->name('transfer.wire');


});


/* </MBI>  */


// Language Route
Route::get('lang/{code}','HomeController@lang')->name('lang');


// Login, Logout & Subscribe Route
Route::get('/admin/logout', 'Auth\LoginController@logout', 'logout')->name('admin.logout');
Route::get('/logout', 'User\LoginController@logout', 'logout')->name('logout');

// User Auth Routes
Route::get('/register', 'User\LoginController@register_form')->name('register');

// Phone Verification Routes
Route::get('phone-verification', 'User\LoginController@phone_verification')->name('phone.verification')->middleware('auth');
Route::post('phone-verification-check', 'User\LoginController@phone_verification')->name('phone.verification.check');
Route::get('phone-verification-view', 'User\LoginController@phone2fa_view')->name('phone.verification.view');
Route::get('phone-verification-show', 'User\LoginController@phone2fa_view')->name('phone.verification.show')->middleware('auth');
Route::post('phone-verification-resend', 'User\LoginController@phone_verification_resend')->name('phone.verification.resend')->middleware('auth');

// Register Route
Route::post('/register', 'User\LoginController@register')->name('register');

// Install
Route::get('install','Admin\AdminController@install_install')->name('install');
Route::get('install/check','Admin\AdminController@install_check')->name('install.check');
Route::get('install/info','Admin\AdminController@install_info')->name('install.info');
Route::get('install/migrate','Admin\AdminController@install_migrate')->name('install.migrate');
Route::get('install/seed','Admin\AdminController@install_seed')->name('install.seed');
Route::post('install/store','Admin\AdminController@install_send');

// todo: move this to user >

// User heartbeat
Route::post('/user/heartbeat', 'Admin\UserController@heartbeat')->name('user.heartbeat');

// Login
Route::post('/user/login', 'Admin\UserController@login')->name('user.login');
Route::post('/login/g2fa', 'Admin\UserController@g2fa')->name('login.g2fa');
Route::post('/avatar/update', 'User\DashboardController@update_avatar')->name('dashboard.update_avatar');

// todo: move this to user <

// User OTP Send Route userg2fa
Route::post('/user/otp', 'Admin\UserController@userOtp')->name('user.otp.resend');
Route::get('/user/otp', 'Admin\UserController@userOtp')->name('user.otp');
Route::post('/user/otp', 'Admin\UserController@userOtp')->name('user.otp.resend');
Route::get('/user/otp-view', 'Admin\UserController@userOtpView')->name('user.otp.view');
Route::get('/profile/otp', 'Admin\UserController@profileOtp')->name('profile.otp');
Route::post('/profile/otp', 'Admin\UserController@profileOtp')->name('profile.otp.resend');
Route::get('/profile/otp-view', 'Admin\UserController@profileOtpView')->name('profile.otp.view');
Route::post('/profile/otp/confirmation', 'Admin\UserController@profileOtpConfirmation')->name('profile.otp.confirmation');
Route::post('/user/otp/confirmation', 'Admin\UserController@userOtpConfirmation')->name('login.otp.confirmation');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {

    /* <MBI> */

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    # system requests
    Route::get('requests', 'SystemRequestsController@index')->name('requests.index');
    Route::get('requests/view/{id}', 'SystemRequestsController@view')->name('requests.view');
    Route::post('request/{id}', 'SystemRequestsController@setstatus')->name('request.setstatus');
    
    // transactions
    Route::get('transactions', 'TransactionController@index')->name('transactions.index');
    Route::get('transactions/view/{id}', 'TransactionController@view')->name('transactions.view');
    Route::get('deposit', 'TransactionController@deposit')->name('transactions.deposit');
    Route::post('deposit', 'TransactionController@makedeposit')->name('transactions.makedeposit');
    //Route::get('all/transaction/search/report', 'TransactionController@allTransactionSearchReport')->name('all.transaction.search.report');

    # support
    Route::resource('support', 'SupportController');
    Route::get('support', 'SupportController@index')->name('support.index');
    Route::post('supportInfo', 'SupportController@getSupportData')->name('support.info');
    Route::post('supportstatus', 'SupportController@supportStatus')->name('support.status');
    Route::post('supportSend', 'SupportController@newmessage');
    Route::post('getCategoryRecent', 'SupportController@getCategoryRecent');
    Route::post('searchInCategory', 'SupportController@searchInCategory');
    Route::post('markseen', 'SupportController@markseen');
    Route::get('support/view/{category}', 'SupportController@getCategory')->name('support.getCategory');
    
    # currencies
    Route::resource('currency', 'CurrencyController');
    Route::post('currency/create', 'CurrencyController@store')->name('currency.create');
    Route::post('currency/update/{id}', 'CurrencyController@update')->name('currency.up');
    Route::post('currency/destroy/{id}', 'CurrencyController@destroy')->name('cur.destroy');
    Route::post('currency/status/{id}', 'CurrencyController@setstatus')->name('currency.setstatus');

    # fees
    Route::get('fees', 'FeesController@index')->name('fees.index');
    Route::get('fees/edit/{id}', 'FeesController@edit')->name('fees.edit');

    # accounts
    Route::get('accounts', 'AccountsController@index')->name('accounts.index'); 
    Route::get('accounts/new', 'AccountsController@newaccount')->name('accounts.newaccount');
    Route::post('accounts/addnew', 'AccountsController@addnewaccount')->name('accounts.addnewaccount'); 
    Route::get('accounts/view/{id}', 'AccountsController@view')->name('accounts.view');
    Route::get('account/types', 'AccountsController@types')->name('account.types'); 
    Route::get('account/types/new', 'AccountsController@newtype')->name('accounts.newtype');
    Route::post('accounts/type/new', 'AccountsController@addnewtype')->name('accounts.addnewtype');
    Route::get('account/types/edit/{id}', 'AccountsController@editacctype')->name('accounts.editacctype');
    Route::post('accounts/types/update/{id}', 'AccountsController@updateacctype')->name('accounts.updateacctype');
    Route::get('accounts/search', 'AccountsController@searchaccount')->name('accounts.searchaccount');

    Route::get('users/search', 'UserController@searchuser')->name('user.search');
    // users
    Route::resource('users', 'UserController');
    Route::post('users/docs/set/{id}', 'UserController@setkycstatus')->name('user.setkycstatus');
    Route::get('users/docs/get/{id}', 'UserController@getuserdocs')->name('user.getdocs');
    Route::post('users/disable/{id}', 'UserController@setstatus')->name('user.setstatus');
    Route::get('users/search', 'UserController@searchuser')->name('user.search');

    // todo: remove unnecesarry routes
    Route::get('users/verified_users/index', 'UserController@verified_users')->name('verified_users');
    Route::get('profile/{id}', 'UserController@profile')->name('profile');
    Route::get('profile/edit/{id}', 'UserController@profile_edit')->name('profile.edit');
    Route::put('profile/update/{id}', 'UserController@profile_update')->name('profile.update');
    Route::put('user/credits/{id}', 'UserController@userCredits')->name('user.credits');
    Route::put('user/transactions/mail/{id}', 'UserController@userTransactionMail')->name('user.transaction.mail');
    Route::get('user/login/{id}', 'UserController@userLogin')->name('user.login');
    Route::get('user/report/{id}', 'UserController@transactionReport')->name('user.transaction.report');

    /* </MBI> */
});


Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User', 'middleware' => ['auth','user','verified','verifiedphone']], function () {
    
    // <MBI>
    
    Route::get('account/view/{id}', 'AccountsController@view')->name('account.view'); 

    Route::get('notifications', 'DashboardController@notifaction_list')->name('notifications');
    Route::post('markNotification', 'DashboardController@markNotification')->name('markNotification');
    
    //Support Route 
    Route::resource('support', 'SupportController');
    Route::post('support/create', 'SupportController@store')->name('sup.store');

    //Transaction History
    Route::get('transaction/history', 'TransactionController@history')->name('transaction.history');
    Route::get('transaction/pdf', 'TransactionController@transactionPDF')->name('transaction.pdf');
    Route::get('transaction/search', 'TransactionController@transcctionSearch')->name('transaction.search');
    Route::get('transaction/view/{id}', 'TransactionController@view')->name('transaction.view');
    Route::post('transaction/createwire', 'TransactionController@createwire')->name('wire.create');
    Route::post('transaction/accountISOcurrency', 'TransactionController@accountISOcurrency')->name('transaction.accountISOcurrency');
    
    // </MBI>

    // User Setting 
    Route::get('account-settings', 'AccountSettingController@accountSetting')->name('account.setting');
    Route::post('account-settings/confirmation', 'AccountSettingController@accountSettingConfirmation')->name('account.setting.confirmation');
    Route::get('account-password/change', 'AccountSettingController@accountPasswordChange')->name('account.password.change');
    Route::post('account-password/update', 'AccountSettingController@accountPasswordUpdate')->name('account.password.update');
    Route::get('account-password/change/otp/view', 'AccountSettingController@accountPasswordOtpView')->name('account.password.change.otp.view');
    Route::post('account-password/otp/confirmation', 'AccountSettingController@accountPasswordOtp')->name('account.password.otp.confirmation');
    Route::post('account-password/resend/otp', 'AccountSettingController@accounOtpResend')->name('account.otp.reset');
    Route::post('account/info/update', 'AccountSettingController@accountUpdate')->name('account.update');

});
