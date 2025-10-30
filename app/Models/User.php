<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Traits\Auditable;
use App\Traits\Searchable;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Searchable, PasswordsCanResetPassword, Auditable;

    protected const SEARCHABLE_COLUMNS = ['name', 'email'];
    protected const ALLOWED_SORTS = ['id', 'name', 'email'];
    protected const ALLOWED_SORT_DIRECTIONS = ['asc', 'desc'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'last_login_at',
    ];

    protected $model = 'User';

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
            'password'          => 'hashed',
            'status'            => UserStatus::class,
        ];
    }

    /**
     * A User has one UserProfile.
     *
     * @return BelongsTo<UserProfile>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * A User belongs to a Role.
     *
     * @return BelongsTo<Role>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if the user has access to a specific route.
     * 
     * @param string $routeName
     * @return bool
     */
    public function canAccess(string $routeName): bool
    {
        static $cachedAccesses = [];

        $roleId = $this->role_id;

        if (!isset($cachedAccesses[$roleId])) {
            $cachedAccesses[$roleId] = $this->role
                ? $this->role->accesses()
                ->where('can_access', true)
                ->pluck('route_name')
                ->toArray()
                : [];
        }

        return in_array($routeName, $cachedAccesses[$roleId]);
    }

    public function getFullAddressAttribute()
    {
        return trim("{$this->profile->street_address}, {$this->profile->city}, {$this->profile->state} {$this->profile->zip}, {$this->profile->country}");
    }
}
