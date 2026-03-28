<?php

namespace App\Models;

use App\Models\TransaksiPeminjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPeminjamanItem extends Model
{
    use HasFactory;

    protected $table = 'transaksi_peminjaman_items';

    protected $fillable = [
        'transaksi_peminjaman_id',
        'data_aset_kolektif_id',
        'aset_kode_snapshot',
        'aset_nama_snapshot',
        'jumlah',
        'kondisi_awal_id',
        'kondisi_akhir_id',
        'catatan_item',
        'catatan_serah_terima',
        'catatan_pengembalian',
        'returned_at',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'returned_at' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(TransaksiPeminjaman::class, 'transaksi_peminjaman_id');
    }

    public function aset()
    {
        return $this->belongsTo(DataAsetKolektif::class, 'data_aset_kolektif_id');
    }

    public function kondisiAwal()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_awal_id');
    }

    public function kondisiAkhir()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_akhir_id');
    }
}
