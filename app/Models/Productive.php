<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productive extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'productive';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unite::class, 'unit_id');
    }
    public function credit()
    {
        return $this->hasMany(RasiedAyni::class, 'productive_id')->orderBy('branch_id', 'DESC');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function shape()
    {
        return $this->belongsTo(Shape::class, 'shape_id');
    }
    public function batches()
    {
        return $this->hasMany(PurchasesDetails::class, 'productive_id')
            ->select(['id', 'productive_id', 'batch_number']);
    }

}
