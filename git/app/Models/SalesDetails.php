<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productive()
    {
        return $this->belongsTo(Productive::class, 'productive_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
    public function product()
    {
        return $this->belongsTo(Productive::class, 'productive_id');
    }
}
