<?php

namespace App\Services\NotificationService;

use App\Models\Article;

class UserNotifier implements NotificationService
{
    public function notifyAbout(Article $article): string
    {
        return "You might like this new article: $article->title from" . $article->author->name;
    }
}
