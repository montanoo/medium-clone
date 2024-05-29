<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'description', 'quote', 'author_id', 'is_premium'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function author() // postedBy
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    public function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn(?string $coverUrl) => $coverUrl ? asset($coverUrl) : null,
            set: fn(?string $coverUrl) => $coverUrl ? asset($coverUrl) : null,
        );
    }
}