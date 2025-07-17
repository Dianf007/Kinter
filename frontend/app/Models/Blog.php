<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    // Use posts table for blog entries
    protected $table = 'posts';

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

    // Categories relationship with correct pivot table
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    // Tags relationship with correct pivot table
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    // Author relationship (assuming User model)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
