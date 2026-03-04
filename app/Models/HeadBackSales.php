<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadBackSales extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'products_ids' => 'array', // Automatically handles JSON to array conversion
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function invoice_sale()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
}
