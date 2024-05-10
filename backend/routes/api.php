use Illuminate\Http\Request;
<?php

use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/Login', [UserController::class, 'Login']);
Route::post('/Register', [UserController::class, 'Register']);
Route::middleware('auth:api')->group(function () {
    //USER API
    Route::get('/authenticate', [UserController::class, 'authenticate']);
    Route::get('/GetUserDetails', [UserController::class, 'GetUserDetails']);
    Route::get('/GetUsers', [UserController::class, 'GetUsers']);
    Route::delete('/DeleteUser', [UserController::class, 'DeleteUser']);
    Route::post('/InsertUser', [UserController::class, 'InsertUser']);
    Route::post('/Logout', [UserController::class, 'Logout']);
    Route::post('/ReadUserQR', [UserController::class, 'ReadUserQR']);
    Route::post('/UpdateUserDetails', [UserController::class, 'UpdateUserDetails']);

    // Attendance Logs
    Route::get('/getAttendance', [AttendanceLogController::class, 'getAttendance']);

    // Schedule
    Route::get('/getSchedule', [ScheduleController::class, 'getSchedule']);
});
