<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_id',
        'shipment_status_generated',
        'shipment_cancelled',
        'shipment_file',
    ];
}
