<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::post("user-signup", [UserController::class, 'userSignUp']);

Route::post("user-login", [UserController::class, 'userLogin']);

Route::get("user-list", [UserController::class, 'userList']);

Route::get("user-list-role/{roleId}", [UserController::class, 'userListWithRole']);

Route::get("user-details/{userId}", [UserController::class, 'userDetails']);

Route::put('user/{id}',[UserController::class,'update']);

Route::delete('user/{id}',[UserController::class,'destroy']);