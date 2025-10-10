<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'street_address',
        'city',
        'state',
        'zip',
        'country',
        'file_base_name',
        'file_extension',
    ];

    protected $attributes = [
        'file_base_name' => 'default_avatar',
        'file_extension' => '.jpg',
    ];

    /**
     * A UserProfile belongs to a User.
     *
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
