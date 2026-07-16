<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'status',
        'role_id',
        'is_suspended',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
        ];
    }

    public function isPartner(): bool
    {
        return optional($this->role)->name === 'Partner';
    }

    public function isSuperAdmin(): bool
    {
        return optional($this->role)->name === 'Super Admin';
    }

    public function isAdmin(): bool
    {
        return in_array(optional($this->role)->name, ['Super Admin', 'Admin'], true);
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array(optional($this->role)->name, ['Super Admin', 'Admin', 'Partner'], true);
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (optional($this->role)->name !== 'Admin') {
            return false;
        }

        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }

        return $this->permissions->contains('slug', $slug);
    }

    public function hasAnyPermission(array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    public function canDeal(string $type, string $action): bool
    {
        return $this->hasPermission(\App\Support\DealPermissions::slug($type, $action));
    }

    public function adminLandingUrl(): string
    {
        if (!$this->canAccessAdminPanel()) {
            return route('index');
        }

        return \App\Support\AdminLanding::urlFor($this);
    }

    public function syncPermissions(array $permissionIds): void
    {
        if (!$this->isSuperAdmin() && optional($this->role)->name === 'Admin') {
            $this->permissions()->sync($permissionIds);
        }
    }

    /**
     * Get the role that belongs to the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
