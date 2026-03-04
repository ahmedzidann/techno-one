<?php

namespace App\Models;

use App\Enum\PurchaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'status' => PurchaseStatus::class
    ];
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
