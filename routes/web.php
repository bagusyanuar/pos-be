<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app_name' => env('APP_NAME'),
        'app_version' => env('APP_VERSION')
    ], 200);
});
