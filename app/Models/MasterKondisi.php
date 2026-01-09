<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKondisi extends Model
{
    use HasFactory;

    protected $table = 'master_kondisi';

    protected $fillable = [
        'nama_kondisi',
        'keterangan',
        'kode_warna',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer'
    ];

    // Relationship
    public function dataAset()
    {
        return $this->hasMany(DataAsetKolektif::class, 'kondisi_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
