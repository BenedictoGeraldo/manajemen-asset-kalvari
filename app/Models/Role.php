<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Get the users with this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the permissions for the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
                    ->withPivot(['can_create', 'can_read', 'can_update', 'can_delete'])
                    ->withTimestamps();
    }

    /**
     * Get the user who created this role.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this role.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this role.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
