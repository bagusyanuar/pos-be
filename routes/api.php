<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app_name' => env('APP_NAME'),
        'app_version' => env('APP_VERSION')
    ], 200);
});

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    });


    Route::group(['middleware' => 'jwt.verify'], function () {

        Route::group(['prefix' => 'unit'], function () {
            Route::post('/', [App\Http\Controllers\UnitController::class, 'create']);
            Route::get('/', [App\Http\Controllers\UnitController::class, 'findAll']);
            Route::get('/{id}', [App\Http\Controllers\UnitController::class, 'findByID']);
            Route::put('/{id}', [App\Http\Controllers\UnitController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\UnitController::class, 'delete']);
        });

        Route::group(['prefix' => 'material'], function () {
            Route::post('/', [App\Http\Controllers\MaterialController::class, 'create']);
            Route::get('/', [App\Http\Controllers\MaterialController::class, 'findAll']);
            Route::get('/{id}', [App\Http\Controllers\MaterialController::class, 'findByID']);
            Route::put('/{id}', [App\Http\Controllers\MaterialController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\MaterialController::class, 'delete']);
        });
    });
});
