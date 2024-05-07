<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function routeError(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'internal server error',
        ], 500); 
    }
}
