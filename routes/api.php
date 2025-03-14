<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/register', 'AuthController@store');
    Route::post('/login', 'AuthController@authenticate');
    //
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('projects', 'ProjectController');
        Route::apiResource('timesheets', 'TimesheetController');
        Route::apiResource('attributes', 'AttributeController');
        Route::post('/logout', 'AuthController@logout');
    });
});
