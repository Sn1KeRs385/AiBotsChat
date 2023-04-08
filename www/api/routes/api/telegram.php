<?php

use App\Http\Controllers\Api\Telegram\WebhookController;
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

Route::prefix('webhook')
    ->middleware('telegram')
    ->name('webhook.')
    ->controller(WebhookController::class)
    ->group(function(){
        Route::post('chat-gpt', 'chatGpt')
            ->name('chat-gpt');
    });
