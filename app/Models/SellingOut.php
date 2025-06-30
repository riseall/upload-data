<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingOut extends Model
{
    use HasFactory;

    protected $table = 'selling_outs';

    protected $fillable = [
        'nama_produk',
        'jumlah_terjual',
        'tanggal_jual',
    ];
    protected $casts = [
        'tanggal_jual' => 'datetime',
    ];

    protected $guarded = [];
}
