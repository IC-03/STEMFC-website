<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Payment extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'month',
        'amount_to_pay',
        'amount_paid',
        'balance',
        'payment_date',
		'payment_option',
        // ...
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    protected $casts = [
        'month' => 'string',
        'payment_date' => 'date',
        'uuid' => 'string',
    ];
}
