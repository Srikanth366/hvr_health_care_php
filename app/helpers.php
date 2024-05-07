<?php 

// File: app/helpers.php

if (!function_exists('in_array_case_insensitive')) {
    /**
     * Case-insensitive check if a value exists in an array.
     *
     * @param  mixed  $needle
     * @param  array  $haystack
     * @return bool
     */
    function in_array_case_insensitive($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    function generateRandomPassword($length = 12) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$&*_?';
        $password = '';
    
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $password;
    }
}


?>