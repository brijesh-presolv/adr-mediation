<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SetNotification extends Model
{
    use HasFactory;

     protected $table='set_notification';
    public $timestamps = false;
    protected $guarded = [];



}