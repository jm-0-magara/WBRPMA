<?php

use Illuminate\Support\Facades\Route;

/** for side bar menu active */
function set_active($route) {
    if (is_array($route )){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}
/** for side bar menu show */
function set_show($route) {
    if (is_array($route )){
        return in_array(Request::path(), $route) ? 'show' : '';
    }
    return Request::path() == $route ? 'show' : '';
}

Route::get('/', function () {
    return view('auth.login');
});


Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('dashboard.home');
    });
    Route::get('home',function()
    {
        return view('dashboard.home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // -----------------------------login----------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::get('logout/page', 'logoutPage')->name('logout/page');
    });

    // ------------------------------ register ----------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');    
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // -------------------------- main dashboard ----------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->middleware('auth')->name('home');
        Route::get('/api/payments', 'getPayments');
    });

    // -------------------------- pages ----------------------//
    Route::controller(AccountController::class)->group(function () {
        Route::get('page/account', 'index')->middleware('auth')->name('page/account');
        Route::post('page/account/upload', 'uploadAvatar')->name('page/account/upload');
        Route::get('page/propertyInput', 'viewPropertyInput')->middleware('auth')->name('page/propertyInput');
    });

    // -------------------------- client ----------------------//
    Route::controller(ClientController::class)->group(function () {
        Route::get('/clients', 'clientView')->middleware('auth')->name('clients');
        Route::get('/tenants/{id}', 'show')->name('client.show');
        Route::get('/clients/add', 'clientAdd')->middleware('auth')->name('clients/add');
        Route::post('/add-tenant', 'addTenant')->middleware('auth')->name('addTenant');
    });

    // -------------------------- management ----------------------//
    Route::controller(ManagementController::class)->group(function () {
        Route::get('management/employee/list', 'employeeList')->middleware('auth')->name('management/employee/list');
        Route::get('management/houses/page', 'housesPage')->middleware('auth')->name('management/houses/page');
        Route::get('management/structure/page', 'structurePage')->middleware('auth')->name('management/structure/page');
        Route::get('management/pricing/page', 'pricingPage')->middleware('auth')->name('management/pricing/page');
    });

    // -------------------------- rentals/properties ----------------------//
    Route::controller(RentalController::class)->group(function () {
        Route::post('rentals/newProperty', 'storeNewRental')->middleware('auth')->name('rentals/newProperty');
        Route::get('rentals/view/updateProperty/{rentalNo}', 'viewUpdateRental')->middleware('auth')->name('rentals/view/updateProperty');
        Route::post('rentals/updateProperty/{rentalNo}', 'updateRental')->middleware('auth')->name('rentals/updateProperty');
        Route::get('rentals/selectProperty/{rentalNo}', 'selectRental')->middleware('auth')->name('rentals/selectProperty');
        Route::delete('rentals/deleteProperty/{rentalNo}', 'deleteRental')->middleware('auth')->name('rentals/deleteProperty');
    });

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('/addEmployeeRole', 'addEmployeeRole')->middleware('auth')->name('addEmployeeRole');
        Route::post('/addEmployee', 'addEmployee')->middleware('auth')->name('addEmployee');
        Route::get('view/updateEmployee/{employeeNo}', 'viewUpdateEmployee')->middleware('auth')->name('view/updateEmployee');
        Route::post('updateEmployee/{employeeNo}', 'updateEmployee')->middleware('auth')->name('updateEmployee');
        Route::delete('deleteEmployee/{employeeNo}', 'deleteEmployee')->middleware('auth')->name('deleteEmployee');
    });

    Route::controller(StructureController::class)->group(function () {
        Route::post('/structure/addStructure', 'addStructure')->middleware('auth')->name('structure/addStructure');
        Route::post('/structure/addStructureType', 'addStructureType')->middleware('auth')->name('structure/addStructureType');
        Route::post('/structure/addHouseType', 'addHouseType')->middleware('auth')->name('structure/addHouseType');
        Route::delete('deleteStructure/{structureName}', 'deleteStructure')->middleware('auth')->name('deleteStructure');
        Route::delete('deleteHouseType/{houseType}', 'deleteHouseType')->middleware('auth')->name('deleteHouseType');
    });

    // REMEMBER TO ADD DELETE & UPDATE ROUTES AND METHODS FOR THE HOUSES AND THE STRUCTURES

    Route::controller(HouseController::class)->group(function () {
        Route::get('/houses/addhouse/view', 'viewAddHouse')->middleware('auth')->name('addhouse/view');
        Route::post('/houses/addhouse', 'addHouse')->middleware('auth')->name('addHouse');
    });

    //-------------------------MPESA FUNCTIONALITY-----------------------//

    Route::controller(MPesaController::class)->prefix('pesa')->as('pesa')->group(function (){
        Route::get('/getaccesstoken', 'getAccessToken')->name('getaccesstoken');
        Route::get('/registerurl', 'registerUrl')->name('registerurl');
        Route::post('/validation', 'Validation')->name('validation');
        Route::post('/confitmation', 'Confirmation')->name('confirmation');
        Route::get('/simulate', 'Simulate')->name('simulate');
    });
    Route::controller(NotificationController::class)->group(function (){
        Route::get('/notifications', 'getNotifications');
    });
    Route::controller(MpesaNotificationController::class)->group(function (){
        Route::post('/mpesa/notification', 'handleMpesaNotification');
    }); 

    // ---------- Financial Management ----------- //

    Route::controller(FinancialController::class)->group(function () {
        Route::get('/payments', 'showPayments')->middleware('auth')->name('payments');
        Route::get('/payments/download-pdf', 'downloadPdf')->middleware('auth')->name('payments.downloadPdf');
        Route::get('/expenditures', 'showExpenditures')->middleware('auth')->name('expenditures');
        Route::get('/expenditures/download-pdf', 'downloadExpenditurePdf')->middleware('auth')->name('expenditures.downloadPdf');
    });

    Route::controller(SmsController::class)->group(function(){
        Route::post('/send-broadcast-sms', 'sendsms')->name('sendBroadcast');
    });

});


