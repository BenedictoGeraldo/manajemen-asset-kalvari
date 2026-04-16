<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPemeliharaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_pemeliharaan';

    protected $fillable = [
        'nomor_pemeliharaan',
        'data_aset_kolektif_id',
        'tanggal_pengajuan',
        'tanggal_rencana',
        'tanggal_disetujui',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_pemeliharaan',
        'prioritas',
        'status',
        'kondisi_sebelum_id',
        'kondisi_sesudah_id',
        'vendor_nama',
        'vendor_kontak',
        'estimasi_biaya',
        'realisasi_biaya',
        'keluhan',
        'tindakan',
        'catatan',
        'catatan_approval',
        'approved_by',
        'started_by',
        'completed_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_rencana' => 'date',
        'tanggal_disetujui' => 'datetime',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'realisasi_biaya' => 'decimal:2',
    ];

    public function aset()
    {
        return $this->belongsTo(DataAsetKolektif::class, 'data_aset_kolektif_id');
    }

    public function kondisiSebelum()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_sebelum_id');
    }

    public function kondisiSesudah()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_sesudah_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function startedBy()
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nomor_pemeliharaan', 'like', "%{$search}%")
                ->orWhere('vendor_nama', 'like', "%{$search}%")
                ->orWhereHas('aset', function ($asetQuery) use ($search) {
                    $asetQuery->where('nama_aset', 'like', "%{$search}%")
                        ->orWhere('kode_aset', 'like', "%{$search}%");
                });
        });
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'diajukan', 'ditolak', 'dibatalkan'], true);
    }

    public function canDelete(): bool
    {
        return in_array($this->status, ['draft', 'diajukan', 'ditolak', 'dibatalkan'], true);
    }
}
