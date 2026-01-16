<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withPivot(['can_create', 'can_read', 'can_update', 'can_delete'])
                    ->withTimestamps();
    }

    /**
     * Users yang memiliki permission ini (legacy compatibility)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}
