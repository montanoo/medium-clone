<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use App\Services\NotificationService\ModeratorNotifier;
use App\Services\NotificationService\TwitterNotifier;
use App\Services\NotificationService\UserNotifier;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, options: ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // we signal query to meantion that we go to the db to fetch something
        $articles = Article::query()->with(['comments.author', 'author', 'tags'])
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
            ->when(
                request()->input('tag_ids'),
                static::filterByRelationshipIds('tags', 'tags.id'),
            )
            ->when(
                request('is_premium') !== null,
                function ($query, $isPremium) {
                    $query->where('is_premium', '=', filter_var($isPremium, FILTER_VALIDATE_BOOLEAN));
                }
            )
            ->paginate();
        // FIRST, we loaded all articles and then all the comments. SLOW. $articles->load(['comments']);
        return $articles;
    }

    public function premiumArticles()
    {
        return Article::query()->with(['comments.author', 'author', 'tags'])
            ->where('is_premium', '=', true)
            ->when(
                request()->input('tag_ids'),
                static::filterByRelationshipIds('tags', 'tags.id'),
            )
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
            'description' => ['required', 'string'],
            'quote' => ['required', 'string'],
            'title' => ['required', 'string'],
            'is_premium' => ['boolean'],
        ]);

        $article = new Article($data);

        if ($request->input('cover_photo')) {
            $image = $request->input('cover_photo'); // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $filename = 'article_' . uniqid() . '.png';

            $coverPath = 'covers/' . $filename;
            Storage::disk('public')->put($coverPath, base64_decode($image));
            $article->cover_url = Storage::url($coverPath);
        }

        $article->author_id = auth()->user()->id;
        $article->save();

        if ($tagContent = request()->input('tags')) {
            $this->attachTags($article, $tagContent);
        }

        // if ($coverPhoto = request()->file('cover_photo')) {
        //     $coverPath = Storage::disk('public')->put('covers', $coverPhoto);
        //     // three ways to accomplish the same thing, yay
        //     //$coverPath = Storage::disk('public')->put('covers', $coverPhoto);
        //     //$coverPath = $coverPhoto->storePublicly('covers', ['disk' => 'public']);
        //     //$coverPath = $coverPhoto->storePublicly('public/covers');
        //     $article->cover_url = Storage::url($coverPath);
        //     $article->save();
        // }

        foreach ($this->NotificationChannels() as $channel) {
            $channel->notifyAbout($article);
        }

        return $article;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $article = Article::where('id', $id)->with(['author', 'comments.author', 'tags'])->firstOrFail();

        // check if the article is premium
        if ($article->is_premium) {
            $user = auth('sanctum')->user();

            // if the user is not logged in or is not a premium user, limit the content
            if (!$user || $user->is_premium === false) {
                $article->content = Str::limit($article->content, 100, '');
            }
        }

        return $article;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $article->update($request->validate([
            'content' => ['required', 'string'],
            'description' => ['required', 'string'],
            'title' => ['required', 'string'],
            'quote' => ['required', 'string'],
            'is_premium' => ['boolean'],
        ]));

        if ($request->input('cover_photo')) {
            $image = $request->input('cover_photo'); // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $filename = 'article_' . uniqid() . '.png';

            $coverPath = 'covers/' . $filename;
            Storage::disk('public')->put($coverPath, base64_decode($image));
            $article->cover_url = Storage::url($coverPath);
        }

        if ($tagContent = request()->input('tags')) {
            DB::transaction(function () use ($article, $tagContent) {
                $article->tags()->detach();
                $this->attachTags($article, $tagContent);
            });
        }

        $article->save();

        return $article;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // $article = Article::where('id', $id)->firstOrFail();

        $article->delete();

        return response()->noContent();
    }

    private function attachTags(Article $article, array $tagContent)
    {
        // create the tags if they don't exist already
        $tagUpsertData = collect($tagContent)->map(fn ($content) => ['tag' => $content, 'author_id' => request()->user()->id])->all();
        Tag::upsert($tagUpsertData, ['tag']);

        // fetch the tags so that they may be attached
        $tags = Tag::query()->whereIn('tag', $tagContent)->get('id');
        $article->tags()->attach($tags);
    }

    private function NotificationChannels(): array
    {
        return [
            new TwitterNotifier,
            new ModeratorNotifier,
            new UserNotifier
        ];
    }

    private static function filterByRelationshipIds(string $relationship, string $idColumn)
    {
        return function (Builder $articleQuery, $ids) use ($relationship, $idColumn) {
            if (!is_array($ids)) {
                // support queries both like foo[]=1&foo[]=2 and foo=[1,2]
                $parsedIds = json_decode($ids);

                if (is_null($parsedIds)) {
                    // also try to support foo=1,2
                    $ids = explode(',', $ids);
                } else {
                    $ids = $parsedIds;
                }
            }

            $articleQuery
                ->whereHas(
                    $relationship,
                    fn (Builder $relationshipQuery) => $relationshipQuery
                        ->whereIn(
                            $idColumn,
                            $ids
                        )
                );
        };
    }
}
