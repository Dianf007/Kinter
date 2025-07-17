<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'post_type',
        'video_link',
        'author_id',
        'comment_count'
    ];

    // Relationship to categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Relationship to tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
