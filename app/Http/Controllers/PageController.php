<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Pages;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageMetadataRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\Page\PageCollection;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Page\PermalinkResource;
use App\Models\Page;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    public function getByPermalink(Page $page): PermalinkResource
    {
        $frontpage = config('general.frontpage');

        if (! $page->exists) {
            $page = Page::findOrFail($frontpage);
        }

        return new PermalinkResource($page);
    }

    /**
     * Get the sitemap.
     */
    public function sitemap(): PageCollection
    {
        $pages = Page::wherePublished(true)->where(function (Builder $query): void {
            $query->select('sitemap_include')->from('page_metadata')->whereColumn('page_metadata.page_id', 'pages.id');
        }, true)->with('metadata')->get();

        return new PageCollection($pages);
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(StorePageRequest $request): PageResource
    {
        $data = $request->all(['title', 'text', 'published']);

        $data['permalink'] = Pages::formatPermalink($data['title']);

        if (Page::wherePermalink($data['permalink'])->first(['permalink']) !== null) {
            $data['permalink'] .= '-'.str(Str::random(8))->lower();
        }

        $page = $request->user()?->pages()->create($data);

        $page->metadata()->create(['title' => $data['title'], 'description' => '']);

        return new PageResource($page);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(UpdatePageRequest $request, Page $page): array
    {
        $data = $request->all(['title', 'text', 'published']);

        $page->title = $data['title'];
        $page->text = $data['text'];
        $page->published = $data['published'];

        return $page->save() ? ['message' => 'Changes saved successfully'] : ['message' => 'Changes could not be saved'];
    }

    /**
     * Update the specified page metadata in storage.
     */
    public function updateMetadata(UpdatePageMetadataRequest $request, Page $page): array
    {
        $data = $request->all(['permalink', 'title', 'description', 'robots', 'sitemap_include', 'sitemap_prio', 'sitemap_change_freq']);

        $page->metadata->title = $data['title'];
        $page->metadata->description = $data['description'];
        $page->metadata->robots = $data['robots'];
        $page->metadata->sitemap_include = $data['sitemap_include'];
        $page->metadata->sitemap_prio = $data['sitemap_prio'];
        $page->metadata->sitemap_change_freq = $data['sitemap_change_freq'];

        $permalink = Pages::formatPermalink($data['permalink']);

        if ($permalink === '') {
            $permalink = Pages::formatPermalink($page->title);
        }

        if (Page::wherePermalink($permalink)->whereNot('id', $page->id)->first(['permalink']) !== null) {
            $permalink .= '-'.str(Str::random(8))->lower();
        }

        $page->permalink = $permalink;

        return $page->push() ? ['message' => 'Changes saved successfully'] : ['message' => 'Changes could not be saved'];
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): array
    {
        return $page->delete() ? ['message' => 'Page deleted successfully'] : ['message' => 'Page could not be deleted'];
    }
}
