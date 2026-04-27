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
        'sub_lokasi',
        'keterangan_lokasi',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ───────────────────────────────────────────

    /** Aset yang berada di lokasi ini */
    public function dataAset()
    {
        return $this->hasMany(DataAsetKolektif::class, 'lokasi_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    /** Hanya lokasi aktif */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Accessors ───────────────────────────────────────────────

    /** Tampilan lengkap: "0A - Area Utama Gereja" atau "Gereja" */
    public function getLokasiLengkapAttribute(): string
    {
        $fullName = $this->nama_lokasi . ($this->sub_lokasi ? ' - ' . $this->sub_lokasi : '');
        if ($this->kode_lokasi) {
            return $this->kode_lokasi . ' - ' . $fullName;
        }
        return $fullName;
    }

    /** Label untuk dropdown: "[Gereja] Area Utama Gereja (0A)" */
    public function getDropdownLabelAttribute(): string
    {
        $label = $this->nama_lokasi;
        if ($this->sub_lokasi) {
            $label = '[' . $this->nama_lokasi . '] ' . $this->sub_lokasi;
        }
        
        if ($this->kode_lokasi) {
            $label .= ' (' . $this->kode_lokasi . ')';
        }
        return $label;
    }
}
