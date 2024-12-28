<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'tarif',
        'saldo_akhir',
        'waktu_transaksi',
    ];

    public $timestamps = false;
}
