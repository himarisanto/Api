<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\QueryController;
use App\Http\Controllers\Api\ExecController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth.api')->group(function () {
    // route crud siswa
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/{id}', [SiswaController::class, 'show']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::post('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);
    // route crud user
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    // route crud server
    Route::get('/server', [ServerController::class, 'index']);
    Route::post('/server', [ServerController::class, 'store']);
    Route::get('/server/{server}', [ServerController::class, 'show']);
    Route::put('/server/{server}', [ServerController::class, 'update']);
    Route::delete('/server/{server}', [ServerController::class, 'destroy']);

    //route crud Query
    Route::get('/query', [QueryController::class, 'index']);
    Route::get('/queries', [QueryController::class, 'index']);
    Route::post('/queries', [QueryController::class, 'store']);
    Route::get('/queries/{id}', [QueryController::class, 'show']);
    Route::put('/queries/{id}', [QueryController::class, 'update']); 
    Route::delete('/queries/{id}', [QueryController::class, 'destroy']);
    Route::post('/execute/{query_id}', [ExecController::class, 'execute']);
    Route::post('/exec-query', [ExecController::class, 'executeQuery']);
    Route::get('/execute-query/{query_id}', [ExecController::class, 'executeQuery']);
});

//login register api siswa 
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
//route untuk menampilkan total
Route::get('/total-siswa', [SiswaController::class, 'getTotalSiswa']);
Route::get('/total-users', [UserController::class, 'getTotalUsers']);
