<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('jwt.auth')->prefix('v1')->group(function () {
    Route::apiResource('carro', \App\Http\Controllers\CarroController::class);
    Route::apiResource('cliente', \App\Http\Controllers\ClienteController::class);
    Route::apiResource('locacao', \App\Http\Controllers\LocacaoController::class);
    Route::apiResource('marca', \App\Http\Controllers\MarcaController::class);
    Route::apiResource('modelo', \App\Http\Controllers\ModeloController::class);

    Route::post('me', [\App\Http\Controllers\AuthController::class, 'me']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);

