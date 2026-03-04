<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function purchases()
    {
        return $this->belongsTo(Purchases::class, 'purchases_id');
    }

    public function productive()
    {
        return $this->belongsTo(Productive::class, 'productive_id');
    }
}
