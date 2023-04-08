<?php

use App\Http\Controllers\Api\AuthController;
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

Route::post('/send-code', [AuthController::class, 'sendCode'])
    ->name('send-code');

Route::post('/get-tokens-by-code', [AuthController::class, 'getTokensByCode'])
    ->name('get-tokens-by-code');

Route::post('/get-tokens-by-credentials', [AuthController::class, 'getTokensByCredentials'])
    ->name('get-tokens-by-credentials');
