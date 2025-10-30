<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAccess extends Model
{
    /** @use HasFactory<\Database\Factories\RoleAccessFactory> */
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'route_name',
        'can_access',
    ];

    protected $model = 'Role Access';

    protected array $auditableRelations = ['accesses'];

    /**
     * A RoleAccess belongs to a Role.
     *
     * @return belongsTo<Role>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
