<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealReviews extends Model
{
    protected $table = 'deal_reviews';
    
    protected $fillable = [
        'deal_id',
        'user_id',
        'review_title',
        'review_content',
        'rating',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean'
    ];

    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor for star rating display
    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fa fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="fa fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    // Accessor for reviewer name from user relationship
    public function getReviewerNameAttribute()
    {
        if ($this->user) {
            return $this->user->full_name ?: 'Anonymous User';
        }
        return 'Anonymous';
    }
}