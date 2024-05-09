
use Illuminate\Http\Request;
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\PartyListController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ElectionController;


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

});
