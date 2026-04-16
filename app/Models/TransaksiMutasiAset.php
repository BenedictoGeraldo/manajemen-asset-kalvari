<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiMutasiAset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_mutasi_aset';

    protected $fillable = [
        'nomor_mutasi',
        'data_aset_kolektif_id',
        'tanggal_mutasi',
        'jenis_mutasi',
        'status',
        'lokasi_lama_id',
        'lokasi_baru_id',
        'department_lama_id',
        'department_baru_id',
        'penanggung_jawab_lama_id',
        'penanggung_jawab_baru_id',
        'kondisi_id',
        'alasan',
        'catatan',
        'catatan_approval',
        'tanggal_diajukan',
        'tanggal_disetujui',
        'tanggal_mulai',
        'tanggal_selesai',
        'approved_by',
        'started_by',
        'completed_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
        'tanggal_diajukan' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Relationships
    public function aset()
    {
        return $this->belongsTo(DataAsetKolektif::class, 'data_aset_kolektif_id');
    }

    public function lokasiLama()
    {
        return $this->belongsTo(MasterLokasi::class, 'lokasi_lama_id');
    }

    public function lokasiBaru()
    {
        return $this->belongsTo(MasterLokasi::class, 'lokasi_baru_id');
    }

    public function departmentLama()
    {
        return $this->belongsTo(Department::class, 'department_lama_id');
    }

    public function departmentBaru()
    {
        return $this->belongsTo(Department::class, 'department_baru_id');
    }

    public function penanggungJawabLama()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_lama_id');
    }

    public function penanggungJawabBaru()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_baru_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(MasterKondisi::class, 'kondisi_id');
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where('nomor_mutasi', 'like', "%{$search}%")
            ->orWhereHas('aset', function ($q) use ($search) {
                $q->where('kode_aset', 'like', "%{$search}%")
                    ->orWhere('nama_aset', 'like', "%{$search}%");
            });
    }

    // Methods
    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'diajukan', 'ditolak', 'dibatalkan']);
    }

    public function canDelete(): bool
    {
        return in_array($this->status, ['draft', 'diajukan', 'ditolak', 'dibatalkan']);
    }
};
