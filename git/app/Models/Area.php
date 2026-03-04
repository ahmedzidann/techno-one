<?php

namespace App\Models;

use App\Enum\AreaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'type' => AreaType::class,
    ];

    public function country()
    {
        return $this->belongsTo(Area::class, 'from_id');
    }
}
