<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetails extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function productive(){
        return $this->belongsTo(Productive::class,'productive_id');
    }

}
