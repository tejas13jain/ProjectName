<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppoinmentController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DoctorController;

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


//login
Route::post('/login', [UsersController::class, 'loginUser'])->name('login');
Route::post('/register', [UsersController::class, 'signup'])->name('signup');

//Appoinments 
Route::get('/getAppointment', [AppoinmentController::class, 'getAppointmentAll']);
Route::post('/appointment', [AppoinmentController::class, 'appointment'])->name('appointment');
Route::post('/updateStatus/{id}', [AppoinmentController::class, 'updateStatus'])->name('updateStatus');
Route::post('/filterDate', [AppoinmentController::class, 'filterDate'])->name('filterDate');

Route::post('/updateStatusByDoctor/{id}', [DoctorController::class, 'updateStatusByDoctor'])->name('updateStatusByDoctor');

