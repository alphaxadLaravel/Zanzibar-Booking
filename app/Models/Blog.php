<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'cover_photo',
        'user_id',
        'title',
        'preview_text',
        'description',
        'category_id',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor for status as string
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Published' : 'Draft';
    }

    // Accessor for status badge class
    public function getStatusBadgeClassAttribute()
    {
        return $this->status ? 'badge-success' : 'badge-warning';
    }
}
