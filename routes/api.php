<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;

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

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(ApiAuthMiddleware::class)->controller(UserController::class)
->prefix('users')->group(function () {
    Route::get('current', 'getUser');
    Route::get('current/{id}', 'getUserById');
    Route::patch('current', 'update');
    Route::delete('logout', 'logout');
});
Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('news/logged', [NewsController::class,'getMyNews']);
    Route::apiResource('news', NewsController::class);
});
