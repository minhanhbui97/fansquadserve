<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\OperatingSystemController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TypeOfMachineController;
use App\Http\Controllers\UserController;
use App\Models\TypeOfMachine;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    
});

Route::get('/users', [UserController::class, 'index']);

Route::post('/users', [UserController::class, 'store']);

Route::put('/users/{user}', [UserController::class, 'update']);

Route::get('/type_of_machines', [TypeOfMachineController::class, 'index']);

Route::get('/operating_systems', [OperatingSystemController::class, 'index']);

Route::get('/priorities', [PriorityController::class, 'index']);

Route::get('/courses', [CourseController::class, 'index']);

Route::get('/students/{student:fanshawe_id}', [StudentController::class,'show']);
Route::post('/students', [StudentController::class,'store']);
Route::put('/students/{student:fanshawe_id}', [StudentController::class,'update']);

Route::get('/tickets', [TicketController::class, 'index']);

Route::post('/tickets', [TicketController::class, 'store']);

Route::get('/tickets/{id}', [TicketController::class,'show']);

Route::get('/programs', [ProgramController::class, 'index']);

Route::get('/ticket_statuses', [TicketStatusController::class, 'index']);

Route::put('/tickets/{ticket}', [TicketController::class, 'update']);

Route::get('/reference-number', function () {
    return random_int(1000000, 9999999);
});