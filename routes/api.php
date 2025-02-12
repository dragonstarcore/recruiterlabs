<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobadderController;
use App\Http\Controllers\XeroController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FileUploadController;

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
    Route::get('/my_business', [App\Http\Controllers\UserController::class, 'my_business'])->name('my_business');
    Route::get('/documents', [App\Http\Controllers\UserController::class, 'documents']);
    Route::get('/knowledges', [App\Http\Controllers\KnowledgeBaseController::class, 'index']);
    Route::post('/my_business_search', [App\Http\Controllers\UserController::class, 'my_business'])->name('my_business_search');
    Route::get('/xero', [\App\Http\Controllers\XeroController::class, 'index'])->name('xero.auth.success');
    Route::resource('/employees', App\Http\Controllers\EmployeeController::class);
    Route::get('fullcalender/{client_id?}', [App\Http\Controllers\EventController::class, 'index']);
    Route::resource('/events', App\Http\Controllers\EventController::class);
    Route::post('fullcalenderAjax', [App\Http\Controllers\EventController::class, 'add_update_delete']);
    Route::resource('/tickets', App\Http\Controllers\TicketController::class);
    Route::post('/tickets/search', [App\Http\Controllers\TicketController::class, 'search'])->name('tickets.search');
    Route::resource('/users', App\Http\Controllers\UserController::class);
    Route::get('/client_doc_search/{client_id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('client_doc_search');
});
