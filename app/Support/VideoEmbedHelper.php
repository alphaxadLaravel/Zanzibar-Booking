<?php

namespace App\Support;

class VideoEmbedHelper
{
    /**
     * Extract a YouTube video ID from common URL formats (watch, youtu.be, shorts, reels, embed).
     */
    public static function youtubeVideoId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (preg_match('#youtu\.be/([\w-]{11})(?:\?|$|/)#i', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('#youtube\.com/(?:shorts|embed|v|live|reels?)/([\w-]{11})(?:\?|$|/)#i', $url, $matches)) {
            return $matches[1];
        }

        $query = [];
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $videoId = $query['v'] ?? null;

        if (is_string($videoId) && preg_match('/^[\w-]{11}$/', $videoId)) {
            return $videoId;
        }

        return null;
    }

    /**
     * Build an iframe embed URL for YouTube, Vimeo, or direct video files.
     */
    public static function embedUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            $videoId = self::youtubeVideoId($url);

            return $videoId
                ? 'https://www.youtube.com/embed/' . $videoId . '?rel=0&modestbranding=1&playsinline=1'
                : null;
        }

        if (str_contains($url, 'vimeo.com')) {
            $path = (string) parse_url($url, PHP_URL_PATH);
            $videoId = trim(basename($path));

            return $videoId !== '' && $videoId !== '/'
                ? 'https://player.vimeo.com/video/' . $videoId . '?title=0&byline=0&portrait=0'
                : null;
        }

        return $url;
    }
}
