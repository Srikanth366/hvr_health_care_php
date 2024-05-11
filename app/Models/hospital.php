<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class hospital extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['id',
        'hospital_name',
        'director_name',
        'email',
        'hospital_contact_number',
        'emergency_number',
        'category',
        'dmho_licence_number',
        'latitude',
        'longitude',
        'accrediations',
        'experience','profile_description','registered_address','logo'
    ];
    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];
}
