<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_all_articles_can_be_loaded(): void
    {
        Article::factory(10)->create();

        $response = $this->get(route('articles.index'), []);
        $this->assertCount(10, $response->original['data']);
        $response->assertJsonStructure(['per_page', 'to', 'total', 'data']);
    }

    public function test_one_articles_can_be_loaded(): void
    {
        $article = Article::factory()->createOne();

        $response = $this->get(route('articles.show', [$article->id]), []);
        $response->assertJsonStructure(['id', 'title', 'content', 'author', 'comments', 'tags']);
        $response->assertOk();
    }

    public function test_articles_can_be_created_by_user(): void
    {
        $author = User::factory()
            ->set('password', '123foobar')
            ->createOne();

        $response = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => fake()->sentence(1),
                'description' => fake()->sentence(3),
                'quote' => fake()->sentence(10),
                'content' => fake()->paragraph(),
            ],
            ['Accept' => 'application/json']
        );

        $this->assertDatabaseHas('articles', ['id' => $response->original['id']]);

        $response->assertCreated();
    }

    public function test_articles_can_be_edited_by_user(): void
    {

        $title = fake()->sentence;
        $content = fake()->paragraph();
        $description = fake()->sentence(10);
        $content = fake()->paragraph();
        $quote = fake()->sentence(10);

        $author = User::factory()
            ->set('password', '123foobar')
            ->createOne();

        $article = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                // 'author_id' => $user->id,
                'title' => fake()->sentence(1),
                'description' => fake()->sentence(3),
                'quote' => fake()->sentence(10),
                'content' => fake()->paragraph(),
            ],
            ['Accept' => 'application/json']
        );

        $response = $this->actingAs($author)->patch(route('articles.update', [$article->original['id']]), [
            // 'author_id' => $user->id,
            'title' => $title,
            'content' => $content,
            'description' => $description,
            'quote' => $quote,
        ]);

        $this->assertDatabaseHas('articles', ['title' => $title]);
        $this->assertDatabaseHas('articles', ['content' => $content]);

        $response->assertOk();
    }

    public function test_articles_can_be_deleted_by_user(): void
    {
        $author = User::factory()
            ->set('password', '123foobar')
            ->createOne();

        $article = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                // 'author_id' => $user->id,
                'title' => fake()->sentence(1),
                'description' => fake()->sentence(3),
                'quote' => fake()->sentence(10),
                'content' => fake()->paragraph(),
            ],
            ['Accept' => 'application/json']
        );

        $response = $this->actingAs($author)->delete(route('articles.destroy', [$article->original['id']]), []);

        $this->assertDatabaseMissing('articles', ['id' => $article->original['id']]);
        $response->assertNoContent();
    }

    public function test_cant_create_articles_without_being_user(): void
    {
        $response = $this->postJson(
            route('articles.store'),
            [
                // 'author_id' => $user->id,
                'title' => fake()->sentence(),
                'content' => fake()->paragraph()
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    public function test_articles_should_not_be_deleted_by_any_user(): void
    {
        $author = User::factory()
            ->set('password', '123foobar')
            ->createOne();

        $article = Article::factory()->createOne();

        $response = $this->actingAs($author)->delete(route('articles.destroy', [$article->id]), []);
        $response->assertStatus(403);
    }

    public function test_articles_should_not_be_edited_by_any_user(): void
    {
        $author = User::factory()
            ->set('password', '123foobar')
            ->createOne();

        $article = Article::factory()->createOne();

        $response = $this->actingAs($author)->patch(route('articles.update', [$article->id]), [
            // 'author_id' => $user->id,
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
        ]);
        $response->assertStatus(403);
    }
}
