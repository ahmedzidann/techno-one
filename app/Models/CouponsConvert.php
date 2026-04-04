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
        'pay_method',
        'notes',
        'invoice_number',
        'publisher',
        'converted_at',
        'type_insert',
        'status',
        'invoice_value',
        'date'
    ];


    public function toUser()
    {
        return $this->belongsTo(Client::class, 'to_user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(Client::class, 'from_user_id');
    }
    public function payMethod()
    {
        return $this->belongsTo(ClientPaymentSetting::class, 'pay_method');
    }
}
