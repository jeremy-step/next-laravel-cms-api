<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $findOrNotFound = function (mixed $id, array $columns = ['*'], ?string $resource = null): Model {
            /** @var BaseBuilder|EloquentBuilder|BelongsToMany|HasManyThrough $this */
            $resource ??= class_basename($this->getModel());

            return $this->findOr($id, $columns, fn () => abort(404, "$resource not found"));
        };

        EloquentBuilder::macro('findOrNotFound', $findOrNotFound);
        BaseBuilder::macro('findOrNotFound', $findOrNotFound);
        BelongsToMany::macro('findOrNotFound', $findOrNotFound);
        HasManyThrough::macro('findOrNotFound', $findOrNotFound);
    }
}
