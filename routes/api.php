<?php

declare(strict_types=1);

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::middleware('auth:sanctum')->group(function (): void {

        Route::get('/user', function (Request $request): array {

            $user = $request->user();

            $userData = $user ? [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'emailVerifiedAt' => $user->email_verified_at,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at,
                'nameDisplay' => $user->name_display,
                'nameFirst' => $user->name_first,
                'nameSecond' => $user->name_second,
                'nameLast' => $user->name_last,
                'phone' => $user->phone,
                'phonePrefix' => $user->phone_prefix,
                'locale' => $user->locale,
            ] : null;

            if ($userData) {
                UserHelper::formatNameDisplay($userData);
                UserHelper::formatPhoneNumber($userData);
            }

            return ['data' => $userData];

        })->name('user');

        Route::get('/authenticated', function (Request $request): array {

            return ['data' => ['authenticated' => (bool) $request->user()]];

        })->name('authenticated');

        Route::get('/test', fn () => response()->json('test'))->name('test');

        Route::post('/test', fn () => '1')->name('ptest');

    });
});
