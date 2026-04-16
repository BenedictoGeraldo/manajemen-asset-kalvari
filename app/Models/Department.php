<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'type',
        'code',
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Get the parent department.
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get the child departments.
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Get the users in this department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the user who created this department.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this department.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this department.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
