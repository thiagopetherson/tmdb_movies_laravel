<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\RatingController;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.auth')->group(function(){  

    Route::apiResource('users', UserController::class);
    Route::post('users-list-search', [UserController::class, 'usersListSearch']);
    
    Route::post('user-profiles-change-image-profile', [UserProfileController::class, 'changeImageProfile']);

    Route::apiResource('ratings', RatingController::class);
    Route::post('rating-by-user', [RatingController::class, 'ratingByUser']);
    Route::get('ratings-by-movie/{id}', [RatingController::class, 'ratingsByMovie']);
    Route::post('ratings-average-users', [RatingController::class, 'ratingsAverageUsers']);
    Route::get('ratings-last-ratings', [RatingController::class, 'lastRatings']);


    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('logout', [AuthController::class, 'logout']);
    
});

// Public Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
