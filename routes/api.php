<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\GuiderController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\VisitedController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\BookingController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/users',[UsersController::class,'index']);

Route::post('/register',[UsersController::class,'register']);
Route::post('/login',[UsersController::class,'login']);
Route::post('/sendOTP',[UsersController::class,'sendOTP']);
Route::post('/verifyOTP',[UsersController::class,'verifyOTP']);
Route::post('/setPassword',[UsersController::class,'setPassword']);
Route::post('/deleteUser',[UsersController::class,'deleteUser']);
Route::Post('/updateProfile',[UsersController::class,'updateProfile']);
Route::Post('/appReview',[UsersController::class,'appReview']);

Route::get('/showAllPlaces',[PlacesController::class,'showAllPlaces']);
Route::get('/searchPlaces',[PlacesController::class,'searchPlaces']);
Route::Post('/sendUserPlaceReviews',[PlacesController::class,'sendUserPlaceReviews']);
Route::get('getPlacesVideo/',[PlacesController::class,'getPlacesVideo']);

Route::get('/showAllNotifications',[NotificationController::class,'showAllNotifications']);
Route::Post('/sendNotificationToAll',[NotificationController::class,'sendNotificationToAll']);
Route::Post('/updateNotificationToken',[NotificationController::class,'updateNotificationToken']);
Route::Post('/sendConfirmNotification',[NotificationController::class,'sendConfirmNotification']);


Route::Post('/addSupportMessage',[SupportController::class,'addSupportMessage']);



Route::get('/getHotelbyCityid',[HotelController::class,'getHotelbyCityid']);
Route::Post('/sendUserHotelReview',[HotelController::class,'sendUserHotelReview']);
Route::get('/findTransportationbyCityid',[HotelController::class,'findTransportationbyCityid']);

Route::get('/findGuiderByCityid',[GuiderController::class,'findGuiderByCityid']);
Route::get('/getGuiderReviews',[GuiderController::class,'getGuiderReviews']);

Route::Post('/markAsFavorite',[MarkController::class,'markAsFavorite']);
Route::Post('/markAsVistied',[VisitedController::class,'markAsVistied']);

Route::Post('/uploadPhotos',[AlbumController::class,'uploadPhotos']);

Route::Post('bookingTour',[BookingController::class,'bookingTour']);


