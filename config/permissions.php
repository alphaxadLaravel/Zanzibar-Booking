<?php

use App\Support\DealPermissions;

$dealPermissionGroups = [];

foreach (DealPermissions::types() as $type => $meta) {
    $groupLabel = 'All Services · ' . $meta['label'];
    $prefix = $meta['prefix'];

    $dealPermissionGroups[$groupLabel] = [
        "{$prefix}.view" => "View {$meta['label']} list",
        "{$prefix}.create" => "Create {$meta['label']}",
        "{$prefix}.edit" => "Edit {$meta['label']}",
        "{$prefix}.delete" => "Delete {$meta['label']}",
    ];
}

return [
    'layout' => [
        [
            'title' => 'Dashboard',
            'icon' => 'mdi-view-dashboard',
            'groups' => ['Dashboard'],
        ],
        [
            'title' => 'All Services',
            'icon' => 'mdi-briefcase-outline',
            'description' => 'Deals and content shown under All Services in the sidebar.',
            'groups' => [
                'All Services · Hotels',
                'All Services · Apartments',
                'All Services · Packages',
                'All Services · Activities / Tours',
                'All Services · Cars',
                'All Services · Blog',
            ],
            'module_icons' => [
                'All Services · Hotels' => 'mdi-city-variant-outline',
                'All Services · Apartments' => 'mdi-home-city',
                'All Services · Packages' => 'mdi-map-marker-radius',
                'All Services · Activities / Tours' => 'mdi-run-fast',
                'All Services · Cars' => 'mdi-car',
                'All Services · Blog' => 'mdi-post-outline',
            ],
        ],
        [
            'title' => 'Manage',
            'icon' => 'mdi-tune-variant',
            'description' => 'Bookings, users, partners and payments.',
            'groups' => [
                'Manage · Bookings',
                'Manage · Users',
                'Manage · Partners',
                'Manage · Payments',
                'Manage · Reviews',
            ],
            'module_icons' => [
                'Manage · Bookings' => 'mdi-calendar-check',
                'Manage · Users' => 'mdi-account-group',
                'Manage · Partners' => 'mdi-handshake-outline',
                'Manage · Payments' => 'mdi-cash-multiple',
                'Manage · Reviews' => 'mdi-star-outline',
            ],
        ],
        [
            'title' => 'Settings',
            'icon' => 'mdi-cog-outline',
            'description' => 'Categories, features and site configuration.',
            'groups' => [
                'Settings · Categories',
                'Settings · Features',
                'Settings · System',
                'Settings · SEO',
                'Settings · CMS Pages',
            ],
            'module_icons' => [
                'Settings · Categories' => 'mdi-shape-outline',
                'Settings · Features' => 'mdi-star-outline',
                'Settings · System' => 'mdi-cog',
                'Settings · SEO' => 'mdi-search-web',
                'Settings · CMS Pages' => 'mdi-file-document-outline',
            ],
        ],
        [
            'title' => 'Account',
            'icon' => 'mdi-account-circle-outline',
            'description' => 'Contact messages and account-related pages.',
            'groups' => [
                'Account · Contact',
            ],
            'module_icons' => [
                'Account · Contact' => 'mdi-email-outline',
            ],
        ],
    ],

    'sections' => [
        'Dashboard' => 'Main admin overview and statistics.',
        'All Services · Hotels' => 'Sidebar: All Services → Hotels',
        'All Services · Apartments' => 'Sidebar: All Services → Apartments',
        'All Services · Packages' => 'Sidebar: All Services → Packages',
        'All Services · Activities / Tours' => 'Sidebar: All Services → Activities',
        'All Services · Cars' => 'Sidebar: All Services → Cars',
        'All Services · Blog' => 'Sidebar: All Services → Blog',
        'Manage · Bookings' => 'Sidebar: Manage → Bookings & My Bookings',
        'Manage · Users' => 'Sidebar: Manage → Users',
        'Manage · Partners' => 'Sidebar: Manage → Partners',
        'Manage · Payments' => 'Sidebar: Manage → Payments',
        'Manage · Reviews' => 'Sidebar: Manage → Reviews',
        'Settings · Categories' => 'Sidebar: Settings → Categories',
        'Settings · Features' => 'Sidebar: Settings → Features',
        'Settings · System' => 'Sidebar: Account → Settings → System, General & Media',
        'Settings · SEO' => 'Sidebar: Account → Settings → Home Page SEO',
        'Settings · CMS Pages' => 'Sidebar: Account → Settings → About, Terms, Privacy, etc.',
        'Account · Contact' => 'Sidebar: Account → Contact Messages',
        'Administration' => 'Super Admin only — manage other admins.',
    ],

    'groups' => array_merge([
        'Dashboard' => [
            'dashboard' => 'View dashboard',
        ],
    ], $dealPermissionGroups, [
        'All Services · Blog' => [
            'blog.view' => 'View blog posts',
            'blog.create' => 'Create blog posts',
            'blog.edit' => 'Edit blog posts',
            'blog.delete' => 'Delete blog posts',
        ],
        'Manage · Bookings' => [
            'bookings.view' => 'View bookings',
            'bookings.manage' => 'Update booking status',
        ],
        'Manage · Users' => [
            'users.view' => 'View users',
            'users.manage' => 'Create, edit, suspend and delete users',
        ],
        'Manage · Partners' => [
            'partners.view' => 'View partners',
            'partners.manage' => 'Approve, reject and assign deals',
        ],
        'Manage · Payments' => [
            'payments.view' => 'View payments',
        ],
        'Manage · Reviews' => [
            'reviews.view' => 'View deal reviews',
            'reviews.manage' => 'Approve and decline deal reviews',
        ],
        'Manage · Flights' => [
            'flights.view' => 'View flight search analytics',
            'flights.manage' => 'Manage and delete flight analytics',
        ],
        'Settings · Categories' => [
            'categories.manage' => 'Manage categories',
        ],
        'Settings · Features' => [
            'features.manage' => 'Manage features',
        ],
        'Settings · System' => [
            'settings.system' => 'System, general, security and media settings',
        ],
        'Settings · SEO' => [
            'settings.seo' => 'Home page SEO settings',
        ],
        'Settings · CMS Pages' => [
            'settings.content' => 'About, partner page, terms, privacy and CMS pages',
        ],
        'Account · Contact' => [
            'contact.view' => 'View contact messages',
            'contact.manage' => 'Update status and delete messages',
        ],
        'Administration' => [
            'admins.manage' => 'Create and manage admin accounts (Super Admin only)',
        ],
    ]),
];
