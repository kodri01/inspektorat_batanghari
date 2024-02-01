<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenanggungJawabs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'temuan_id',
        'obrik_id',
        'nilai_obrik',
        'name',
        'nip',
        'nilai',
    ];
}
