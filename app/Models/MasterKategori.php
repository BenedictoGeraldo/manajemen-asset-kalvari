<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditColumns;

class MasterKategori extends Model
{
    use HasFactory, HasAuditColumns;

    protected $table = 'master_kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
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
        return $this->hasMany(DataAsetKolektif::class, 'kategori_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
