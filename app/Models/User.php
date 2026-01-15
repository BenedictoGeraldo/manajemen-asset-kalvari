<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'role_id',
        'name',
        'username',
        'email',
        'phone',
        'password',
        'profile_picture',
        'is_active',
        'last_login_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the department that the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the role that the user has.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this user.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Permissions yang dimiliki user (legacy compatibility)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Cek apakah user memiliki permission
     */
    public function hasPermission($permissionName)
    {
        // Super admin memiliki semua akses
        if ($this->is_super_admin) {
            return true;
        }

        // Check via role permissions (new system)
        if ($this->role) {
            return $this->role->permissions()->where('slug', $permissionName)->exists();
        }

        // Check direct permissions (legacy system)
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Cek apakah user memiliki salah satu dari beberapa permissions
     */
    public function hasAnyPermission($permissions)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    /**
     * Berikan permission ke user
     */
    public function givePermission($permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();

        if ($permission && !$this->hasPermission($permissionName)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Cabut permission dari user
     */
    public function revokePermission($permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Sync permissions user
     */
    public function syncPermissions($permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }
}
