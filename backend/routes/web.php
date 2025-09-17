<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-web', function () {
    return response()->json([
        'message' => 'Web route is working!',
        'timestamp' => now()
    ]);
});
