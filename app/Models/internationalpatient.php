<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class internationalpatient extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'internationalpatients';
    protected $primarykey = 'id';

    protected $unique = [''];

    protected $fillable   = ['name','gender','email','country','mobile_code','mobile','service_request','customer_id'];

    protected $hidden = [''];
       
    public $incrementing = false; 

    protected $casts = [];
}
