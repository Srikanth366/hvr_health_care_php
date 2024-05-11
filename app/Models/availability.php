<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class availability extends Model
{
    use HasFactory;

    protected $fillable = ['user_type','user_id','start_time','end_time','day_of_week'];
    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];
}
