<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaOfSpecialization extends Model
{
    use HasFactory;
    protected $table ="area_of_specialization";
    protected $fillable = ["name,id"];
}
