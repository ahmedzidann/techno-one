<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadBackPurchases extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'products_ids' => 'array',
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function invoice_purchase()
    {
        return $this->belongsTo(Purchases::class, 'purchase_id');
    }
}
