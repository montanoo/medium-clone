<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\User;
use Tests\TestCase;

class ArticlePremiumTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_premium_articles_filter_index(): void
    {
        // given
        Article::factory()->count(3)->create(['is_premium' => true]);
        Article::factory()->count(2)->create(['is_premium' => false]);

        // endpoint
        $response = $this->getJson('/api/articles?is_premium=true');

        // then
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.is_premium', true);

        // assert that all articles in the response are premium
        $articles = $response->json('data');
        foreach ($articles as $article) {
            $this->assertTrue($article['is_premium']);
        }
    }

    public function test_non_premium_article_show_to_all_users(): void
    {
        // given
        $article = Article::factory()->createOne(['is_premium' => false]);
        $user = User::factory()->create();

        // do the action as an unauthenticated user
        $responseUnauthenticated = $this->get(
            route(
                'articles.show',
                ['article' => $article->id]
            )
        );

        // Parse the JSON response content to access the data
        $responseUnauthContent = $responseUnauthenticated->json();

        // check that the response content contains the expected data structure for a single article
        $this->assertArrayHasKey('title', $responseUnauthContent);
        $this->assertArrayHasKey('content', $responseUnauthContent);
        $this->assertArrayHasKey('description', $responseUnauthContent);
        $this->assertArrayHasKey('quote', $responseUnauthContent);
        $this->assertArrayHasKey('is_premium', $responseUnauthContent);

        // do the action as an authenticated user
        $responseAuthenticated = $this->actingAs($user)->get(
            route(
                'articles.show',
                ['article' => $article->id]
            )
        );

        // Parse the JSON response content to access the data
        $responseAuthContent = $responseAuthenticated->json();

        // check that the response content contains the expected data structure for a single article
        $this->assertArrayHasKey('title', $responseAuthContent);
        $this->assertArrayHasKey('content', $responseAuthContent);
        $this->assertArrayHasKey('description', $responseAuthContent);
        $this->assertArrayHasKey('quote', $responseAuthContent);
        $this->assertArrayHasKey('is_premium', $responseAuthContent);
    }

    public function test_premium_articles_show_only_to_logged_premium_users(): void
    {
        // using faker to generate large paragraphs of text in content
        $faker = FakerFactory::create();

        // given
        $article = Article::factory()->createOne([
            'is_premium' => true,
            'content' => $faker->paragraphs(5, true) .
            ' Extra content to ensure length is more than 100 characters.',
        ]);

        $user = User::factory()->create(['is_premium' => true]);

        // do the action as an unauthenticated user
        $responseUnauthenticated = $this->get(
            route(
                'articles.show',
                ['article' => $article->id]
            )
        );

        // Parse the JSON response content to access the data
        $responseUnauthContent = $responseUnauthenticated->json();

        // check that article content is limited to 100 characters
        $this->assertLessThanOrEqual(100, Str::length($responseUnauthContent['content']));

        // do the action as an authenticated user
        $responseAuthenticated = $this->actingAs($user)->get(
            route(
                'articles.show',
                ['article' => $article->id]
            )
        );

        // Parse the JSON response content to access the data
        $responseAuthContent = $responseAuthenticated->json();

        // check that article content is not limited to 100 characters
        $this->assertGreaterThan(100, Str::length($responseAuthContent['content']));
    }
}