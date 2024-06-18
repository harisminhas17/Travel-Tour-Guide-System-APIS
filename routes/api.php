<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportController;

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
Route::get('/users',[UsersController::class,'index']);
Route::post('/register',[UsersController::class,'register']);
Route::post('/login',[UsersController::class,'login']);
Route::post('/sendOTP',[UsersController::class,'sendOTP']);
Route::post('/verifyOTP',[UsersController::class,'verifyOTP']);
Route::post('/setPassword',[UsersController::class,'setPassword']);
Route::post('/deleteUser',[UsersController::class,'deleteUser']);
Route::get('/showAllPlaces',[PlacesController::class,'showAllPlaces']);
Route::get('/showAllNotifications',[NotificationController::class,'showAllNotifications']);
Route::Post('/addSupportMessage',[SupportController::class,'addSupportMessage']);
Route::Post('/updateProfile',[UsersController::class,'updateProfile']);
Route::Post('/sendNotificationToAll',[NotificationController::class,'sendNotificationToAll']);
Route::Post('/updateNotificationToken',[NotificationController::class,'updateNotificationToken']);
