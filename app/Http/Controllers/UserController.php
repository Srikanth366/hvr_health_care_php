<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Customers;
use App\Models\hvr_doctors;


class UserController extends Controller
{
    
    function index(Request $request)
    {
        
        //$userId = 3;
        //$user = User::find($userId);
        //$token = $user->createToken('Token Name')->plainTextToken;
        //return $token;
        $user= User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'status' => false,
                    'message' => 'These credentials do not match our records.'
                ], 404);
            } else {

                if($user->roles == "Customer")
                {
                    $userData = Customers::where('email', $request->email)->first();
                } else if($user->roles == "Doctor") {
                    $userData = hvr_doctors::where('email', $request->email)->first();
                } else {
                    $userData = $user;
                }
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $userData,
                'userid' => $user->id,
                'token' => $token
            ];
        
             return response($response, 201);
    }

    function generateToken(Request $request)
    {
        $user = User::find($request->userid);
        $token = $user->createToken('Token Name')->plainTextToken;
        return $token;
    }

    public function customerchangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials, Please use valid userid and password.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer = User::where('id', $request->id)->first();
        
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['status'=> false,'message' => 'Invalid email or password'], 401);
        }

        $customer->password = bcrypt($request->new_password);
        $customer->save();

        return response()->json([
            'status'=> true,
            'message' => 'Password updated successfully',
            'data' => $customer], 200);
    }


}
