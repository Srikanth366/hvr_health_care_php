<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FirebaseAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'firebase.auth';
    }
    
}
