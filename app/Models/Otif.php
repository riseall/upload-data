<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otif extends Model
{
    use HasFactory;

    protected $table = 'otifs';

    protected $fillable = [
        'produk',
        'jumlah_pesanan',
        'jumlah_terkirim',
        'tanggal_pesanan',
        'tanggal_kirim',
    ];
    protected $casts = [
        'tanggal_pesanan' => 'datetime', 
        'tanggal_kirim' => 'datetime', 
    ];

    protected $guarded = [];
}
