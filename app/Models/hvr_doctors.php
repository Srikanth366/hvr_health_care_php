<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class hvr_doctors extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'hvr_doctors';
    protected $primarykey = 'id';

    protected $unique = ['email', 'phone'];

    protected $fillable   = ['id','first_name',
                            'last_name',
                            'gender',
                            'email',
                            'phone',
                            'qualification',
                            'expeirence',
                            'latitude',
                            'longitute',
                            'address',
                            'profile',
                            'profile_photo',
                            'password','specialist'];

    protected $hidden = ['password'];

    public $incrementing = false;  
                
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['password' => 'string'];
}
