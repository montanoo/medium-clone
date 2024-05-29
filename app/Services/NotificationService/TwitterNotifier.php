<?php

namespace App\Services\NotificationService;

use App\Models\Article;

class TwitterNotifier implements NotificationService
{
    public function notifyAbout(Article $article): string
    {
        return "Check out this cool new article! $article->title";
    }
}
