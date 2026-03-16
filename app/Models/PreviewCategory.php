<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviewCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points',
        'status',
        'image'
    ];

    public function products()
{
    return $this->hasMany(PreviewProduct::class,'preview_category_id');
}
}
