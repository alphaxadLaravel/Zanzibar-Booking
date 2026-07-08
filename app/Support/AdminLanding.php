<?php

namespace App\Support;

use App\Models\User;

class AdminLanding
{
    /**
     * First accessible admin page for this user (sidebar order).
     */
    public static function urlFor(User $user): string
    {
        if ($user->isPartner() || $user->isSuperAdmin() || $user->hasPermission('dashboard')) {
            return route('dashboard');
        }

        foreach (array_keys(DealPermissions::types()) as $dealType) {
            if ($user->hasPermission(DealPermissions::slug($dealType, 'view'))) {
                return route('admin.deal', $dealType);
            }
        }

        $permissionRoutes = [
            'blog.view' => fn () => route('admin.blog'),
            'bookings.view' => fn () => route('admin.bookings'),
            'users.view' => fn () => route('admin.users'),
            'partners.view' => fn () => route('admin.partners'),
            'payments.view' => fn () => route('admin.payments'),
            'reviews.view' => fn () => route('admin.reviews'),
            'categories.manage' => fn () => route('admin.categories'),
            'features.manage' => fn () => route('admin.features'),
            'settings.system' => fn () => route('admin.system.settings'),
            'settings.seo' => fn () => route('admin.home.seo'),
            'settings.content' => fn () => route('admin.manage.content', 'about-us'),
            'contact.view' => fn () => route('admin.contact.messages'),
        ];

        foreach ($permissionRoutes as $permission => $routeResolver) {
            if ($user->hasPermission($permission)) {
                return $routeResolver();
            }
        }

        return route('admin.profile');
    }
}
