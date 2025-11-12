<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{
    use Searchable;

    protected const SEARCHABLE_COLUMNS = ['event', 'old_values', 'changed_values', 'new_values'];
    protected const ALLOWED_SORTS = ['created_at'];
    protected const ALLOWED_SORT_DIRECTIONS = ['asc', 'desc'];

    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'changed_values',
        'new_values',
        'message',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'changed_values' => 'array',
        'new_values' => 'array',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * An Audit belongs to a User.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function getChangedValuesAttribute()
    {
        $str = [];

        if (!is_null($this->attributes['changed_values'])) {
            foreach (json_decode($this->attributes['changed_values']) as $key => $value) {
                if (!in_array($key, ['created_at', 'updated_at'])) {
                    $str[] .= '[' . $key . '] => ' . $value;
                }
            }
        }

        return implode('&#9;', $str);
    }

    public function getOldValuesAttribute()
    {
        $str = [];

        if (!is_null($this->attributes['old_values'])) {
            foreach (json_decode($this->attributes['old_values']) as $key => $value) {
                if (!in_array($key, ['created_at', 'updated_at'])) {
                    $str[] .= '[' . $key . '] => ' . $value;
                }
            }
        }


        return implode('&#9;', $str);
    }

    public function getNewValuesAttribute()
    {
        $str = [];

        if (!is_null($this->attributes['new_values'])) {
            foreach (json_decode($this->attributes['new_values']) as $key => $value) {
                if (!in_array($key, ['created_at', 'updated_at'])) {
                    $str[] .= '[' . $key . '] => ' . $value;
                }
            }
        }
        return implode('<br>', $str);
    }

    public function isLogin(): bool
    {
        return $this->event == 'login';
    }

    public function hasChangedValues(): bool
    {
        return !is_null($this->attributes['changed_values']);
    }

    public function hasOldValues(): bool
    {
        return !is_null($this->attributes['old_values']);
    }

    public function hasNewValues(): bool
    {
        return !is_null($this->attributes['new_values']);
    }
}
