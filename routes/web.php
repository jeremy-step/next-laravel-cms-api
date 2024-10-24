<?php

declare(strict_types=1);

use App\Http\Middleware\FrontRoutesMiddleware;
use Illuminate\Support\Facades\Route;

Route::name('front.')
    ->middleware(FrontRoutesMiddleware::class)
    ->group(function (): void {

        Route::post('/api/set-cookies')->name('set.cookies');

        Route::name('cp.')
            ->prefix('/control-panel')
            ->middleware('front:auth')
            ->group(function (): void {

                Route::get('/dashboard')->name('dashboard.index');

                Route::name('pages.')
                    ->prefix('/pages')
                    ->group(function (): void {

                        Route::get('/')->name('index');
                        Route::get('/create')->name('create');
                        Route::get('/{pageId}')->whereUuid('pageId')->name('edit');

                    });

                Route::get('/settings')->name('settings.index');

            });

        Route::middleware('front:guest')->group(function (): void {

            Route::get('/login')->name('login');
            Route::get('/register')->name('register');

        });

    });

Route::middleware(FrontRoutesMiddleware::class)->group(function (): void {

    Route::middleware('front:auth')->group(function (): void {

        Route::get('/email/verify')->name('verification.notice');

    });

    Route::get('/{permalink?}')
        ->where(['permalink' => '.+', '_permalink' => true])
        ->name('front.page.permalink');

});

Route::fallback(fn () => abort(400));
