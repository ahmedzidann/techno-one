<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destruction extends Model
{
    use HasFactory;
    protected $table='destruction';
    protected $guarded=[];
    public function storage(){
        return $this->belongsTo(Storage::class,'storage_id');
    }
}
