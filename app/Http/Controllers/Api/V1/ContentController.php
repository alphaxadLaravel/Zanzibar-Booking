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

class ContentController extends Controller
{
    public function page(string $slug)
    {
        $page = Pages::where('page', $slug)->first()
            ?? Pages::where('slug', $slug)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $page->id,
                'title' => $page->title ?? $page->page,
                'slug' => $page->slug ?? $page->page,
                'content' => $page->content ?? $page->body,
            ],
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::query()
            ->when(
                \Schema::hasColumn((new Blog)->getTable(), 'status'),
                fn ($q) => $q->where('status', 1)
            )
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => collect($blogs->items())->map(function ($b) {
                $coverPath = $b->cover_photo ?? $b->image ?? null;

                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'slug' => $b->slug ?? null,
                    'excerpt' => $b->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($b->content ?? ''), 140),
                    'cover' => $coverPath ? asset('storage/' . ltrim($coverPath, '/')) : null,
                    'created_at' => optional($b->created_at)?->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'total' => $blogs->total(),
            ],
        ]);
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
