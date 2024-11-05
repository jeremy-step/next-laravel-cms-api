<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\UserHelpers;
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
            'emailVerifiedAt' => $this->email_verified_at,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'nameDisplay' => $this->name_display,
            'nameFirst' => $this->name_first,
            'nameSecond' => $this->name_second,
            'nameLast' => $this->name_last,
            'phone' => $this->phone,
            'phonePrefix' => $this->phone_prefix,
            'locale' => $this->locale,
        ];

        if ($user) {
            UserHelpers::formatNameDisplay($user);
            UserHelpers::formatPhoneNumber($user);
        }

        return $user;
    }
}
