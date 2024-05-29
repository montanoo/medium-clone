<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StoreCommentTest extends TestCase
{
    // use RefreshDatabase;
    // deletes the database.
    /**
     * A basic feature test example.
     */
    public function test_comment_can_be_loaded(): void
    {
        // given an author and a article
        $comments = Comment::factory(10)->create();

        // do the action
        $response = $this->get(route('comments.index'), []);

        $response->assertOk();

        // verify that data containts 10 articles!
        $this->assertCount(10, $response->original['data']);

        // assert that the pagination structure works!
        $response->assertJsonStructure(['per_page', 'to', 'total', 'data']);
    }

    public function test_comment_can_load_one(): void
    {
        // given an author and a article
        $comments = Comment::factory()->createOne();

        // do the action
        $response = $this->get(route('comments.show', ['comment' => $comments->id]), []);
        $response->assertJsonStructure(['id', 'content', 'article', 'article_id']);
        $response->assertOk();
    }
    public function test_comment_can_be_stored(): void
    {
        // given an author and a article
        // User::factory()->set('email', 'test@example.com')->set('password', bcrypt('password'))->create();
        $author =  User::factory()->createOne();

        $article = Article::factory()->createOne();
        // $token = $this->post(route('api.authenticate'), [
        //     'email' => 'test@example.com',
        //     'password' => 'password'
        // ]);

        // do the action
        $response = $this->actingAs($author)->post(route('comments.store'), [
            'article_id' => $article->id,
            'content' => fake()->sentence,
        ] /* ['Authorization' => 'Bearer ' . $token->original['token']] */);

        // check that
        $response->assertCreated();

        $comments = Comment::all();
        $this->assertCount(1, $comments);
    }

    public function test_comment_can_be_edited_by_user(): void
    {
        $author =  User::factory()->createOne();
        $article = Article::factory()->createOne();

        $comment = $this->actingAs($author)->post(route('comments.store'), [
            'article_id' => $article->id,
            'content' => fake()->sentence,
        ] /* ['Authorization' => 'Bearer ' . $token->original['token']] */);

        $sentence = fake()->sentence(1);
        $edit = $this->actingAs($author)->patch(route('comments.update', [$comment->original['id']]), [
            'content' => $sentence,
        ] /* ['Authorization' => 'Bearer ' . $token->original['token']]*/);

        // check that
        $edit->assertNoContent();
        $this->assertDatabaseHas('comments', ['content' => $sentence]);
        $comments = Comment::all();
        $this->assertCount(1, $comments);
    }

    public function test_comments_can_be_deleted_by_user(): void
    {
        $author =  User::factory()->createOne();
        $article = Article::factory()->createOne();

        $comment = $this->actingAs($author)->post(route('comments.store'), [
            'article_id' => $article->id,
            'content' => fake()->sentence,
        ] /* ['Authorization' => 'Bearer ' . $token->original['token']] */);

        $this->actingAs($author)->delete(route('comments.destroy', [$comment->original['id']]), []);

        // check that
        $comments = Comment::all();

        $this->assertEmpty($comments);
    }

    public function test_comments_on_create_without_user_show_error_unauthenticated(): void
    {
        $article = Article::factory()->createOne();

        $response = $this->post(route('comments.store'), [
            'article_id' => $article->id,
            'content' => fake()->sentence,
        ], ['Accept' => 'application/json'] /* ['Authorization' => 'Bearer ' . $token->original['token']] */);


        // check that
        $response->assertStatus(401);
    }

    public function test_comment_should_not_be_edited_by_any_user(): void
    {
        // given
        $author =  User::factory()->createOne();
        $comment = Comment::factory()->createOne(); // this works because this factory also creates a user!

        // action
        $edit = $this->actingAs($author)->patch(route('comments.update', [$comment->id]), [
            'content' => fake()->sentence(1),
        ], ['Accept' => 'application/json']);

        // check that
        $edit->assertStatus(403);

        $comments = Comment::all();
        $this->assertCount(1, $comments);
    }

    public function test_comment_should_not_be_deleted_by_any_user(): void
    {
        // given
        $author =  User::factory()->createOne();
        $comment = Comment::factory()->createOne(); // this works because this factory also creates a user!

        // action
        $edit = $this->actingAs($author)->patch(route('comments.destroy', [$comment->id]), [], ['Accept' => 'application/json']);

        // check that
        $edit->assertStatus(403);

        $comments = Comment::all();
        $this->assertCount(1, $comments);
    }
}
