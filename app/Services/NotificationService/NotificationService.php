<?php

namespace App\Services\NotificationService;

use App\Models\Article;

interface NotificationService
{
    public function notifyAbout(Article $article): string;
}
