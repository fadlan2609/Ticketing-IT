<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OfficeInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'office_infos';

    protected $fillable = [
        'title', 'slug', 'summary', 'content', 'category',
        'metadata', 'icon', 'is_pinned', 'is_published',
        'published_until', 'author_id', 'views', 'sort_order',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
        'published_until' => 'datetime',
        'views' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($info) {
            if (empty($info->slug)) {
                $info->slug = Str::slug($info->title) . '-' . Str::random(5);
            }
        });
    }

    // ===== Relasi =====
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ===== Scope =====
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_until')
                  ->orWhere('published_until', '>=', now());
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_pinned')
                     ->orderBy('sort_order')
                     ->orderByDesc('created_at');
    }

    // ===== Helper =====
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'announcement' => 'Pengumuman',
            'policy' => 'Kebijakan',
            'contact' => 'Kontak',
            'hours' => 'Jam Operasional',
            'event' => 'Acara',
            default => 'Umum',
        };
    }

    public function getCategoryColorAttribute(): array
    {
        return match ($this->category) {
            'announcement' => ['bg' => '#FDECEF', 'text' => '#C8102E'],
            'policy' => ['bg' => '#EFF6FF', 'text' => '#1E40AF'],
            'contact' => ['bg' => '#ECFDF3', 'text' => '#166534'],
            'hours' => ['bg' => '#FFF7ED', 'text' => '#9A3412'],
            'event' => ['bg' => '#F5F3FF', 'text' => '#6D28D9'],
            default => ['bg' => '#F4F4F5', 'text' => '#52525B'],
        };
    }

    public function getIconDisplayAttribute(): string
    {
        return $this->icon ?: match ($this->category) {
            'announcement' => '📢',
            'policy' => '📋',
            'contact' => '📞',
            'hours' => '🕐',
            'event' => '🎉',
            default => 'ℹ️',
        };
    }
}