<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPeminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_peminjaman';

    protected $fillable = [
        'nomor_peminjaman',
        'tanggal_pengajuan',
        'tanggal_rencana_kembali',
        'tanggal_disetujui',
        'tanggal_serah_terima',
        'tanggal_dikembalikan',
        'nama_peminjam',
        'kontak_peminjam',
        'unit_peminjam',
        'status',
        'keperluan',
        'catatan',
        'catatan_approval',
        'catatan_serah_terima',
        'catatan_pengembalian',
        'approved_by',
        'handover_by',
        'returned_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_disetujui' => 'datetime',
        'tanggal_serah_terima' => 'datetime',
        'tanggal_dikembalikan' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiPeminjamanItem::class, 'transaksi_peminjaman_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function handoverBy()
    {
        return $this->belongsTo(User::class, 'handover_by');
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nomor_peminjaman', 'like', "%{$search}%")
                ->orWhere('nama_peminjam', 'like', "%{$search}%")
                ->orWhere('unit_peminjam', 'like', "%{$search}%");
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
