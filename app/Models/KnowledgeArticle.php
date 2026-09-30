<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KnowledgeArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'summary', 'content', 'category_id',
        'author_id', 'ticket_id', 'views', 'helpful_count',
        'not_helpful_count', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . Str::random(5);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ArticleTag::class, 'article_tag', 'article_id', 'tag_id');
    }

    public function ratings()
    {
        return $this->hasMany(ArticleRating::class, 'article_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getUserRatingAttribute(): ?bool
    {
        if (!auth()->check()) return null;
        $rating = $this->ratings()->where('user_id', auth()->id())->first();
        return $rating?->is_helpful;
    }

    public function getRatingPercentageAttribute(): int
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        if ($total === 0) return 0;
        return round(($this->helpful_count / $total) * 100);
    }
}