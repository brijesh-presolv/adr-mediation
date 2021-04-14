<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediators_mediation_cases_status extends Model {

    use HasFactory;

    protected $table = 'mediators_mediation_cases_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mediator_id',
        'mediation_case_id',
        'status',
        'user_type'
    ];

}
