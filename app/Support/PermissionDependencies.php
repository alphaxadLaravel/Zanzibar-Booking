<?php

namespace App\Support;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionDependencies
{
    /**
     * Slugs that must also be granted when another slug is selected.
     */
    protected static function requiredSlugsFor(string $slug): array
    {
        $required = [];

        if (preg_match('/^(.+)\.(create|edit|delete)$/', $slug, $matches)) {
            $required[] = $matches[1] . '.view';
        }

        $manageRequiresView = [
            'bookings.manage' => 'bookings.view',
            'users.manage' => 'users.view',
            'partners.manage' => 'partners.view',
            'contact.manage' => 'contact.view',
        ];

        if (isset($manageRequiresView[$slug])) {
            $required[] = $manageRequiresView[$slug];
        }

        return $required;
    }

    /**
     * Expand permission IDs so action permissions always include their view dependency.
     *
     * @param  array<int>  $permissionIds
     * @return array<int>
     */
    public static function expandIds(array $permissionIds): array
    {
        if (empty($permissionIds)) {
            return [];
        }

        $permissions = Permission::query()->whereIn('id', $permissionIds)->get();
        $requiredSlugs = [];

        foreach ($permissions as $permission) {
            foreach (self::requiredSlugsFor($permission->slug) as $slug) {
                $requiredSlugs[] = $slug;
            }
        }

        if (empty($requiredSlugs)) {
            return array_values(array_unique(array_map('intval', $permissionIds)));
        }

        $extraIds = Permission::query()
            ->whereIn('slug', array_unique($requiredSlugs))
            ->pluck('id');

        return array_values(array_unique(array_map('intval', array_merge($permissionIds, $extraIds->all()))));
    }

    /**
     * @param  Collection<int, Permission>  $permissions
     */
    public static function viewPermissionForModule(Collection $permissions): ?Permission
    {
        return $permissions->first(fn (Permission $permission) => str_ends_with($permission->slug, '.view'));
    }
}
