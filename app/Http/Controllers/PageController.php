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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): PageCollection
    {
        $fields = ['id', 'title', 'text', 'created_at', 'updated_at'];

        $pages = QueryBuilder::for(Page::class)
            ->allowedFilters($fields)
            ->allowedFields($fields)
            ->allowedSorts($fields);

        $pages = $request->has('all') ? $pages->get() : $pages->jsonPaginate()->appends(request()->query());

        return new PageCollection($pages);
    }

    /**
     * Get the specified resource.
     */
    public function get(string $id): PageResource
    {
        $fields = ['id', 'title', 'text', 'created_at', 'updated_at'];

        $page = QueryBuilder::for(Page::class)
            ->allowedFields($fields);

        return new PageResource($page->findOrNotFound($id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request): PageResource
    {
        $data = $request->all(['title', 'text']);
        $page = Page::create($data);

        return new PageResource(['id' => $page->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page): array
    {
        $data = $request->all(['title', 'text']);

        $page->title = $data['title'];
        $page->text = $data['text'];

        return $page->save() ? ['message' => 'Changes saved successfully'] : ['message' => 'Changes could not be saved'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page): array
    {
        return $page->delete() ? ['message' => 'Page deleted successfully'] : ['message' => 'Page could not be deleted'];
    }
}
