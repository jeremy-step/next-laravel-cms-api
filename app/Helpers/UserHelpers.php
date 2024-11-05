<?php

declare(strict_types=1);

namespace App\Helpers;

final class UserHelpers
{
    public static function formatNameDisplay(array &$user): void
    {
        if ($user['nameDisplay']) {
            $user['nameDisplay'] = trim(str_replace(
                ['<nameFirst>', '<nameSecond>', '<nameLast>', '<username>'],
                [$user['nameFirst'], $user['nameSecond'], $user['nameLast'], $user['username']],
                $user['nameDisplay']
            ));
        }

        $user['nameDisplay'] ??= $user['username'];
    }

    public static function formatPhoneNumber(array &$user): void
    {
        $user['phoneNumber'] = null;

        if ($user['phonePrefix'] && $user['phone']) {
            $user['phoneNumber'] = "+{$user['phonePrefix']} {$user['phone']}";
        }
    }
}
