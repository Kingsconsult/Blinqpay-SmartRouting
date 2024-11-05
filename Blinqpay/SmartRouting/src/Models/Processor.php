<?php

namespace Blinqpay\SmartRouting\Models;

use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    protected $fillable = ['name', 'cost', 'reliability', 'currency_support', 'country_support', 'status', 'adapter_class'];

    protected $casts = [
        'currency_support' => 'array',
        'country_support' => 'array',
        'status' => 'boolean',
    ];
}
