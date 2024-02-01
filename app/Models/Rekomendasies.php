<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rekomendasies extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wilayah_id',
        'obrik_id',
        'temuan_id',
        'lhp_id',
        'rekomendasi',
        'nilai_rekomendasi',
    ];

    public function obrik()
    {
        return $this->belongsTo(Obrik::class, 'obrik_id');
    }

    public function lhp()
    {
        return $this->belongsTo(Lhp::class, 'lhp_id');
    }

    public function temuan()
    {
        return $this->belongsTo(Temuans::class, 'temuan_id');
    }

    public function penanggung()
    {
        return $this->belongsTo(PenanggungJawabs::class, 'temuan_id', 'temuan_id');
    }
}
