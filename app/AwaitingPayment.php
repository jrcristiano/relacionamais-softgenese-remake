<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwaitingPayment extends Model
{
    protected $table = 'awaiting_payments';
    protected $fillable = [
        'awaiting_payment_award_id',
        'awaiting_payment_file'
    ];
}
