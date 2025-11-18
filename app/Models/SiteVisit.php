<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'session_id',
        'visitor_hash',
        'first_visit_at',
        'last_visit_at',
        'visit_count'
    ];

    protected $casts = [
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
        'visit_count' => 'integer'
    ];

    /**
     * Generate a unique hash for visitor identification
     * Based on IP address and user agent
     */
    public static function generateVisitorHash($ipAddress, $userAgent = null)
    {
        $data = $ipAddress . '|' . ($userAgent ?? '');
        return hash('sha256', $data);
    }

    /**
     * Track a site visit
     */
    public static function trackVisit($ipAddress, $userAgent = null, $sessionId = null)
    {
        $visitorHash = self::generateVisitorHash($ipAddress, $userAgent);
        $now = now();

        $visit = self::where('visitor_hash', $visitorHash)->first();
        
        if ($visit) {
            // Update existing visit - increment visit count
            $visit->increment('visit_count');
            $visit->update([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_id' => $sessionId,
                'last_visit_at' => $now
            ]);
            return $visit;
        } else {
            // Create new visit
            return self::create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_id' => $sessionId,
                'visitor_hash' => $visitorHash,
                'first_visit_at' => $now,
                'last_visit_at' => $now,
                'visit_count' => 1
            ]);
        }
    }
}
