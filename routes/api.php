<?php

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

Route::get('/students/{student:fanshawe_id}', [StudentController::class,'show']);

Route::get('/programs', [ProgramController::class, 'index']);

Route::get('/type_of_machines', [TypeOfMachineController::class, 'index']);

Route::get('/operating_systems', [OperatingSystemController::class, 'index']);

Route::get('/priorities', [PriorityController::class, 'index']);

Route::get('/courses', [CourseController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);

Route::get('/tickets', [TicketController::class, 'index']);

Route::post('/tickets', [TicketController::class, 'store']);


