<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadBackPurchasesDetails extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function purchases(){
        return $this->belongsTo(HeadBackPurchases::class,'head_back_purchases_id');
    }

    public function productive(){
        return $this->belongsTo(Productive::class,'productive_id');
    }
}
