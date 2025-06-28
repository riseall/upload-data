<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otif extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_pesanan' => 'datetime', 
        'tanggal_kirim' => 'datetime', 
    ];

    protected $guarded = [];
}
