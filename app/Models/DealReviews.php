<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealReviews extends Model
{
    protected $table = 'deal_reviews';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'deal_id',
        'user_id',
        'review_title',
        'review_content',
        'rating',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', self::STATUS_DECLINED);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function approve(): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'is_approved' => true,
        ]);
    }

    public function decline(): void
    {
        $this->update([
            'status' => self::STATUS_DECLINED,
            'is_approved' => false,
        ]);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

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

    public function getReviewerNameAttribute()
    {
        if ($this->user) {
            return $this->user->full_name ?: 'Anonymous User';
        }

        return 'Anonymous';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->moderation_status) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINED => 'Declined',
            default => 'Pending',
        };
    }

    public function getModerationStatusAttribute(): string
    {
        if (!empty($this->status)) {
            return $this->status;
        }

        return $this->is_approved ? self::STATUS_APPROVED : self::STATUS_PENDING;
    }
}
