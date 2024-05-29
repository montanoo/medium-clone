<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, options: ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query()->with(['articles', 'author'])
            ->when(
                request('author_id'),
                function ($query, $authorId) {
                    $query->where('author_id', '=', $authorId);
                }
            )
            ->when(
                request('author_ids'),
                function ($query, string $authorIds) {
                    $query->whereIn('author_id', explode(',', $authorIds));
                }
            )
            ->paginate(5);
        return $tags;
    }

    public function premiumTags()
    {
        return Tag::query()->with(['articles', 'author'])
            ->whereHas('articles', function ($query) {
                $query->where('is_premium', '=', true);
            })
            ->when(
                request('author_id'),
                function ($query, $authorId) {
                    $query->where('author_id', '=', $authorId);
                }
            )
            ->when(
                request('author_ids'),
                function ($query, string $authorIds) {
                    $query->whereIn('author_id', explode(',', $authorIds));
                }
            )
            ->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $data = $request->validate([
            'tag' => ['required', 'string'],
        ]);

        $tag = new Tag($data);
        $tag->author_id = auth()->user()->id;

        $tag->save();

        return $tag;
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
        $tag->load(['articles', 'author']);
        return $tag;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
        $tag->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
        $tag->delete();
        return response()->noContent();
    }

    public function byTag(Request $request)
    {
        $tags = Tag::query()->where('tag', 'like', '%' . $request->search . '%')->get();

        return ['tags' => $tags];
    }
}