<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->set('email', 'test@example.com')->set('password', bcrypt('password'))->create();

        Article::factory(10)->create();
        Comment::factory(10)->create();

        Tag::factory(10)->create();
        ArticleTag::factory(10)->create();
        Article::All()->each(function ($article) {
            $article->tags()->attach(Tag::all()->random(rand(1, 2)));
        });
    }
}
