<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $timestamps = false; //-- use for models without timestamps
    protected $guarded = [];

    // Relationship: a customer has many meter readings
    public function meterReadings()
    {
        return $this->hasMany(\App\Models\MeterReadings::class, 'customer_account_number', 'customer_account_number');
    }
}
