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
                'frontpage' => config('general.frontpage') === $this->id,
                'published' => $this->published,
                'permalink' => $this->permalink,
                'title' => $this->metadata->title,
                'description' => $this->metadata->description,
                'robots' => $this->metadata->robots,
                'sitemap_include' => $this->metadata->sitemap_include,
                'sitemap_prio' => $this->metadata->sitemap_prio,
                'sitemap_change_freq' => $this->metadata->sitemap_change_freq,
                'timestamps' => [
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
            ],
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
