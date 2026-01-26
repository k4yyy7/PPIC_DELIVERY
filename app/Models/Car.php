<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'nama_mobil',
        'plat_nomor',
        'gambar',
    ];
}
