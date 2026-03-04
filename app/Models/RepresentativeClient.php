<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentativeClient extends Model
{
    protected $fillable = [
        'representative_id',
        'client_id',
    ];
}
