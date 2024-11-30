<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Settings;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\Setting\SettingCollection;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): SettingCollection
    {
        Gate::authorize('viewAny', Setting::class);

        return new SettingCollection(Settings::get());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request)
    {
        $settings = $request->input('settings');

        return Settings::set($settings) ? ['message' => 'Changes saved successfully'] : ['message' => 'Changes could not be saved'];
    }
}
