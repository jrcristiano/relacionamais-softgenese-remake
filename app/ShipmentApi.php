<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentApi extends Model
{
    protected $table = 'shipments_api';
    protected $fillable = [
        'shipment_award_id',
        'shipment_last_field',
        'shipment_generated',
        'shipment_file',
        'shipment_file_vinc'
    ];
}
