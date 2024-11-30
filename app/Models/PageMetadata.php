<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageMetadata extends Model
{
    use HasUuids;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'page_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'robots',
        'sitemap_include',
        'sitemap_prio',
        'sitemap_change_freq',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sitemap_include' => 'boolean',
            'sitemap_prio' => 'decimal:1',
        ];
    }

    /**
     * Get the page that owns the metadata.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
