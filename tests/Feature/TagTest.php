<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_all_tags_can_be_loaded(): void
    {
        Tag::factory(10)->create();

        $response = $this->get(route('tags.index'), []);
        $this->assertCount(5, $response->original['data']);
        $response->assertJsonStructure(['per_page', 'to', 'total', 'data']);
        $response->assertOk();
    }

    public function test_one_tag_can_be_loaded(): void
    {
        $tag = Tag::factory()->createOne();

        $response = $this->get(route('tags.show', [$tag->id]), []);
        $response->assertJsonStructure(['id', 'tag', 'articles']);
        $response->assertOk();
    }

    public function test_tags_can_be_created_by_user(): void
    {
        $author = User::factory()->createOne();

        $tag = fake()->sentence();

        $response = $this->actingAs($author)->post(route('tags.store'), [
            'tag' => $tag,
        ]);

        $this->assertDatabaseHas('tags', ['tag' => $tag]);

        $tags = Tag::all();
        $this->assertCount(1, $tags);
        $response->assertCreated();
    }

    public function test_tags_can_be_edited_by_user(): void
    {
        $author = User::factory()->createOne();
        $original = fake()->sentence();
        $sentence = fake()->sentence(1);

        $tag = $this->actingAs($author)->post(route('tags.store'), [
            'tag' => $original,
        ]);

        $response = $this->actingAs($author)->patch(route('tags.update', [$tag->original['id']]), [
            'tag' => $sentence,
        ]);

        $this->assertDatabaseHas('tags', ['tag' => $sentence]);

        $response->assertNoContent();
    }

    public function test_tags_can_be_deleted_by_user(): void
    {
        $author = User::factory()->createOne();

        $tag = $this->actingAs($author)->post(route('tags.store'), [
            'tag' => fake()->sentence(),
        ]);
        $this->assertDatabaseHas('tags', ['id' => $tag->original['id']]);
        $response = $this->actingAs($author)->delete(route('tags.destroy', [$tag->original['id']]), []);

        $this->assertDatabaseMissing('tags', ['id' => $tag->original['id']]);
        $response->assertNoContent();
    }

    public function test_cant_create_tags_without_being_user(): void
    {
        $response = $this->post(route('tags.store'), [
            'tag' => fake()->sentence(),
        ], ['Accept' => 'application/json']);

        $response->assertStatus(401);
        $tags = Tag::all();
        $this->assertCount(0, $tags);
    }
}
