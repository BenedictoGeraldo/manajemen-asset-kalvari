<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditColumns;

class MasterLokasi extends Model
{
    use HasFactory, HasAuditColumns;

    protected $table = 'master_lokasi';

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'keterangan_lokasi',
        'gedung',
        'lantai',
        'ruangan',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship
    public function dataAset()
    {
        return $this->hasMany(DataAsetKolektif::class, 'lokasi_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor
    public function getLokasiLengkapAttribute()
    {
        $parts = array_filter([
            $this->gedung,
            $this->lantai ? 'Lantai ' . $this->lantai : null,
            $this->ruangan
        ]);
        
        $fullName = $this->nama_lokasi . (count($parts) > 0 ? ' (' . implode(', ', $parts) . ')' : '');
        
        return $this->kode_lokasi ? $this->kode_lokasi . ' - ' . $fullName : $fullName;
    }
}
