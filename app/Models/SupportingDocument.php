<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportingDocument extends Model
{
    use HasFactory;

    protected $table = 'manage_files';

    protected $fillable = [
        'case_id',
        'file_name',
        'uploaded_by',
        'access',
        'mediator_access'
    ];

}
