<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\AppUser\CartController;
use App\Http\Controllers\APPUser\PointController;
use App\Http\Controllers\AppUser\ReviewController;
use App\Http\Controllers\AppUser\AddressController;
use App\Http\Controllers\AppUser\appAuthController;
use App\Http\Controllers\AppUser\BookingController;
use App\Http\Controllers\AppUser\GeneralController;
use App\Http\Controllers\AppUser\AppUsersController;
use App\Http\Controllers\AppUser\UserProfileController;
use App\Http\Controllers\AppUser\NotificationController;
use App\Http\Controllers\AppUser\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'app-user'
], function ($router) {
    //auth
    Route::post('/logout', [appAuthController::class, 'logout']);
    Route::post('/login', [appAuthController::class, 'login']);
    Route::post('/register', [appAuthController::class, 'register']);
    Route::post('/check_number', [AppUsersController::class, 'check_number']);
    Route::post('/check_opt', [AppUsersController::class, 'check_opt']);
    Route::post('/save-token', [AppUsersController::class, 'saveToken'])->name('save-token');
    //booking
    Route::post('booking', [BookingController::class, 'bookMultipleServices']);
    Route::delete('/bookings/{id}', [BookingController::class, 'cancelBooking']);
    ///coupon
    Route::post('check-coupon', [BookingController::class, 'checkCoupon']);
    ///////////////addresses
    Route::apiResource('addresses', AddressController::class);
    // Route::get('/addresses', [AddressController::class, 'index']);
    //user
    Route::get('/user/bookings', [BookingController::class, 'userBookings']);
    Route::get('/user-profile', [UserProfileController::class, 'index']);
    Route::post('/update-profile', [UserProfileController::class, 'updateProfile']);
    Route::get('/deactive-account', [UserProfileController::class, 'deactive_account']);
    //////////cart
    Route::post('addItemToCart', [CartController::class, 'addItemToCart']);
    Route::post('removeItemFromCart', [CartController::class, 'removeItemFromCart']);
    Route::get('getCartItems', [CartController::class, 'getCartItems']);
    Route::get('getUserCart', [CartController::class, 'getUserCart']);
    //reviews route
    Route::post('/review', [ReviewController::class, 'store']);
    Route::post('/review/{review}', [ReviewController::class, 'update']);
    Route::delete('/review/{review}', [ReviewController::class, 'destroy']);
    /////////////////deleveryTimes

});
Route::group([
    'prefix' => 'app-user'
], function ($router) {
    Route::get('service-details/{service}', [BookingController::class, 'getServiceDetails']);
    //General
    Route::get('/products', [GeneralController::class, 'getAllProducts'])->name('products');
    Route::get('/products-most-common', [GeneralController::class, 'getProductMostCommon']);
    Route::get('/contact-us', [GeneralController::class, 'getContactUs']);
    Route::get('/about-us', [GeneralController::class, 'getAboutUs']);
    Route::get('/privacy', [GeneralController::class, 'getAllprivacy']);
    Route::get('/term', [GeneralController::class, 'getAllTerm']);
    Route::get('/setting', [GeneralController::class, 'getAllsetting']);
    Route::get('/delevery-times', [GeneralController::class, 'getDeleveryTimes']);
});
////////home page
Route::post('/check', [GeneralController::class, 'check_number']);
Route::post('/delete', [GeneralController::class, 'check_opt']);
require __DIR__ . '/dashboard.php';
