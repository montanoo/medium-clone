<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DebugInfoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_debug_info_appears_on_get_articles_request(): void
    {
        $request = $this->get(route('articles.index'));

        $request->assertJsonStructure(['debug-info']);
    }

    public function test_debug_info_appears_on_get_comments_request(): void
    {
        $request = $this->get(route('comments.index'));

        $request->assertJsonStructure(['debug-info']);
    }

    public function test_debug_info_appears_on_get_tags_request(): void
    {
        $request = $this->get(route('tags.index'));

        $request->assertJsonStructure(['debug-info']);
    }

    public function test_debug_info_appears_on_post_article_request(): void
    {
        $user = User::factory()->createOne();

        $request = $this->actingAs($user)->post(route('articles.store'), [
            // 'author_id' => $user->id,
            'title' => fake()->sentence(1),
            'description' => fake()->sentence(3),
            'quote' => fake()->sentence(10),
            'content' => fake()->paragraph(),
        ]);

        $request->assertJsonStructure(['debug-info']);
    }

    public function test_debug_info_appears_on_post_tags_request(): void
    {
        $author = User::factory()->createOne();

        $request = $this->actingAs($author)->post(route('tags.store'), [
            'tag' => fake()->sentence(),
        ]);

        $request->assertJsonStructure(['debug-info']);
    }

    public function test_debug_info_appears_on_post_comments_request(): void
    {
        $user =  User::factory()->createOne();

        $article = $this->actingAs($user)->post(route('articles.store'), [
            // 'author_id' => $user->id,
            'title' => fake()->sentence(1),
            'description' => fake()->sentence(3),
            'quote' => fake()->sentence(10),
            'content' => fake()->paragraph(),
        ]);

        $request = $this->post(route('comments.store'), [
            'article_id' => $article->original['id'],
            // 'author_id' => $user->id,
            'content' => fake()->sentence,
        ]);

        $request->assertJsonStructure(['debug-info']);
    }
}
