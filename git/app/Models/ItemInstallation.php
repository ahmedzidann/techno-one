<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInstallation extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function productive(){
        return $this->belongsTo(Productive::class,'productive_id');
    }

}
