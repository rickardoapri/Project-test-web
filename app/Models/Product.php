<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'gambar',
        'kategori_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    use HasFactory;
}
