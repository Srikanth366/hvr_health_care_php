<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatrequest extends Model
{
    use HasFactory;

    protected $table = 'chatrequests';
    protected $fillable = ['doctor_type','patientID','doctorID','status','notes'];
    public $incrementing = true; 

    protected $hidden = [];

    protected $casts = [];
}
