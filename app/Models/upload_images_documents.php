<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class upload_images_documents extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'upload_images_documents';
    protected $primarykey = 'id';

    protected $unique = [];

    protected $fillable   = ['document_url','document_type','uploaded_user_id','uploaded_user_type'];

    protected $hidden = [];

    public $incrementing = false;  
                
    protected $casts = [];
}
