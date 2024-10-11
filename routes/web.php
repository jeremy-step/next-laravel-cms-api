<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/reset-password', function () {
    return "Reset Password";
})->name('password.reset');

Route::get('/{foo?}', fn() => abort(400))->where(['foo' => '.*']);
