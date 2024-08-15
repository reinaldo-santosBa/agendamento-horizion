<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/teste', function () {
    return response()->json([
        'message' => 'Teste',
    ]);
});

//auth
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

//users
Route::post('users', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->get('users/pacientes', [UserController::class, 'getUsuariosTipoP']);
Route::middleware('auth:sanctum')->get('users', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->get('users/{id}', [UserController::class, 'show']);
Route::delete('users/{id}', [UserController::class, 'destroy']);
Route::put('users/{id}', [UserController::class, 'update']);

//agendamentos
Route::middleware('auth:sanctum')->get('agendamentos/{id}', [AgendamentoController::class, 'show']);
Route::middleware('auth:sanctum')->post('agendamentos', [AgendamentoController::class, 'store']);
Route::middleware('auth:sanctum')->get('agendamentos', [AgendamentoController::class, 'getAll']);
Route::middleware('auth:sanctum')->put('agendamentos/{id}', [AgendamentoController::class, 'update']);
Route::middleware('auth:sanctum')->get('/agendamentos', [AgendamentoController::class, 'filterByDate']);
Route::middleware('auth:sanctum')->get('/agendamentos/usuario/todos', [AgendamentoController::class, 'getByUsuarioId']);
