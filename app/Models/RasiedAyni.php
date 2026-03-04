<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RasiedAyni extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $table='rasied_ayni';

    public function productive(){
        return $this->belongsTo(Productive::class,'productive_id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function storage(){
        return $this->belongsTo(Storage::class,'storage_id');
    }
}
