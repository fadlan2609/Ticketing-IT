<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleTag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function articles()
    {
        return $this->belongsToMany(KnowledgeArticle::class, 'article_tag', 'tag_id', 'article_id');
    }
}