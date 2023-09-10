<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

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

Route::controller(UserController::class)->group(function () {
    // Route::get('/orders/{id}', 'show');
    Route::post('/users', 'register');
});
Route::middleware('auth:api')->group(function () {
    // Your protected API routes here
    Route::resource('news', NewsController::class);
});
