<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkLog extends Model
{
    use HasFactory;
    protected $table = "bulk_log";
    public $timestamps = false;
    protected $fillable = ["total_row", "selected_ids", "uploaded_by", "inserted_row", "failed_row", "issue", "log_type", "updated_at"];
}
