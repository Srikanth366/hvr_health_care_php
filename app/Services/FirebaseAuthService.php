<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class FirebaseAuthService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->auth = $factory->createAuth();
    }

    public function getUserByEmail($email)
    {
        try {
            return $this->auth->getUserByEmail($email);
        } catch (UserNotFound $e) {
            return null;
        }
    }

    public function updateUser($uid, $attributes)
    {
        return $this->auth->updateUser($uid, $attributes);
    }
}
