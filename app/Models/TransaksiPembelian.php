<?php

namespace App\Models;

use App\Models\TransaksiPembelianItem;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditColumns;

class TransaksiPembelian extends Model
{
    use HasFactory, SoftDeletes, HasAuditColumns;

    protected $table = 'transaksi_pembelian';

    protected $fillable = [
        'nomor_pembelian',
        'tanggal_pembelian',
        'vendor_nama',
        'vendor_kontak',
        'nama_pengaju',
        'unit_pengaju',
        'sumber_dana',
        'department_id',
        'alasan_penolakan',
        'status',
        'total_nilai',
        'catatan',
        'is_posted_to_aset',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'aset_kolektif_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'total_nilai' => 'decimal:2',
        'is_posted_to_aset' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiPembelianItem::class, 'transaksi_pembelian_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nomor_pembelian', 'like', "%{$search}%")
              ->orWhere('vendor_nama', 'like', "%{$search}%");
        });
    }
}
