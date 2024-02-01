<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lhp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahun',
        'no_lhp',
        'tgl_lhp',
        'judul',
        'upload',
    ];

    public function temuan()
    {
        return $this->hasMany(Temuans::class, 'lhp_id');
    }

    public function tindakan()
    {
        return $this->hasMany(TindakLanjut::class, 'lhp_id');
    }
}
