<?php

use App\Http\Controllers\TornaguiaController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
//Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
//Route::apiResource('tornaguia', App\Http\Controllers\TornaguiaController::class);
//post
Route::get('tornaguia', [App\Http\Controllers\TornaguiaController::class, 'index']);
Route::get('/tornaguia/vista/{id}', [TornaguiaController::class, 'vistaWeb']);


Route::get('tornaguia/print/{id}', [TornaguiaController::class, 'imprimir']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('tornaguia', [App\Http\Controllers\TornaguiaController::class, 'store']);
    Route::get('tornaguia/{id}', [App\Http\Controllers\TornaguiaController::class, 'show']);
    Route::put('tornaguia/{id}', [App\Http\Controllers\TornaguiaController::class, 'update']);
    Route::delete('tornaguia/{id}', [App\Http\Controllers\TornaguiaController::class, 'destroy']);

    Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout']);
    Route::post('/me', [App\Http\Controllers\UserController::class, 'me']);
    Route::put('/updatePassword/{user}', [App\Http\Controllers\UserController::class, 'updatePassword']);

    Route::apiResource('transporte', App\Http\Controllers\TransporteController::class);
    Route::apiResource('contratista', App\Http\Controllers\ContratistaController::class);
    Route::apiResource('empresa', App\Http\Controllers\EmpresaController::class);
    Route::apiResource('permissions', App\Http\Controllers\PermissionController::class);
//    Route::apiResource('tornaguia', App\Http\Controllers\TornaguiaController::class);
    Route::post('tornaguiaSearch', [App\Http\Controllers\TornaguiaController::class,'tornaguiaSearch']);
    Route::apiResource('driver', App\Http\Controllers\DriverController::class);
    Route::apiResource('user', App\Http\Controllers\UserController::class);
//    totales
    Route::get('totales', [App\Http\Controllers\TornaguiaController::class,'totales']);
    Route::post('attach', [\App\Http\Controllers\PermissionController::class,'attach']);
//    datos get
    Route::get('datos', [\App\Http\Controllers\DatosController::class,'datos']);
//    tornaguiasGet
    Route::get('tornaguiasGet', [\App\Http\Controllers\TornaguiaController::class,'tornaguiasGet']);
});
