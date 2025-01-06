<?php

use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Period;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return redirect()->route('home');
})->middleware('auth');

Auth::routes();

Route::get('/reset_password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('reset.password');
Route::post('/change_password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('change.password');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/users', App\Http\Controllers\UserController::class);

    Route::resource('/tickets', App\Http\Controllers\TicketController::class);
    Route::resource('/employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('/events', App\Http\Controllers\EventController::class);
    Route::resource('/knowledgebases', App\Http\Controllers\KnowledgeBaseController::class);
    Route::get('/kb_client_list', [App\Http\Controllers\KnowledgeBaseController::class, 'kb_client_list'])->name('kb_client_list');
    Route::get('/kb_show', [App\Http\Controllers\KnowledgeBaseController::class, 'index'])->name('kb_show');
    Route::get('/kb_create', [App\Http\Controllers\KnowledgeBaseController::class, 'create'])->name('kb_create');
    Route::get('/kb_edit/{id}/edit', [App\Http\Controllers\KnowledgeBaseController::class, 'edit'])->name('kb_edit');
    // Client update profile
    Route::get('/update_profile', [App\Http\Controllers\UserController::class, 'update_profile'])->name('update_profile');
    Route::post('/update_client_profile/{client_id}', [App\Http\Controllers\UserController::class, 'update_client_profile'])->name('update_client_profile');

    Route::get('/my_business', [App\Http\Controllers\UserController::class, 'my_business'])->name('my_business');
    Route::post('/update_client_business/{client_id}', [App\Http\Controllers\UserController::class, 'update_client_business'])->name('update_client_business');
    // for admin to get employees of client
    Route::get('/client_list', [App\Http\Controllers\EmployeeController::class, 'client_list'])->name('client_list');
    Route::get('/employee_list/{client_id}', [App\Http\Controllers\EmployeeController::class, 'employee_list'])->name('employee_list');
    Route::get('/employee_view/{client_id}', [App\Http\Controllers\EmployeeController::class, 'employee_view'])->name('employee_view');
    //  Calendar events for clients
    Route::get('allevents', [App\Http\Controllers\EventController::class, 'allevents']);
    Route::get('fullcalender/{client_id?}', [App\Http\Controllers\EventController::class, 'index']);
    Route::post('fullcalenderAjax', [App\Http\Controllers\EventController::class, 'add_update_delete']);
    Route::get('/client_events_list/{user_id?}', [App\Http\Controllers\EventController::class, 'client_events_list'])->name('client_events_list');
    //TO save hr documets
    Route::post('hr_docs', [App\Http\Controllers\UserController::class, 'hr_documents'])->name('hr_docs');
    // Community
    Route::resource('/communities', App\Http\Controllers\CommunityController::class);
        // For search option created new routes
    Route::post('/community_search', [App\Http\Controllers\CommunityController::class, 'index'])->name('community_index');
    Route::post('/employee_search', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employee_index');
    Route::post('/my_business_search', [App\Http\Controllers\UserController::class, 'my_business'])->name('my_business_search');
    Route::post('/client_hrdoc_search/{client_id}', [App\Http\Controllers\EmployeeController::class, 'employee_list'])->name('client_hrdoc_search');
    Route::post('/client_doc_search/{client_id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('client_doc_search');
    Route::post('/people_doc_search/{client_id}/edit', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('people_doc_search');

    Route::get('/manage/xero/{xeroCredentials?}', [\App\Http\Controllers\XeroController::class, 'index'])->name('xero.auth.success');

    Route::get('/privacy', [App\Http\Controllers\UserController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [App\Http\Controllers\UserController::class, 'terms'])->name('terms');
    Route::get('/jobadder', [\App\Http\Controllers\JobadderController::class, 'index'])->name('jobadder');
    Route::get('/jobadder_data', [\App\Http\Controllers\JobadderController::class, 'get_jobs'])->name('get_jobs');
    Route::get('/manage/analytics', [\App\Http\Controllers\HomeController::class, 'check_analytics_view_id'])->name('check_analytics_view_id');
    Route::get('/get_CV_Attachment/{candidate_id?}/{new_token?}', [\App\Http\Controllers\JobadderController::class, 'get_CV_Attachment'])->name('get_CV_Attachment');
    Route::get('/client_error_page', [\App\Http\Controllers\JobadderController::class, 'client_error_page'])->name('client_error_page');

    Route::post('/reset_link', [App\Http\Controllers\UserController::class, 'reset_link'])->name('reset_link');

    Route::post('/search_jobadder', [\App\Http\Controllers\JobadderController::class, 'get_jobs'])->name('search_jobadder');

    Route::post('/dashboard_jobadder', [\App\Http\Controllers\JobadderController::class, 'dashboard_jobadder_data'])->name('dashboard_jobadder');

    // Route::get('/dates_data', [\App\Http\Controllers\JobadderController::class, 'dates_data'])->name('dates_data');
    Route::post('/delete_api_data', [\App\Http\Controllers\UserController::class, 'delete_api_data'])->name('delete_api_data');
});



