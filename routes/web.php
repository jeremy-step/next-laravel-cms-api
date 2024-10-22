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
            ->group(function (): void {

                Route::get('/dashboard')->name('dashboard.index');

                Route::name('pages.')
                    ->prefix('/pages')
                    ->group(function (): void {

                        Route::get('/pages')->name('index');
                        Route::get('/pages/create')->name('create');
                        Route::get('/pages/{pageId}')->whereUuid('pageId')->name('edit');

                    });

                Route::get('/settings')->name('settings.index');

            });

        Route::get('/login')->name('login');
        Route::get('/register')->name('register');

        Route::get('/{permalink?}')->where(['permalink' => '.+'])->name('page.permalink');

    });

Route::middleware(FrontRoutesMiddleware::class)->group(function (): void {

    Route::get('/email/verify')->name('verification.notice');

});

Route::fallback(fn () => abort(400));
