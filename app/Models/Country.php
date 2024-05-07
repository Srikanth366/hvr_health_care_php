<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Country extends Model
{
    use HasFactory;

    use HasApiTokens, HasFactory;

    protected $table = 'country';
    protected $primarykey = 'id';

    protected $unique = [];

    protected $fillable   = ['id','country_name','country_code','dialing_code'];

    protected $hidden = [];
       
    public $incrementing = false; 
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];
}
