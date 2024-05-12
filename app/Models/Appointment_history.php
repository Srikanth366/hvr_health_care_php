<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_history extends Model
{
    use HasFactory;

    protected $table = "Appointment_history";

    protected $fillable = ['AppointmentID','requested_user_type','notes','Appointment_status'];
    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];
}
