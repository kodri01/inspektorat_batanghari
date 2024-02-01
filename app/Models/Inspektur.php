<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspektur extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'wilayah_id',
        'name',
        'nip',
        'pangkat_gol',
    ];
}
