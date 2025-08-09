<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{
    public $timestamps = false; //-- use for models without timestamps
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_account_number', 'customer_account_number');
    }
}
