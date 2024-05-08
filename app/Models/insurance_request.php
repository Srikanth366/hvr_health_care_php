<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class insurance_request extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'insurance_requests';
    protected $primarykey = 'id';

    protected $unique = [''];

    protected $fillable   = ['name','gender','email','mobile','city','address','pincode','description','customer_id'];

    protected $hidden = [''];
       
    public $incrementing = false; 

    protected $casts = [];
}
