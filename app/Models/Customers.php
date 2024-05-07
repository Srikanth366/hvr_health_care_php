<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customers extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'customer';
    protected $primarykey = 'id';

    protected $unique = ['email', 'mobile_number'];

    protected $fillable   = ['id','first_name',
                            'last_name',
                            'email',
                            'mobile_number',
                            'profile_photo','gender',
                            'password'];

    protected $hidden = ['password'];
       
    public $incrementing = false; 
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['password' => 'string'];

}