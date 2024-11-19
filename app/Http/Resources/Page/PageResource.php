<?php

declare(strict_types=1);

namespace App\Http\Resources\Page;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'meta' => [
                'permalink' => $this->permalink,
                'timestamps' => [
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
            ],
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
