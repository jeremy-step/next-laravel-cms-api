<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
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
            ->allowedFilters($this->fields)
            ->allowedFields($this->fields)
            ->allowedSorts($this->fields);

        $pages = $request->has('all') ? $pages->get() : $pages->jsonPaginate()->appends(request()->query());

        return new PageCollection($pages);
    }

    /**
     * Get the specified page.
     */
    public function get(string $id): PageResource
    {
        $page = QueryBuilder::for(Page::class)
            ->allowedFields($this->fields);

        return new PageResource($page->findOrNotFound($id));
    }

    /**
     * Get the specified page by permalink.
     */
    public function getByPermalink(Request $request): PageResource
    {
        $page = Page::wherePermalink($request->get('permalink') ?? '')->first();

        return $page ? new PageResource($page) : abort(404, 'Page not found');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(StorePageRequest $request): PageResource
    {
        $data = $request->all(['title', 'text']);

        $data['permalink'] = str($data['title'])->slug();

        $page = Page::create($data);

        return new PageResource(['id' => $page->id]);
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
