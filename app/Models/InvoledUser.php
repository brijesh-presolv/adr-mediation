<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoledUser extends Model
{
    use HasFactory;

     protected $table = 'user_involved_in_agreement';

     protected $fillable = ['userId', 'userEmail', 'userPhone', 'userPlanId', 'joinCode', 'username', 'fulladdress', 'address1', 'address2', 'city', 'pincode', 'state', 'country', 'name', 'isClaimant', 'isOnboarded', 'created_at', 'updated_at'];

}
