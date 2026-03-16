<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviewProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'preview_category_id',
        'name',
        'description',
        'points',
        'price',
        'publisher',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(PreviewCategory::class,'preview_category_id');
    }
}