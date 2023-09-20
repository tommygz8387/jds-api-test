<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\CommentController;

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
Route::controller(PublicController::class)->prefix('public')->group(function () {
    Route::get('/', 'getNews');
    Route::get('/detail/{id}', 'getNewsDetail')->where('id', '[0-9]+');
    Route::get('/user/detail/{id}', 'getUserProfile')->where('id', '[0-9]+');
});;
Route::controller(AuthController::class)->prefix('users')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::delete('logout', 'logout')->middleware(ApiAuthMiddleware::class);
});
Route::middleware(ApiAuthMiddleware::class)->controller(UserController::class)
->prefix('users')->group(function () {
    Route::patch('current', 'update');
    Route::get('current', 'getUser');
});
Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::apiResource('news', NewsController::class)->middleware(RoleMiddleware::class);
    Route::apiResource('comment', CommentController::class);
});
