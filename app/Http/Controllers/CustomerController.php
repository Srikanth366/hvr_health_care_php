<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShippedMail;
use App\Mail\WelcomeEmail;
use App\Models\hvr_doctors;
use App\Models\favorite;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Storage;
use App\Models\PushNotification;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Providers\FirebaseServiceProvider;
use App\Facades\FirebaseAuth;

//require_once 'vendor/autoload.php'; 
require_once app_path('helpers.php');

class CustomerController extends Controller
{

    function generateFCMToken($serviceAccountPath)
{
    // Load the service account JSON file
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

    $now_seconds = time();
    $exp_seconds = $now_seconds + (60 * 60); // Token valid for 1 hour

    // Create the payload
    $payload = array(
        "iss" => $serviceAccount['client_email'],
        "sub" => $serviceAccount['client_email'],
        "aud" => "https://oauth2.googleapis.com/token",
        "iat" => $now_seconds,
        "exp" => $exp_seconds,
        "scope" => "https://www.googleapis.com/auth/firebase.messaging"
    );

    // Encode the JWT
    $jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');

    // Get the OAuth 2.0 token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $response = json_decode($result, true);
    return $response['access_token'];
}

    public function SendPushNotification($newinsertingId,$role){

        $serviceAccountPath = env('FirebasePAth');
        $authBearerToken = $this->generateFCMToken($serviceAccountPath);

        $adminUsers = User::where('roles', 'Admin')->get();
        $user = User::find($newinsertingId);
        if ($user) {
            $firebaseUserId = $user->FbUserID;
        } else {
            $firebaseUserId = '';
        }

        if ($adminUsers->isEmpty()) {
            return response()->json(['message' => 'No admin users found'], 404);
        } else {

            $FMProjetID = env('ProjetID');
            foreach($adminUsers as $admins){
                $unames = $admins->name;
                $FbUserID = $admins->FbUserID;
                $FbToken = $admins->FbToken;
                $FBAuth = 'Bearer '.$authBearerToken;

                $ptitle = "Dear ".$unames." Great News!";
                $pmessage = "A New User Just Registered with us";

$notificationTitle = "Registration Alert";
$notificationBody = "A New User Just Registered with us";
$role = "User Registration";
$userId = ".$newinsertingId.";
//$firebaseUserId = "q55qcFxbwjcNjpbvBSywhedxDyw1";
$type = "profile";
$androidTitle = "Dear ".$unames." Great News!";
$androidBody = "A New User Just Registered with us";
$androidSound = "default";
$apnsSound = "default";
$apnsPriority = "5";

// Construct the associative array
$data = [
    "message" => [
        "token" => $FbToken,
        "notification" => [
            "title" => $notificationTitle,
            "body" => $notificationBody,
        ],
        "data" => [
            "role" => $role,
            "userId" => $userId,
            "firebaseUserId" => $firebaseUserId,
            "type" => $type,
        ],
        "android" => [
            "priority" => "high",
            "notification" => [
                "title" => $androidTitle,
                "body" => $androidBody,
                "sound" => $androidSound,
            ],
        ],
        "apns" => [
            "payload" => [
                "aps" => [
                    "sound" => $apnsSound,
                    "content-available" => 1,
                ],
            ],
            "headers" => [
                "apns-priority" => $apnsPriority,
            ],
        ],
    ],
];
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/' . $FMProjetID . '/messages:send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $FBAuth,
                        'Content-Type: application/json'
                    ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);

               // if($response){
                    $pushNotification = PushNotification::create([
                        'title' => $ptitle,
                        'message' => $pmessage,
                        'user_id' => $admins->id,
                        'role' => 'Admin',
                        'status' => 0,
                    ]);
                    /* if ($pushNotification) {
                        return response()->json(['message' => 'Push notification created successfully!'], 201);
                    } */
               // }
            }

            return $response;
        }

    }

    public function SaveCredinUser($newinsertingId,$name,$password,$email,$role){

        $user = User::create([
           'id' => $newinsertingId,
           'name' => $name,
           'email' => $email,
           'password' => Hash::make($password),
           'roles' => $role
       ]);

       if ($user) {
               return "sucess";
       } else {
               return "failed";
       }
   }
    public function createCustomer(Request $request)
    {
        try {
            // Validate the request
            $validateUser = Validator::make($request->all(), [
               'first_name' => 'required',
               'last_name' => 'required',
               'email' => 'required|email|unique:customer,email',
               'mobile_number' => 'required|unique:customer,mobile_number',
               'password' => 'required',
               'gender' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

            /* Check User is Exists or not */
            $exists = User::where('email', $request->email)->exists();
            if($exists){
                return response()->json(['status' => false,'message' => 'This email address is already in use. Please provide a different one.'], 500);
            }
            /* Check User is Exists or not end */

            $lastInsertId = User::orderBy('id', 'desc')->first()->id;
            $newinsertingId = $lastInsertId + 1;


            $user = Customers::create([
               'id' => $newinsertingId,
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'email' => $request->email,
               'mobile_number' => $request->mobile_number,
               'profile_photo' => $request->profile_photo ? $request->profile_photo : '0',
               'password' => Hash::make($request->password),
               'gender'=> $request->gender,
           ]);
           
           if ($user) {
            $name = $request->first_name.' '.$request->last_name;

            $result = $this->SaveCredinUser($newinsertingId,$name,$request->password,$request->email,'Customer');

            if ($result == 'failed') {
                $deleteuser = Customers::find($newinsertingId);
                $deleteuser->delete();

                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);

            } else {
            Mail::to($request->email)->send(new WelcomeEmail($request->password,$name,$request->email));
            $pushnotification = $this->SendPushNotification($newinsertingId,'Customer');
                return response()->json([
                    'status' => true,
                    'message' => 'Customer Registered Successfully',
                    'FCMResponse' => $pushnotification
                    //'token' => $user->createToken("API TOKEN")->plainTextToken,
                ], 200);
            }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to save data',
                ], 500);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    function UploadProfile(Request $request){
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
        ], [
            'file.required' => 'Please upload an image.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
            'file.max' => 'The file size must be less than 5MB.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => false,
            'message' => 'Validation error',
            'errors' => implode(', ', $validator->errors()->all())], 422);
        }

        try {
        $customer = Customers::findOrFail($request->id);
        $imagePath = $customer->profile_photo;
        $customer->updated_at  = date('Y-m-d H:i:s');
        $result  = $request->file('file')->storePublicly('customerptofile','public');
        $customer->profile_photo = $result;
        $customer->save();

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            //return response()->json(['message' => 'Image deleted successfully!'], 200);
        }
            return response()->json([
                'status' => true,
                'message' => 'Profile Picture Updated successfully',
                'path' => asset($result),
                'data' => $customer,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }
 
    }

    public function loginUser(Request $request)
    {

        /* $user = Customers::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                

                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.'
                ], 400);
            }

            $token = $user->createToken('my-app-token')->plainTextToken;


            return response()->json([
                'status' => true,
                'message' => 'Customer Logged In Successfully',
                'userid' => $user->id,
                'token' => $token
            ], 201);   */

            /*********************************** */


            $user = User::where('email', $request->email)
            ->where('roles', 'Customer')
            ->first();
           
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
        $user = Customers::find($request->userid);
        $token = $user->createToken('Token Name')->plainTextToken;
        return $token;
    }

    function getProfile($id){
        try {
            $customerdetails = Customers::find($id);

                if (!$customerdetails) {
                    return $this->apiResponse(true, 'No Data found', []);
                }else {
                    return $this->apiResponse(true, 'Success', $customerdetails);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    } 

    function updatProfile(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customer,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('customer')->ignore($request->id),
            ],
            'mobile_number' => [
                'required',
                'numeric',
                Rule::unique('customer')->ignore($request->id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $doctor = Customers::findOrFail($request->id);
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->first_name = $request->first_name;
            $doctor->last_name = $request->last_name;
            $doctor->gender = $request->gender;
            $doctor->email = $request->email;
            $doctor->mobile_number = $request->mobile_number;
            $doctor->save();
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $doctor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }

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
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        $customerData = Customers::where('id', $request->id)->first();
        $customer = User::where('id', $request->id)
        ->where('roles', 'Customer')
        ->first();

        /* return response()->json([
            'errors' => $customer,
        ], 422); */

        
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['status'=> false,'message' => 'Invalid email or password'], 401);
        }

        //$customer->password = bcrypt($request->new_password);
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        $firebaseUser = FirebaseAuth::getUserByEmail($customer->email);
        if ($firebaseUser) {
                FirebaseAuth::updateUser($firebaseUser->uid, ['password' => $request->new_password]);
        }

      /*  $Userslogin->password = bcrypt($request->new_password);
        $Userslogin->save(); */

        return response()->json([
            'status'=> true,
            'message' => 'Password updated successfully',
            'data' => $customerData], 200);
    }

        /**************** Delete Users **************/
        public function destroy($id)
        {
            $userData = User::findOrFail($id);
            
            if(!empty($userData->FbUserID)){
                $FireaseUserID = $userData->FbUserID;
                $response = $this->FirebaseUserID($FireaseUserID);
            }

            return response()->json([
                'status' => true,
                'message' => 'The delete option is currently disabled. If you need this feature, please contact support.',
            ], 404);

            DB::beginTransaction();
            try {
              if($userData){
                $userDeleted = User::where('id', $id)->delete();
                $customerDeleted = Customers::where('id', $id)->delete();
                if ($userDeleted && $customerDeleted) {
                    DB::commit();
                    return response()->json(['message' => 'Record deleted successfully'], 200);
                } else {
                    DB::rollBack();
                    return response()->json(['message' => 'Record not found or could not be deleted'], 404);
                }
              } else {
                return response()->json(['message' => 'Record not found or could not be deleted'], 404);
              }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
            }
        }

        public function FirebaseUserID($FireaseUserID){
            try {

                FirebaseAuth::deleteUser($FireaseUserID);
                return response()->json(['message' => 'User deleted successfully from Firebase'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
            }
        }

        /************* Delete Users ****************/

    public function AllUsersResetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try{
            $customerData = Customers::where('email', $request->email)->first();
            $customer = User::where('email', $request->email)
                 ->where('roles', 'Customer')
                 ->first();
            if ($customer) {

            $randomPassword = generateRandomPassword(10);
            $customer->password = Hash::make($randomPassword);
            $name = $customerData->first_name.' '.$customerData->last_name;
            Mail::to($request->email)->send(new OrderShippedMail($randomPassword,$name));
            $customer->save();

            /* $Userslogin->password = Hash::make($randomPassword);
            $Userslogin->save(); */

            // Update password in Firebase
            $firebaseUser = FirebaseAuth::getUserByEmail($request->email);
            if ($firebaseUser) {
                FirebaseAuth::updateUser($firebaseUser->uid, ['password' => $randomPassword]);
            }


            if (Mail::failures()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email failed. Please try again later!',
                    //'data' =>['passcode'=>$randomPassword]
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Your new password has been successfully sent to your email address.',
                   // 'password' => $randomPassword
                ], 200);
            }
            //return response()->json(['password' => $randomPassword,"name"=> $name], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Email, please enter registered email',
                ], 200);
            }
        }catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred, Please try again later!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function apiResponse($status, $message, $data = [], $error = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response);
    }

    function SaveCustomerFaverate(Request $request){

        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'customer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        try {
            $doctorDetails = User::find($request->doctor_id);
            $customerDetails = Customers::find($request->customer_id);

            $isFavorite = favorite::where('doctor_id', $request->doctor_id)
                    ->where('customer_id', $request->customer_id)
                    ->exists();

        if ($isFavorite) {
                return response()->json([
                    'status' => false,
                    'message' => 'This user has already been added to your favorites.',
                ], 200);
        }
        else {
            if ($doctorDetails && $customerDetails) {
                
                $favorite = favorite::create([
                    'doctor_id' => $doctorDetails->id,
                    'customer_id' => $customerDetails->id,
                ]);
                
                if ($favorite) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Data Saved to Favorites Successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data is not Saved, Please Try Again',
                    ], 422);
                }

            } else {
                return $this->apiResponse(false, 'Failed, No Data found', []);
            }
        }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }


    function getsavedFavorites($id){
        try {

           /* $favorite_doctors = DB::table('favorites')
            ->join('hvr_doctors', 'favorites.doctor_id', '=', 'hvr_doctors.id')
            ->where('favorites.customer_id', $id)
            ->select('favorites.id as favorite_pk_id','hvr_doctors.id as doctor_id','first_name','last_name','gender','specialist','qualification','expeirence','latitude','longitute','address','profile','profile_photo','profile_status','firebaseUserId')
            ->get(); */

            $favorites = DB::table('favorites')
            ->where('customer_id', $id)
            ->orderBy('id', 'desc')
            ->get();

            $favorite_doctors = [];

            foreach ($favorites as $favorite) {
                $favorite_pk_id = $favorite->id;
                $doctor_id = $favorite->doctor_id;
                $customer_id = $favorite->customer_id;
                $status = $favorite->status;
                $requested_at = $favorite->created_at;
                $status_updated_at = $favorite->updated_at;

                $users = DB::table('users')->where('id', $doctor_id)->limit(1)->get();
                $userName = $users->first()->name;
                $userRoles = $users->first()->roles;
                $userEmail = $users->first()->email;
                $FbUserID = $users->first()->FbUserID;

                if($userRoles == 'Doctor'){
                    $favoritesusersData = DB::select('SELECT expeirence as experience, latitude, longitute as longitude, address,profile_photo  FROM hvr_doctors WHERE id = ?', [$doctor_id]);
                } else if($userRoles == 'Hospital'){
                    $favoritesusersData = DB::select('SELECT h.latitude, h.longitude, h.registered_address as address, h.experience, h.logo as profile_photo FROM hospitals as h WHERE id = ?', [$doctor_id]);
                } else if($userRoles == 'Pharmacy'){
                    $favoritesusersData = DB::select('SELECT h.latitude, h.longitude, h.registered_address as address, h.experience, h.logo as profile_photo FROM pharmacy as h WHERE id = ?', [$doctor_id]);
                } else if($userRoles == 'Diagnositcs'){
                    $favoritesusersData = DB::select('SELECT h.latitude, h.longitude, h.registered_address as address, h.experience, h.logo as profile_photo FROM diagnositcs as h WHERE id = ?', [$doctor_id]); 
                }

                if (!empty($favoritesusersData)) {
                    $latitude = $favoritesusersData[0]->latitude;
                    $longitude = $favoritesusersData[0]->longitude;
                    $address = $favoritesusersData[0]->address;
                    $experience = $favoritesusersData[0]->experience;
                    $profile_photo = $favoritesusersData[0]->profile_photo;

                    $favorite_doctors[] = [
                        'favorite_pk_id' => $favorite_pk_id,
                        'doctor_id' => $doctor_id,
                        'customer_id' => $customer_id,
                        'favoriteName' => $userName,
                        'favoriteRole' => $userRoles,
                        'favoriteEmail' => $userEmail,
                        'favoriteFbUserID' => $FbUserID,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'address' => $address,
                        'experience' => $experience,
                        'profile_photo' => $profile_photo,
                        'requested_status' => $status,
                        'requested_at' => $requested_at,
                        'status_updated_at' => $status_updated_at
                        
                    ];

                }
            }


            if (!$favorite_doctors) {
                    return $this->apiResponse(true, 'No Data found', []);
            }else {
                    return $this->apiResponse(true, 'Success', $favorite_doctors);
            }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }

    function DeletesavedFavorites($id){

        try {
            $favorite = favorite::findOrFail($id);
            if (!$favorite) {
                return response()->json(['status'=>false,'message' => 'Favorite not found.'], 404);
            } else {
                $favorite->delete();
                return response()->json(['status'=>true, 'message' => 'Favorite deleted successfully.'],200);
            }
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    function getcountry(){
        try {
            $countrylist = Country::all();

                if (!$countrylist) {
                    return $this->apiResponse(true, 'No Data found', []);
                }else {
                    return $this->apiResponse(true, 'Success', $countrylist);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    } 

    /*** MY FAvorites *************/
    function getUserFavorites($id){
        try {

            $favorites = DB::table('favorites')
            ->where('doctor_id', $id)
            ->orderBy('id', 'desc')
            ->get();

            $favorite_doctors = [];

            foreach ($favorites as $favorite) {
                $favorite_pk_id = $favorite->id;
                $doctor_id = $favorite->doctor_id;
                $customer_id = $favorite->customer_id;
                $status = $favorite->status;
                $requested_at = $favorite->created_at;
                $status_updated_at = $favorite->updated_at;
                

                $users = DB::table('users')->where('id', $customer_id)->limit(1)->get();
                $userName = $users->first()->name;
                $userRoles = $users->first()->roles;
                $userEmail = $users->first()->email;
                $FbUserID = $users->first()->FbUserID;

                if($userRoles == 'Customer'){
                    $favoritesusersData = DB::select('SELECT * FROM customer WHERE id = ?', [$customer_id]);
                }

                if (!empty($favoritesusersData)) {
                   // $latitude = $favoritesusersData[0]->latitude;

                    $favorite_doctors[] = [
                        'favorite_pk_id' => $favorite_pk_id,
                        'doctor_id' => $doctor_id,
                        'customer_id' => $customer_id,
                        'favoriteName' => $userName,
                        'favoriteRole' => $userRoles,
                        'favoriteEmail' => $userEmail,
                        'favoriteFbUserID' => $FbUserID,
                        //'mobile_number' => $favoritesusersData[0]->mobile_number,
                        'profile_photo' => $favoritesusersData[0]->profile_photo,
                        'requested_status' => $status,
                        'requested_at' => $requested_at,
                        'status_updated_at' => $status_updated_at
                    ];

                }
            }

            if (!$favorite_doctors) {
                    return $this->apiResponse(true, 'No Data found', []);
            }else {
                    return $this->apiResponse(true, 'Success', $favorite_doctors);
            }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }
    /*********** MY FAvorites ********/

    
}