<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class favorite extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'favorites';
    protected $primarykey = 'id';

    protected $unique = ['email', 'phone'];

    protected $fillable   = ['doctor_id','customer_id'];

    protected $hidden = [];

}