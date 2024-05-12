<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Diagnositcs extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['id',
        'diagnostics_name',
        'owner_name','gender',
        'email',
        'mobile',
        'accrediations_NABL',
        'Category',
        'licence_number',
        'latitude',
        'longitude',
        'experience','profile_description','registered_address','logo'
    ];

    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];
}
