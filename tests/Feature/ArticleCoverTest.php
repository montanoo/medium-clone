<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

test('article_cover_can_be_stored_at_creating_user', function () {
    $user = User::factory()->createOne();
    $image = "data:image/png;base64," . base64_encode(file_get_contents(UploadedFile::fake()->image('cover.jpg')));
    $response = $this->actingAs($user)->postJson(route('articles.store'), [
        'title' => fake()->sentence(1),
        'description' => fake()->sentence(3),
        'quote' => fake()->sentence(10),
        'content' => fake()->paragraph(),
        'cover_photo' => $image,
    ]);

    $this->assertDatabaseHas('articles', ['cover_url' => $response->original['cover_url']]);
    // check if it has been saved!
    $article = Article::find($response->original['id']);
    Storage::disk('public')->assertExists(Str::after($article->cover_url, 'storage/'));
});

test('only_logged_in_users_can_add_cover_to_article', function () {
    // given
    $user = User::factory()->createOne();

    // post to users.store
    $response = $this->postJson(route('articles.store'), [
        'title' => fake()->sentence(1),
        'content' => fake()->paragraph(),
        'cover_photo' => UploadedFile::fake()->image('cover.jpg'),
    ]);

    // check for status (unauthenticated)
    $response->assertStatus(401);
});
