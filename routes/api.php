<?php

use App\Http\Controllers\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/run-query', [ApiController::class, 'runQuery'])->name('runQuery');
Route::post('/run-query-datatable', [ApiController::class, 'runQueryDatatable'])->name('runQuery');
Route::get('/run-query-by-id/{id}', [ApiController::class, 'runQueryById'])->name('runQueryById');
Route::post('/test-connection', [ApiController::class, 'testConnection'])->name('testConnection');