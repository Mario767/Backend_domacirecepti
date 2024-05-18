<?php

use Illuminate\Support\Facades\Route;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::middleware([EnsureFrontendRequestsAreStateful::class])
    ->get('/sanctum/csrf-cookie', function (Request $request) {
        return response()->json();
    });

Route::get('/', function () {
    return view('welcome');
});

