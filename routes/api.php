<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::middleware('auth:sanctum')->group(function (): void {

        Route::get('/user', function (Request $request): array {

            return ['data' => $request->user()];

        })->name('user');

        Route::get('/test', fn () => response()->json('test'))->name('test');

    });
});
