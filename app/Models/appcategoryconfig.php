<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appcategoryconfig extends Model
{
    use HasFactory;

    protected $fillable = ['user_type','category_id','user_id'];
}
