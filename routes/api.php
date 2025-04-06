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
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\KnowledgeBaseController;
use \Webfox\Xero\Controllers\AuthorizationController;
use \Webfox\Xero\Controllers\AuthorizationCallbackController;
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
    Route::get('/communities/{community_id}', [CommunityController::class, 'show']);

    Route::get('/my_business', [UserController::class, 'my_business'])->name('my_business');
    Route::post('/my_business_search', [UserController::class, 'my_business'])->name('my_business_search');

    Route::get('/documents', [UserController::class, 'documents']);

    Route::get('/knowledges', [KnowledgeBaseController::class, 'index']);

    Route::resource('/employees', EmployeeController::class);
    Route::post('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::get('/get_employee', [EmployeeController::class, 'getEmployee'])->name('getEmployee');
    Route::get('/client_list', [EmployeeController::class, 'client_list'])->name('client_list');
    Route::get('/employee_list/{client_id}', [EmployeeController::class, 'employee_list'])->name('employee_list');
    Route::post('/client_hrdoc_search/{client_id}', [EmployeeController::class, 'employee_list'])->name('client_hrdoc_search');
    Route::post('/employee_search', [EmployeeController::class, 'employee_search'])->name('employee_search');

    Route::resource('/events', EventController::class);
    Route::get('fullcalender/{client_id?}', [EventController::class, 'index']);
    Route::post('fullcalenderAjax', [EventController::class, 'add_update_delete']);

    Route::resource('/tickets', TicketController::class);
    Route::post('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

    Route::resource('/users', UserController::class);
    Route::post('/users/{users}', [UserController::class, 'update']);
    Route::get('/client_doc_search/{client_id}/edit', [UserController::class, 'edit'])->name('client_doc_search');

    Route::post('hr_docs', [UserController::class, 'hr_documents'])->name('hr_docs');

    Route::get('/client_events_list/{user_id?}', [EventController::class, 'client_events_list'])->name('client_events_list');

    Route::get('/ga', [HomeController::class, 'getAnalyticsData']);

    Route::get('/jobadder', [JobadderController::class, 'jobadder'])->name('jobadder');
    Route::get('/jobadder_data', [JobadderController::class, 'get_jobs'])->name('get_jobs');
    Route::get('/get_cv', [JobadderController::class, 'get_CV_Attachment'])->name('get_cv');

    Route::get('/xero', [XeroController::class, 'index'])->name('xero.auth.success');
//    Route::get('/xero/auth/authorize', AuthorizationController::class)->name('xero.auth.authorize');
//    Route::get('/xero/auth/callback', AuthorizationCallbackController::class)->name('xero.auth.callback');

    Route::post('/users_search', [UserController::class, 'search']);

    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{job_id}', [JobController::class, 'getJob']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{job_id}', [JobController::class, 'update']);
    Route::delete('/jobs/{job_id}', [JobController::class, 'destroy']);

    Route::get('/jobshared', [JobController::class, 'jobshared_list'])->name('job.shared');
    Route::post('/jobs/{job_id}/apply', [JobController::class, 'apply'])->name('job.apply');
});
