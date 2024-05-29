<?php

namespace App\Services\NotificationService;

use App\Models\Article;

class ModeratorNotifier implements NotificationService
{
    public function notifyAbout(Article $article): string
    {
        return "This new article is ready for review! id:$article->id";
    }
}
