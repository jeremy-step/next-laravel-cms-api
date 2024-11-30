<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class Settings
{
    private static Collection $settings;

    public static function set(string|array $setting, $value = null): bool
    {
        if (is_array($setting)) {
            $settings = $setting;

            DB::transaction(function () use ($settings): void {
                foreach ($settings as $key => $value) {
                    $setting = Setting::firstOrNew(
                        ['key' => $key]
                    );

                    $setting->value = $value;
                    $setting->saveOrFail();
                }
            });

            config($settings);

            return true;
        }

        $key = $setting;

        $setting = Setting::firstOrNew(
            ['key' => $key]
        );

        $setting->value = $value;
        $setting->saveOrFail();

        config([$key => $value]);

        return true;
    }

    public static function load(): void
    {
        $settings = Setting::all();

        $settings->each(function (Setting $setting): void {
            config([$setting->key => $setting->value]);

            if (! isset(static::$settings)) {
                static::$settings = collect();
            }

            static::$settings->push($setting->key);
        });
    }

    /**
     * @return ($setting is null ? \Illuminate\Support\Collection<TKey, TValue> : mixed)
     */
    public static function get(?string $setting = null, $defaultValue = null): mixed
    {
        if ($setting === null) {
            $settings = collect();

            self::$settings->each(function (string $setting) use ($settings): void {
                $settings->push(collect([$setting => config($setting)]));
            });

            return $settings;
        }

        return config($setting, $defaultValue);
    }
}
