<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'is_active',
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
    ];

    /**
     * Permissions yang dimiliki user
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
