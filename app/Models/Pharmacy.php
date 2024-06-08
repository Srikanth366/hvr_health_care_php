<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pharmacy extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "pharmacy";
    protected $fillable = ['id',
        'pharmacy_name',
        'pharmacist_name','gender',
        'email',
        'mobile',
        'Category',
        'drug_licence_number',
        'latitude',
        'longitude',
        'experience','profile_description','registered_address','logo'
    ];


    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}