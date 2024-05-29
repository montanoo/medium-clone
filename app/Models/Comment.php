<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'author_id', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function author() // postedBy
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
