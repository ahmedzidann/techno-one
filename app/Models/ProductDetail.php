<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',        // علاقة بالمنتج
        'size', 
        'price', 
        'pieces_at_packet'
    ];
    public function product()
{
    return $this->belongsTo(Product::class);
}
}
