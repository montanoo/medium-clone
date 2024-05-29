<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, options: ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Comment::query()
            ->with(['author', 'article'])
            // -> cursorPaginate(2) !cannot pass to artibrary page. (better performance.)
            ->when(
                request('author_id'),
                function ($query, $authorId) {
                    $query->where('author_id', '=', $authorId);
                }
            )
            ->when(
                request('author_ids'),
                function ($query, string $authorIds) {
                    $query->whereIn(
                        'author_id',
                        explode(',', $authorIds)
                    );
                }
            )
            ->when(
                request('article_id'),
                function ($query, $articleId) {
                    $query->where('article_id', '=', $articleId);
                }
            )
            ->when(
                request('article_ids'),
                function ($query, string $articleIds) {
                    $query->whereIn(
                        'article_ids',
                        explode(',', $articleIds)
                    );
                }
            )
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = new Comment($request->validated());
        $comment->author_id = auth()->user()->id;
        $comment->save();

        $comment->load(['author']);

        return $comment;
    }

    /**
     * Display the specified resource.
     */
    // laravel implicit binding: https://laravel.com/docs/10.x/routing#implicit-binding
    public function show(Comment $comment)
    {
        $comment->load(['article']);
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
