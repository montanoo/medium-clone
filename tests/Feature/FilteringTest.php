<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Testing\Fluent\AssertableJson;

test('can filter comments according to article_id', function () {
    // given
    $article1 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $article2 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 4);

    // when
    $response = $this->getJson(
        route(
            'comments.index',
            ['article_id' => $article1->id]
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});

test('nested article comments route returns correct data', function () {
    // given
    $article1 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $article2 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 4);

    // when
    $response = $this->getJson(
        route(
            'articles.index'
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(
        fn (AssertableJson $json) =>
        $json
            ->has('data', 2)
            ->has(
                'data.0',
                fn (AssertableJson $data) =>
                $data
                    ->where('author_id', $article1->author_id)
                    ->etc()
            )
            ->etc()
    );
});

test('filtering articles by author_id returns correct data', function () {
    // given the data
    $articles = Article::factory(10)
        ->has(Comment::factory(2), 'comments')
        ->create();

    // kinda useless test (i just created 10)
    $this->assertDatabaseCount('articles', 10);

    // if I make a get request with an author id as filter
    $response = $this->getJson(
        route('articles.index', ['author_id' => $articles[0]->author_id])
    );

    // assert
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 1)
            ->etc();
    });

    // if I make another get request with two authors
    $response2 = $this->getJson(
        route('articles.index', ['author_ids' => $articles[0]->author_id . ',' . $articles[1]->author_id])
    );

    // assert that i have two responses
    $response2->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});

test('filtering comments by author_id returns correct data', function () {
    // given the data
    $comments = Comment::factory(10)->create();

    // kinda useless test (i just created 10)
    $this->assertDatabaseCount('comments', 10);

    // if I make a get request with an author id as filter
    $response = $this->getJson(
        route('comments.index', ['author_id' => $comments[0]->author_id])
    );

    // assert
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 1)
            ->etc();
    });

    // if I make another get request with two authors
    $response = $this->getJson(
        route('comments.index', ['author_ids' => $comments[0]->author_id . ',' . $comments[1]->author_id])
    );

    // assert that i have two responses
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});

test('filtering tags by author_id returns correct data', function () {
    // given the data
    $tags = Tag::factory(10)->create();

    // kinda useless test (i just created 10)
    $this->assertDatabaseCount('tags', 10);

    // if I make a get request with an author id as filter
    $response = $this->getJson(
        route('tags.index', ['author_id' => $tags[0]->author_id])
    );

    // assert
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 1)
            ->etc();
    });

    // if I make another get request with two authors
    $response = $this->getJson(
        route('tags.index', ['author_ids' => $tags[0]->author_id . ',' . $tags[1]->author_id])
    );

    // assert that i have two responses
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});
