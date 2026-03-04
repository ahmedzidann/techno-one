<?php

namespace App\Models;

use App\Enum\DeficitType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAdjustment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Productive::class, 'product_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }
}
