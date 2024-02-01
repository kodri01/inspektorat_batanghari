<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obrik extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wilayah_id',
        'name',
        'jenis',
        'kecamatan',
    ];

    public function temuan()
    {
        return $this->hasMany(Temuans::class, 'obrik_id');
    }

    public function tindakan()
    {
        return $this->hasMany(TindakLanjut::class, 'obrik_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}
