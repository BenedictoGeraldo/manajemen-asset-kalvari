<?php

namespace App\Models;

use App\Models\DataAsetKolektif;
use App\Models\MasterKategori;
use App\Models\MasterKondisi;
use App\Models\MasterLokasi;
use App\Models\MasterPengelola;
use App\Models\TransaksiPembelian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelianItem extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembelian_items';

    protected $fillable = [
        'transaksi_pembelian_id',
        'nama_item',
        'kategori_id',
        'deskripsi',
        'kegunaan',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'lokasi_id',
        'kondisi_id',
        'pengelola_id',
        'tahun_pengadaan',
        'catatan',
        'aset_kolektif_id',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tahun_pengadaan' => 'integer',
    ];

    public function pembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'transaksi_pembelian_id');
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

    public function asetKolektif()
    {
        return $this->belongsTo(DataAsetKolektif::class, 'aset_kolektif_id');
    }
}
