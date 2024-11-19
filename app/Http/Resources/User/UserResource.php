<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Helpers\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'name_display' => $this->name_display,
            'name_display_plain' => $this->name_display,
            'name_first' => $this->name_first,
            'name_second' => $this->name_second,
            'name_last' => $this->name_last,
            'phone' => $this->phone,
            'phone_prefix' => $this->phone_prefix,
            'meta' => [
                'locale' => $this->locale,
                'timestamps' => [
                    'email_verified_at' => $this->email_verified_at,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
            ],
        ];

        Users::formatNameDisplay($user);
        Users::formatPhoneNumber($user);

        return $user;
    }
}
