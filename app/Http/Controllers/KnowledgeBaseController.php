<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\ArticleTag;
use App\Models\Category;
use App\Models\KnowledgeArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeArticle::published()
            ->with(['category', 'author', 'tags']);

        // Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($tagSlug = $request->input('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $tagSlug));
        }

        $articles = $query->orderByDesc('views')->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)->get();
        $popularTags = ArticleTag::withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        return view('kb.index', compact('articles', 'categories', 'popularTags'));
    }

    public function show(KnowledgeArticle $article)
    {
        if (!$article->is_published && !auth()->user()->hasAnyRole(['Super Admin', 'IT Support'])) {
            abort(404);
        }

        $article->incrementViews();
        $article->load(['category', 'author', 'tags', 'ratings']);

        // Artikel terkait
        $relatedArticles = KnowledgeArticle::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->limit(4)
            ->get();

        return view('kb.show', compact('article', 'relatedArticles'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'IT Support'])) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();

        // Pre-fill dari tiket (kalau datang dari halaman tiket)
        $ticket = null;
        if ($ticketId = $request->input('ticket_id')) {
            $ticket = \App\Models\Ticket::find($ticketId);
        }

        return view('kb.create', compact('categories', 'ticket'));
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();

        $article = KnowledgeArticle::create([
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'],
            'category_id' => $data['category_id'],
            'author_id' => auth()->id(),
            'is_published' => $request->boolean('is_published', true),
            'ticket_id' => $request->input('ticket_id'),
        ]);

        // Simpan tags
        $this->syncTags($article, $data['tags'] ?? '');

        return redirect()->route('kb.show', $article)
            ->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(KnowledgeArticle $article)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'IT Support'])) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        $tagString = $article->tags->pluck('name')->implode(', ');

        return view('kb.edit', compact('article', 'categories', 'tagString'));
    }

    public function update(UpdateArticleRequest $request, KnowledgeArticle $article)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'IT Support'])) {
            abort(403);
        }

        $data = $request->validated();

        $article->update([
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'],
            'category_id' => $data['category_id'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        $this->syncTags($article, $data['tags'] ?? '');

        return redirect()->route('kb.show', $article)
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(KnowledgeArticle $article)
    {
        if (!auth()->user()->hasRole('Super Admin') && $article->author_id !== auth()->id()) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('kb.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * Vote artikel (helpful / not helpful)
     */
    public function rate(Request $request, KnowledgeArticle $article)
    {
        $request->validate([
            'is_helpful' => ['required', 'boolean'],
        ]);

        $isHelpful = $request->boolean('is_helpful');

        // Cek apakah sudah pernah vote
        $existing = $article->ratings()->where('user_id', auth()->id())->first();

        if ($existing) {
            // Update vote
            if ($existing->is_helpful !== $isHelpful) {
                // Kurangi counter lama
                if ($existing->is_helpful) {
                    $article->decrement('helpful_count');
                } else {
                    $article->decrement('not_helpful_count');
                }

                // Tambah counter baru
                if ($isHelpful) {
                    $article->increment('helpful_count');
                } else {
                    $article->increment('not_helpful_count');
                }

                $existing->update(['is_helpful' => $isHelpful]);
            }
        } else {
            // Vote baru
            $article->ratings()->create([
                'user_id' => auth()->id(),
                'is_helpful' => $isHelpful,
            ]);

            if ($isHelpful) {
                $article->increment('helpful_count');
            } else {
                $article->increment('not_helpful_count');
            }
        }

        return back()->with('success', 'Terima kasih atas feedback Anda!');
    }

    /**
     * Sync tags dari string comma-separated
     */
    private function syncTags(KnowledgeArticle $article, string $tagsString): void
    {
        $tagIds = [];

        if (!empty($tagsString)) {
            $tags = array_filter(array_map('trim', explode(',', $tagsString)));

            foreach ($tags as $tagName) {
                $tag = ArticleTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }

        $article->tags()->sync($tagIds);
    }
}