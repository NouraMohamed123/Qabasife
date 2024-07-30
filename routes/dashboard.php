<?php

use App\Models\ControlBooking;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\AppUserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ManualNotification;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\OptionTypeController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\DeleveryTimeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ControlBookingController;
use App\Http\Controllers\Admin\PaymentGatewayController;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


});
Route::group([
    'middleware' => 'auth:users',
    'prefix' => 'dashboard'
], function ($router) {
    //users
Route::get('/me', [UserController::class, 'me']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::get('getUserCount', [UserController::class, 'getUserCount']);
Route::get('getAppUserCount', [UserController::class, 'getAppUserCount']);
//////////app users
Route::get('/app_user', [AppUserController::class, 'index']);
Route::get('/app_user/{user}', [AppUserController::class, 'show']);
Route::post('/app_user', [AppUserController::class, 'store']);
Route::post('/app_user/{user}', [AppUserController::class, 'update']);
Route::delete('/app_user/{user}', [AppUserController::class, 'destroy']);
//////////products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::post('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::get('getProductCount', [ProductController::class, 'getProductCount']);
//roles
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{role}', [RoleController::class, 'show']);
Route::post('/roles', [RoleController::class, 'store']);
Route::post('/roles/{role}', [RoleController::class, 'update']);
Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

//about_us
Route::get('about-us', [AboutUsController::class, 'index']);
Route::post('about-us', [AboutUsController::class, 'update']);
//terms
Route::get('terms', [TermsController::class, 'index']);
Route::post('terms', [TermsController::class, 'update']);
//privacy
Route::get('privacies', [PrivacyController::class, 'index']);
Route::post('privacies', [PrivacyController::class, 'update']);
//questions
Route::get('questions', [QuestionController::class, 'index']);
Route::post('questions', [QuestionController::class, 'store']);
Route::get('questions/{question}', [QuestionController::class, 'show']);
Route::post('questions/{question}', [QuestionController::class, 'update']);
Route::delete('questions/{question}', [QuestionController::class, 'destroy']);
//Contact
Route::get('contact', [ContactController::class, 'index']);
Route::post('contact', [ContactController::class, 'update']);
//setting
Route::get('/setting', [SettingController::class, 'index']);
Route::post('/setting', [SettingController::class, 'store']);

//reports
Route::get('/all-order', [ReportsController::class, 'all_orders']);
Route::get('getOrderCount', [ReportsController::class, 'getOrderCount']);


Route::get('/coupons', [CouponsController::class, 'index']);
Route::post('/coupons', [CouponsController::class, 'store']);
Route::get('/coupons/{coupon}', [CouponsController::class, 'show']);
Route::post('/coupons/{coupon}', [CouponsController::class, 'update']);
Route::delete('/coupons/{coupon}', [CouponsController::class, 'destroy']);

//reviews
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
/////////////////deleveryTimes
Route::get('/delevery-times', [DeleveryTimeController::class, 'index']);
Route::post('/delevery-times', [DeleveryTimeController::class, 'store']);
Route::get('/delevery-times/{deleverytime}', [DeleveryTimeController::class, 'show']);
Route::post('/delevery-times/{deleverytime}', [DeleveryTimeController::class, 'update']);
Route::delete('/delevery-times/{deleverytime}', [DeleveryTimeController::class, 'destroy']);







});

Route::post('/contact-us', [App\Http\Controllers\HomeController::class, 'contactUs']);
Route::get('/home-settings', [App\Http\Controllers\HomeController::class, 'Settings']);




