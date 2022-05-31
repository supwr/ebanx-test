<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Balance\BalanceController;
use App\Http\Controllers\Event\EventController;

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

Route::post('/reset', [ResetController::class, 'reset']);
Route::get('/balance', [BalanceController::class, 'getBalance']);
Route::post('/event', [EventController::class, 'postEvent']);
