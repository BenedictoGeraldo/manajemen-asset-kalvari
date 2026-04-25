<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditColumns;

class DataAsetKolektif extends Model
{
    use HasFactory, SoftDeletes, HasAuditColumns;

    protected $table = 'data_aset_kolektif';

    protected $fillable = [
        'nama_aset',
        'kategori_id',
        'department_id',
        'deskripsi_aset',
        'gambar_aset_base64',
        'ukuran',
        'ukuran_label',
        'deskripsi_ukuran_bentuk',
        'lokasi_id',
        'kegunaan',
        'label_penggunaan',
        'keterangan_kegunaan',
        'jumlah_barang',
        'tipe_grup',
        'tipe_grup_v2',
        'keterangan_tipe_grup',
        'nilai_budget',
        'sumber_dana',
        'keterangan_budget',
        'pengelola_id',
        'tahun_pengadaan',
        'nilai_pengadaan_total',
        'nilai_pengadaan_per_unit',
        'kondisi_id',
        'kode_aset',
        'catatan',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'jumlah_barang' => 'integer',
        'nilai_budget' => 'decimal:2',
        'nilai_pengadaan_total' => 'decimal:2',
        'nilai_pengadaan_per_unit' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function kategori()
    {
        return $this->belongsTo(MasterKategori::class, 'kategori_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(MasterLokasi::class, 'lokasi_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_id');
    }

    public function pengelola()
    {
        return $this->belongsTo(MasterPengelola::class, 'pengelola_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function scopeByLokasi($query, $lokasiId)
    {
        return $query->where('lokasi_id', $lokasiId);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun_pengadaan', $tahun);
    }

    // Accessors
    public function getNilaiTotalFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->nilai_pengadaan_total, 0, ',', '.');
    }

    public function getNilaiPerUnitFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->nilai_pengadaan_per_unit, 0, ',', '.');
    }
}
