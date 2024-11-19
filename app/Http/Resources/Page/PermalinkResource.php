<?php

declare(strict_types=1);

namespace App\Http\Resources\Page;

use Illuminate\Http\Request;

class PermalinkResource extends PageResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $page = parent::toArray($request);

        unset($page['id'], $page['user']);

        return $page;
    }
}
