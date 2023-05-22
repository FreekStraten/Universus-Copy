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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// route for creating a competition
Route::middleware('api')->post('/createCompetition', [App\Http\Controllers\APIController::class, 'createCompetition']);
Route::middleware('api')->get('/getUserCompetition', [App\Http\Controllers\APIController::class, 'getUserCompetition']);
Route::middleware('api')->post('/uploadImage', [App\Http\Controllers\APIController::class, 'uploadImage']);

