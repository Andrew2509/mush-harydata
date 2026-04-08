<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'no_pembeli',
        'jumlah',
        'layanan',
        'status',
        'claimed_by',
    ];
}
