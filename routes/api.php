<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\MontageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/content', [ContentController::class, 'get']);
Route::post('/montage/schedule', [MontageController::class, 'getSchedule']);
Route::post('/montage/enroll', [MontageController::class, 'enroll']);
