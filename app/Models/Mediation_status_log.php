<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediation_status_log extends Model
{
    use HasFactory;
    const STATUS_NEW_REQUEST= 0;
    const STATUS_ACCEPTE_BY_ADMIN= 1;
    const STATUS_REJECT_BY_ADMIN= 2;
    const STATUS_ACCEPTE_BY_MEDIATOR= 3;
    const STATUS_REJECT_BY_MEDIATOR= 4;
    const STATUS_WITHDRAWN= 5;
    const STATUS_RESOLVED= 6;
    const STATUS_UNRESOLVED= 7;
}
