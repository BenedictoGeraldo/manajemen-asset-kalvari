<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditColumns;

class TransaksiPemusnahan extends Model
{
    use HasFactory, SoftDeletes, HasAuditColumns;

    protected $table = 'transaksi_pemusnahan';

    protected $fillable = [
        'kode_transaksi',
        'aset_id',
        'jumlah_dimusnahkan',
        'tanggal_pemusnahan',
        'alasan_pemusnahan',
        'metode_pemusnahan',
        'penanggung_jawab',
        'catatan',
        'dokumen_berita_acara',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'tanggal_pemusnahan' => 'date',
        'jumlah_dimusnahkan' => 'integer'
    ];

    public function aset()
    {
        return $this->belongsTo(DataAsetKolektif::class, 'aset_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $model->kode_transaksi = 'DIS-' . date('Ymd') . '-' . str_pad(static::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
