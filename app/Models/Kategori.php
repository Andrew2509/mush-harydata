<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the pembelians (purchases) associated with this kategori.
     * Links via layanan name containing the category name.
     */
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'kategori_id');
    }
}
