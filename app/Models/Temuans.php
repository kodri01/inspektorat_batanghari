<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temuans extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'wilayah_id',
        'obrik_id',
        'lhp_id',
        'jns_pemeriksaan',
        'ringkasan',
        'nilai_temuan',
        'jns_temuan',
        'status',
    ];

    public function obrik()
    {
        return $this->belongsTo(Obrik::class, 'obrik_id');
    }

    public function lhp()
    {
        return $this->belongsTo(Lhp::class, 'lhp_id');
    }

    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasies::class, 'temuan_id', 'id');
    }

    public function penanggungJawabs()
    {
        return $this->hasMany(PenanggungJawabs::class, 'temuan_id', 'id');
    }

    public function tindakan()
    {
        return $this->hasMany(TindakLanjut::class, 'temuan_id', 'id');
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('tahun', $year);
    }
}
