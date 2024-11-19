<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hourAgo = time() - Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
        $lastActivity =
            $this->last_activity < $hourAgo ?
            $this->last_activity :
            Carbon::createFromTimestamp($this->last_activity)->diffForHumans(options: Carbon::JUST_NOW);

        return [
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at,
            'last_activity' => $lastActivity,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
