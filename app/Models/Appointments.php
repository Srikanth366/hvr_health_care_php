<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_type','PatientID','DoctorID','AppointmentDate','AppointmentTime','Notes','name','age','PatientMobile'];
    public $incrementing = false; 

    protected $hidden = [];

    protected $casts = [];
}
