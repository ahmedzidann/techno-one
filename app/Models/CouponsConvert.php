<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponsConvert extends Model
{
    use HasFactory;
    protected $table = 'coupons_converts';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'notes',
        'invoice_number',
        'publisher',
        'converted_at',
        'type_insert',
        'status'
    ];


    public function toUser()
    {
        return $this->belongsTo(Client::class, 'to_user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(Client::class, 'from_user_id');
    }
}
