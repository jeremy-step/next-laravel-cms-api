<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {

    Route::middleware('auth:sanctum')->group(function (): void {

        // --- AUTH ---

        Route::name('users.')
            ->prefix('/users')
            ->controller(UserController::class)
            ->group(function (): void {

                Route::name('sessions.')->prefix('/sessions')->group(function (): void {

                    Route::get('/', 'getSessions')->name('index');
                    Route::get('/{user}', 'getUserSessions')->name('get');

                });

                Route::post('/invite', 'invite')->name('invite');
                Route::patch('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
                Route::get('/authenticated', 'getAuthenticated')->name('authenticated');
                Route::get('/is-authenticated', 'isAuthenticated')->name('is-authenticated');
                Route::get('/', 'index')->name('index');
                Route::get('/{user}', 'get')->name('get');

            });

        // --- PAGES ---

        Route::name('pages.')
            ->prefix('/pages')
            ->controller(PageController::class)
            ->group(function (): void {

                Route::post('/', 'store')->name('store');
                Route::patch('/{page}', 'update')->name('update');
                Route::delete('/{page}', 'destroy')->name('destroy');
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'get')->name('get');

            });

    });

    // --- PAGES ---

    Route::name('pages.')->controller(PageController::class)->group(function (): void {

        Route::get('/page/{page:permalink?}', 'getByPermalink')->name('permalink');

    });

});
