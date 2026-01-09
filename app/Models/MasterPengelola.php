<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPengelola extends Model
{
    use HasFactory;

    protected $table = 'master_pengelola';

    protected $fillable = [
        'nama_pengelola',
        'jabatan',
        'departemen',
        'kontak',
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship
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
        return $this->nama_pengelola . ($this->jabatan ? ' - ' . $this->jabatan : '');
    }
}
