<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Settings;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\Page\PageCollection;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Page\PermalinkResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PageController extends Controller
{
    protected $fields = ['id', 'permalink', 'title', 'text', 'created_at', 'updated_at'];

    /**
     * Display a pages listing.
     */
    public function index(Request $request): PageCollection
    {
        $pages = QueryBuilder::for(Page::class)
            ->allowedFields($this->fields)
            ->allowedFilters($this->fields)
            ->allowedSorts($this->fields)
            ->allowedIncludes(['user'])
            ->defaultSort(['-created_at']);

        $pages = $request->has('all') ? $pages->get() : $pages->jsonPaginate()->appends(request()->query());

        return new PageCollection($pages);
    }

    /**
     * Get the specified page.
     */
    public function get(string $id): PageResource
    {
        $page = QueryBuilder::for(Page::class)
            ->allowedFields($this->fields)
            ->allowedIncludes(['user']);

        return new PageResource($page->findOrNotFound($id));
    }

    /**
     * Get the specified page by permalink.
     */
    public function getByPermalink(Page $page): array|PermalinkResource
    {
        $frontpage = Settings::get('frontpage');

        if ($page->exists && $page->id === $frontpage) {
            return ['redirect' => ['route' => 'front.page.permalink']];
        }

        if (! $page->exists) {
            $page = Page::findOrFail($frontpage);
        }

        return new PermalinkResource($page);
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(StorePageRequest $request): PageResource
    {
        $data = $request->all(['title', 'text']);

        $data['permalink'] = str($data['title'])->slug();

        $page = $request->user()?->pages()->create($data);

        return new PageResource($page);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(UpdatePageRequest $request, Page $page): array
    {
        $data = $request->all(['title', 'text']);

        $page->title = $data['title'];
        $page->text = $data['text'];

        return $page->save() ? ['message' => 'Changes saved successfully'] : ['message' => 'Changes could not be saved'];
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): array
    {
        return $page->delete() ? ['message' => 'Page deleted successfully'] : ['message' => 'Page could not be deleted'];
    }
}
