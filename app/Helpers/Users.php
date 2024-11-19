<?php

declare(strict_types=1);

namespace App\Helpers;

final class Users
{
    public static function formatNameDisplay(array &$user): void
    {
        $user['name_display'] = trim(str_replace(
            ['<first_name>', '<middle_name>', '<surname>', '<username>'],
            [$user['name_first'], $user['name_second'], $user['name_last'], $user['username']],
            $user['name_display']
        ));

        $user['name_display'] ??= $user['username'];
    }

    public static function formatPhoneNumber(array &$user): void
    {
        $user['phone_number'] = null;
        $user['phone_number_plain'] = null;

        if ($user['phone_prefix'] !== null && $user['phone'] !== null) {
            $user['phone_number'] = "+{$user['phone_prefix']} {$user['phone']}";
            $user['phone_number_plain'] = '+'.preg_replace('/[^\d]/', '', $user['phone_prefix'].$user['phone']);
        }
    }
}
