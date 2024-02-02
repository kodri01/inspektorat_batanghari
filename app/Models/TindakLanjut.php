<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakLanjut extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wilayah_id',
        'temuan_id',
        'obrik_id',
        'lhp_id',
        'rekomendasi_id',
        'uraian',
        'status_tl',
        'nilai_selesai',
        'nilai_dalam_proses',
        'nilai_sisa',
        'nilai_setor',
        'saran',
        'file',
        'status',
    ];

    public function obrik()
    {
        return $this->belongsTo(Obrik::class, 'obrik_id', 'id');
    }

    public function lhp()
    {
        return $this->belongsTo(Lhp::class, 'lhp_id');
    }

    public function temuan()
    {
        return $this->belongsTo(Temuans::class, 'temuan_id');
    }

    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasies::class, 'rekomendasi_id');
    }
}