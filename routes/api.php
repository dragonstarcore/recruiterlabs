<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobadderController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, function (Request $request) {
        return $request->user();
    }]);
    Route::put('/me', [UserController::class, 'update_client_profile']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::post('/dashboard_jobadder', [JobadderController::class, 'dashboard_jobadder_data'])->name('dashboard_jobadder');
    Route::get('/communities', [CommunityController::class, 'index']);
});
