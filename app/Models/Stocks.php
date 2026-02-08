<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;
     protected $fillable = [
        'id',
        'model_name',
        'company_name',
        'imei1',
        'imei2',
        'ram',
        'rom',
        'pta_status',
        'activation_status',
        'country_status',
        'health',
        'type',
        'status',
        'purchase',
        'sale',
        'created_at',
        'updated_at',

        'pushasing_from_name',
        'pushasing_from_phone',
        'pushasing_from_cnic',
        'pushasing_from_address',
        'dealer_id',
        'purchasing_from',
    ];
}
