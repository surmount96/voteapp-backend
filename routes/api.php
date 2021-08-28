<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login/start', [AuthController::class, 'LoginStart']);
Route::post('/login/finish', [AuthController::class, 'LoginFinish']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/contestants', [AuthController::class, 'fetchAllContestants'])->middleware('onetime');
    Route::post('/submit/vote', [AuthController::class, 'submitVote']);
});
