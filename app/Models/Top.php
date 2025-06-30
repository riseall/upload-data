<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Top extends Model
{
    use HasFactory;

    protected $table = 'tops';

    protected $fillable = [
        'nama_target',
        'target_value',
        'tanggal_target',
    ];
    protected $casts = [
        'tanggal_target' => 'datetime', // Mengonversi tanggal_target ke tipe date
    ];

    protected $guarded = [];
}
