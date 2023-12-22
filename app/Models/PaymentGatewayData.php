<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentGatewayData extends Model
{
    use HasFactory;
    protected $table = 'payment_gateway_data';
}