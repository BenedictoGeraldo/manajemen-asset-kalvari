<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditColumns;

class MasterPengelola extends Model
{
    use HasFactory, HasAuditColumns;

    protected $table = 'master_pengelola';

    protected $fillable = [
        'kode_pengelola',
        'department_id',
        'nama_pengelola',
        'jabatan',
        'kontak',
        'email',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function dataAset()
    {
        return $this->hasMany(DataAsetKolektif::class, 'pengelola_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        $fullName = $this->nama_pengelola . ($this->jabatan ? ' - ' . $this->jabatan : '');
        return $this->kode_pengelola ? $this->kode_pengelola . ' - ' . $fullName : $fullName;
    }
}
