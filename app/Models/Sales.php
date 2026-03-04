<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }
    public function details()
    {
        return $this->hasMany(SalesDetails::class, 'sales_id');
    }
}
