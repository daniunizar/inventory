<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ForgotPasswordController;

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

// Sanctum
Route::post('auth/login', [App\Http\Controllers\Api\AuthController::class, 'loginUser'])->name('auth.login');

// Passwd Reset
Route::post('auth/forgot-password', [ForgotPasswordController::class, 'requestPasswordResetToken'])->name('auth.forgot-password');
Route::post('auth/submit-password', [ForgotPasswordController::class, 'handlePasswordResetToken'])->name('auth.submit-password');

Route::group([
    "middleware" => "guest"
], function($router){
    // Passwords
    // Route::get('password/request', [ManagePasswordController::class, "getRequestView"])->name('password.request'); //send first mail of reset password. Currently not in use
    // Route::get('password/reset', [ManagePasswordController::class, "getResetPasswordView"])->name('password.reset'); //return the form view (internal function)
    // Route::post('password/update', [ManagePasswordController::class, "resetPassword"])->name('password.update'); //update password in database, called when submit a reset password form
});

Route::group([
    'middleware' => 'auth:sanctum',
], function ($router) {  
  // ... all routes
  //auth
  Route::post('auth/me', 'App\Http\Controllers\Api\AuthController@me');
});