<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {

    Route::middleware('auth:sanctum')->group(function (): void {

        // --- AUTH ---

        Route::get('/user', function (Request $request): UserResource {

            return new UserResource($request->user());

        })->name('user');

        Route::get('/authenticated', fn () => null)->name('authenticated');

        // --- PAGES ---

        Route::name('pages.')->controller(PageController::class)->group(function (): void {

            Route::post('/pages', 'store')->name('store');

            Route::patch('/pages/{page}', 'update')->name('update');

            Route::delete('/pages/{page}', 'destroy')->name('destroy');

        });

    });

    // --- PAGES ---

    Route::name('pages.')->controller(PageController::class)->group(function (): void {

        Route::get('/pages', 'index')->name('index');

        Route::get('/pages/{id}', 'get')->whereUuid('page')->name('get');

    });

});
