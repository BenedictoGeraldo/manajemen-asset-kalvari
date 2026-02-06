<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataAsetKolektif extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_aset_kolektif';

    protected $fillable = [
        'nama_aset',
        'kategori_id',
        'deskripsi_aset',
        'ukuran',
        'deskripsi_ukuran_bentuk',
        'lokasi_id',
        'kegunaan',
        'keterangan_kegunaan',
        'jumlah_barang',
        'tipe_grup',
        'keterangan_tipe_grup',
        'budget',
        'keterangan_budget',
        'pengelola_id',
        'tahun_pengadaan',
        'nilai_pengadaan_total',
        'nilai_pengadaan_per_unit',
        'kondisi_id',
        'kode_aset',
        'catatan',
        'is_active'
    ];

    protected $casts = [
        'jumlah_barang' => 'integer',
        'budget' => 'decimal:2',
        'nilai_pengadaan_total' => 'decimal:2',
        'nilai_pengadaan_per_unit' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
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
        return 'Rp ' . number_format($this->nilai_pengadaan_total, 0, ',', '.');
    }

    public function getNilaiPerUnitFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_pengadaan_per_unit, 0, ',', '.');
    }
}
