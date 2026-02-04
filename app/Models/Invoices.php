<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'profit',
        'buyer_name',
        'buyer_phone',
        'sold_date',
        'total_bill',
        'invoice_id',
        'stock_id',
    ];
}
