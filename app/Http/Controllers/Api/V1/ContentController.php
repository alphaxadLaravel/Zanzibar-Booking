<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Deal;
use App\Models\DealReviews;
use App\Models\Pages;
use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /** Website footer / CMS page slugs → `pages.page` title in DB */
    protected array $pageSlugMap = [
        'about-us' => 'About Us',
        'become-a-partner' => 'Become a Partner',
        'our-commitment' => 'Our Commitment',
        'terms-conditions' => 'Terms & Conditions',
        'privacy-policy' => 'Privacy Policy',
    ];

    public function pages()
    {
        $known = collect($this->pageSlugMap)->map(function (string $title, string $slug) {
            $exists = Pages::where('page', $title)->exists();

            return [
                'slug' => $slug,
                'title' => $title,
                'available' => $exists,
            ];
        })->values();

        // Also include any other CMS rows not in the fixed map.
        $extras = Pages::query()
            ->orderBy('page')
            ->get()
            ->filter(fn (Pages $p) => ! in_array($p->page, array_values($this->pageSlugMap), true))
            ->map(fn (Pages $p) => [
                'slug' => Str::slug($p->page),
                'title' => $p->page,
                'available' => true,
            ])
            ->values();

        return response()->json([
            'data' => $known->concat($extras)->values(),
        ]);
    }

    public function page(string $slug)
    {
        $pageName = $this->pageSlugMap[$slug] ?? null;

        $page = null;
        if ($pageName) {
            $page = Pages::where('page', $pageName)->first();
        }

        if (!$page) {
            $page = Pages::where('page', $slug)->first()
                ?? Pages::whereRaw('LOWER(page) = ?', [strtolower(str_replace('-', ' ', $slug))])->first();
        }

        // Match extras keyed by slugified title
        if (!$page) {
            $page = Pages::query()->get()->first(
                fn (Pages $p) => Str::slug($p->page) === $slug
            );
        }

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $page->id,
                'title' => $page->page,
                'slug' => $slug,
                'content' => $this->richPlainText($page->content),
            ],
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::query()
            ->where('status', 1)
            ->with(['user', 'category'])
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => collect($blogs->items())->map(fn (Blog $b) => $this->mapBlog($b)),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    public function showBlog(string $id)
    {
        $blogId = HashidsHelper::resolveId($id) ?? (is_numeric($id) ? (int) $id : null);
        if (!$blogId) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog = Blog::query()
            ->where('status', 1)
            ->with(['user', 'category'])
            ->findOrFail($blogId);

        $recent = Blog::query()
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Blog $b) => $this->mapBlog($b));

        return response()->json([
            'data' => $this->mapBlog($blog, full: true),
            'recent' => $recent,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapBlog(Blog $b, bool $full = false): array
    {
        $coverPath = $b->cover_photo ?? $b->image ?? null;
        $html = $b->description ?? $b->content ?? '';
        $excerptSource = $b->preview_text ?: $html;
        $author = $b->user
            ? trim(($b->user->firstname ?? '') . ' ' . ($b->user->lastname ?? ''))
            : 'Zanzibar Bookings';

        $data = [
            'id' => $b->id,
            'hashid' => HashidsHelper::encode((int) $b->id),
            'title' => $b->title,
            'excerpt' => $this->plainText(Str::limit(strip_tags((string) $excerptSource), 160)),
            'cover' => $coverPath ? asset('storage/' . ltrim($coverPath, '/')) : null,
            'author' => $author !== '' ? $author : 'Zanzibar Bookings',
            'category' => $b->category?->category ?? $b->category?->name,
            'created_at' => optional($b->created_at)?->toIso8601String(),
        ];

        if ($full) {
            $data['content'] = $this->plainText($html);
            $data['preview_text'] = $this->plainText($b->preview_text);
        }

        return $data;
    }

    protected function plainText(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $text = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    /** Keep paragraph breaks for longer legal / CMS pages. */
    protected function richPlainText(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $value) ?? $value;
        $text = preg_replace('/<\/\s*p\s*>/i', "\n\n", $text) ?? $text;
        $text = preg_replace('/<\/\s*li\s*>/i', "\n", $text) ?? $text;
        $text = preg_replace('/<\/\s*h[1-6]\s*>/i', "\n\n", $text) ?? $text;
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/[ \t]+/u", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create([
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? ($validated['phone'] ? 'Phone: '.$validated['phone'] : 'Contact'),
            'content' => $validated['message'],
            'status' => 'new',
        ]);

        return response()->json(['message' => 'Message sent successfully'], 201);
    }

    public function storeReview(Request $request, string $dealId)
    {
        $id = HashidsHelper::resolveId($dealId) ?? (is_numeric($dealId) ? (int) $dealId : null);
        $deal = Deal::active()->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'title' => 'nullable|string|max:255',
        ]);

        $review = DealReviews::create([
            'deal_id' => $deal->id,
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'review_title' => $validated['title'] ?? 'Review',
            'review_content' => $validated['comment'],
            'status' => DealReviews::STATUS_PENDING,
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Review submitted for approval',
            'data' => [
                'id' => $review->id,
                'rating' => $review->rating,
            ],
        ], 201);
    }
}
