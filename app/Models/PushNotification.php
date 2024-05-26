<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $table = 'push_notifications';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'title',
        'message',
        'user_id',
        'status',
        'role'
    ];
}
