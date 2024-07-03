<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\hvr_doctors;
use App\Models\User;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShippedMail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\upload_images_documents;
use App\Models\Specialists;
use App\Models\appcategoryconfig;
use App\Models\availability;
use App\Models\hospital;
use App\Models\Diagnositcs;
use App\Models\Pharmacy;
use App\Models\favorite;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\WorkingHour;
use App\Models\PushNotification;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Providers\FirebaseServiceProvider;
use App\Facades\FirebaseAuth;

 

require_once app_path('helpers.php');

class DoctorController extends Controller
{
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
    public function createUserBackup(Request $request)
    {
        try {
            // Validate the request
            $validateUser = Validator::make($request->all(), [
               'first_name' => 'required',
               'last_name' => 'required',
               'gender' => 'required',
               'email' => 'required|email|unique:hvr_doctors,email',
               'phone' => 'required',
               'qualification' => 'required',
               'expeirence' => 'required',
               'latitude' => 'required',
               'longitute' => 'required',
               'address' => 'required',
               'profile' => 'required',
               'password' => 'required',
               'specialist' => 'required',
               'NMC_Registration_NO' => 'required'
            ]);

            

            if ($validateUser->fails()) {
                
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

            $lastInsertId = User::orderBy('id', 'desc')->first()->id;
            $newinsertingId = $lastInsertId + 1;


                $user = hvr_doctors::create([
               'id' => $newinsertingId,
               'first_name' => $request->first_name ? $request->first_name : 'Guest',
               'last_name' => $request->last_name ? $request->last_name : 'User',
               'gender' => $request->gender ? $request->gender : 'Male',
               'email' => $request->email,
               'phone' => $request->phone ? $request->phone : rand(1000000000, 9999999999),
               'qualification' => $request->qualification ? $request->qualification : 'Not Specified',
               'expeirence' => $request->expeirence ? $request->expeirence : 'None',
               'latitude' => $request->latitude ? $request->latitude : '1',
               'longitute' => $request->longitute ? $request->longitute : '1.0',
               'address' => $request->address ? $request->address : 'None',
               'profile' => $request->profile ? $request->profile : 'About',
               'profile_photo' => $request->profile_photo ? $request->profile_photo : '0',
               'password' => Hash::make($request->password) ? Hash::make($request->password) : Hash::make('Sree@1234'),
               'specialist' => $request->specialist ? $request->specialist : '1',
               'NMC_Registration_NO' => $request->NMC_Registration_NO ? $request->NMC_Registration_NO : '0'
           ]);
           
           if ($user) {
            $name = $request->first_name.' '.$request->last_name;

            $result = $this->SaveCredinUser($newinsertingId,$name,$request->password,$request->email,'Doctor');

            if ($result == 'failed') {
                $deleteuser = hvr_doctors::find($newinsertingId);
                $deleteuser->delete();

                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);

            } else {
                Mail::to($request->email)->send(new WelcomeEmail($request->password,$name,$request->email));
                return response()->json([
                    'status' => true,
                    'message' => 'Doctor Created Successfully',
                   // 'token' => $user->createToken("API TOKEN")->plainTextToken
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

         /**
     * Login The User
     * @param Request $request
     * @return hvr_doctors
     * @return User
     */

     public function loginUser(Request $request)
    {  
        try {

        /* $user = hvr_doctors::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.'
                ], 400);
            }
            $token = $user->createToken('my-app-token')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Doctor Logged In Successfully',
                'userid' => $user->id,
                'token' => $token
            ], 201);
        */ 

        //$hvr_doctors = hvr_doctors::where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();
        
           
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                ], 404);
            } else {

                if($user->roles == "Customer")
                {
                    $userData = Customers::where('email', $request->email)->first();
                } else if($user->roles == "Doctor") {
                    $userData = hvr_doctors::where('email', $request->email)->first();
                } else if($user->roles == "Hospital") {
                    $userData = hospital::where('email', $request->email)->first();
                } else if($user->roles == "Diagnositcs") {
                    $userData = Diagnositcs::where('email', $request->email)->first();
                } else if($user->roles == "Pharmacy"){
                    $userData = Pharmacy::where('email', $request->email)->first();
                }
                else {
                    $userData = $user;
                }
            }

            if ($userData) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        //'user' => $userData,
                        'userid' => $user->id,
                        'username' => $user->name,
                        'useremail' => $user->email,
                        'roles' => $user->roles,
                        'fbid' => $user->FbUserID,
                        'token' => $user->createToken('my-app-token')->plainTextToken
                    ], 200);
            } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong, Please try again!',
                    ], 403);
            }


        }catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        } 
        
    }

    function generateToken(Request $request)
    {
        $user = hvr_doctors::find($request->userid);
        $token = $user->createToken('Token Name')->plainTextToken;
        return $token;
    }

    function getActivedoctors(){
        try {
            //$doctors = hvr_doctors::where('profile_status', 1)->orderBy("id")->get();
            $doctors = hvr_doctors::orderBy('id', 'desc')->get();

            if ($doctors->isEmpty()) {
                return $this->apiResponse(true, 'No Data found.', []);
            }

            return $this->apiResponse(true, 'Success', $doctors);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

   /* function getNotActivedoctors(){
        try {
            $doctors = hvr_doctors::where('profile_status', 0)->orderBy("id")->get();

            if ($doctors->isEmpty()) {
                return $this->apiResponse(true, 'No Data found', []);
            }

            return $this->apiResponse(true, 'Success', $doctors);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    } */

    function getDoctorProfile($id){
        try {
            $doctorDetails = hvr_doctors::find($id);

                if (!$doctorDetails) {
                    return $this->apiResponse(false, 'No Data found', []);
                }else {
                    $specialitys = explode(",", $doctorDetails->specialist);
                    $doctorSpecialities = Specialists::query()->whereIn('id', $specialitys)->get();
                    //$workingHours = availability::where('user_id', $id)->get();
                    $workingHours  = WorkingHour::where('user_id', $id)->get();


                   $response = [
                    'status' => true,
                    'message' => 'Success',
                    'data' => $doctorDetails,
                    'speciality'  => $doctorSpecialities,
                    'WorkingHours' => $workingHours,
                ];
        
                return response()->json($response);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }

    function updateDoctorStatusData(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'profile_status' => 'required|string',
            'role'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {

            if($request->role == 'Doctor') {
            $doctor = hvr_doctors::findOrFail($request->id);
            $doctor->profile_status = $request->profile_status;
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->save();
            } else if($request->role == 'Hospital') {
                $doctor = hospital::findOrFail($request->id);
                $doctor->status = $request->profile_status;
                $doctor->save();

            } else if($request->role == 'Diagnositcs') {
                $doctor = Diagnositcs::findOrFail($request->id);
                $doctor->status = $request->profile_status;
                $doctor->save();

            } else if($request->role == 'Pharmacy') {
                $doctor = Pharmacy::findOrFail($request->id);
                $doctor->status = $request->profile_status;
                $doctor->save();

            } else if($request->role == 'Customer') {
                $doctor = Customers::findOrFail($request->id);
                $doctor->status = $request->profile_status;
                $doctor->save();
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Data passed, Please try Again!',
                ], 403);
            }
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully',
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

    function updateDoctorProfile(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:hvr_doctors,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            //'email' => 'required|email|unique:hvr_doctors,email',
            'phone' => [
                'required',
                'numeric',
                Rule::unique('hvr_doctors')->ignore($request->id),
            ],
            'qualification' => 'required',
            'expeirence' => 'required',
            'latitude' => 'required',
            'longitute' => 'required',
            'address' => 'required',
            'profile' => 'required',
            'specialist' => 'required',
            'NMC_Registration_NO' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $doctor = hvr_doctors::findOrFail($request->id);
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->first_name = $request->first_name;
            $doctor->last_name = $request->last_name;
            $doctor->gender = $request->gender;
            //$doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->qualification = $request->qualification;
            $doctor->expeirence = $request->expeirence;
            $doctor->latitude = $request->latitude;
            $doctor->longitute = $request->longitute;
            $doctor->address = $request->address;
            $doctor->profile = $request->profile;
            $doctor->specialist = $request->specialist;
            $doctor->NMC_Registration_NO = $request->NMC_Registration_NO;
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

    function UploadProfile(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id'=> 'required',
            'role' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
        ], [
            'file.required' => 'Please upload an image.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
            'file.max' => 'The file size must be less than 5MB.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status'=> false,
                        'message' => 'Validation error',
                        'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
        //$result  = $request->file('file')->store('ptofilephoto');

        if($request->role == 'Hospital'){
            $profileData = hospital::find($request->id);
            $imagePath = $profileData->logo;

            if($profileData){
                $result  = $request->file('file')->storePublicly('ptofilephoto','public');
                $profileData->logo = $result;
                $profileData->save();
            } else {
                return response()->json(['status' => false,'message' => 'Failed, Please Pass Valid data',], 400);
            }

        } else if($request->role == 'Doctor'){
            $profileData = hvr_doctors::findOrFail($request->id);
            $imagePath = $profileData->profile_photo;
            if($profileData){
                $result  = $request->file('file')->storePublicly('ptofilephoto','public');
                $profileData->profile_photo = $result;
                $profileData->save();
            } else {
                return response()->json(['status' => false,'message' => 'Failed, Please Pass Valid data',], 400);
            }
        } else if($request->role == 'Diagnositcs'){
            $profileData = Diagnositcs::findOrFail($request->id);
            $imagePath = $profileData->logo;
            if($profileData){
                $result  = $request->file('file')->storePublicly('ptofilephoto','public');
                $profileData->logo = $result;
                $profileData->save();
            } else {
                return response()->json(['status' => false,'message' => 'Failed, Please Pass Valid data',], 400);
            }
        } else if($request->role == 'Customer'){
            $profileData = Customers::findOrFail($request->id);
            $imagePath = $profileData->profile_photo;
            if($profileData){
                $result  = $request->file('file')->storePublicly('ptofilephoto','public');
                $profileData->profile_photo = $result;
                $profileData->save();
            } else {
                return response()->json(['status' => false,'message' => 'Failed, Please Pass Valid data',], 400);
            }

        } else if($request->role == 'Pharmacy'){
            $profileData = Pharmacy::findOrFail($request->id);
            $imagePath = $profileData->logo;
            if($profileData){
                $result  = $request->file('file')->storePublicly('ptofilephoto','public');
                $profileData->logo = $result;
                $profileData->save();
            } else {
                return response()->json(['status' => false,'message' => 'Failed, Please Pass Valid data',], 400);
            }
        } else {
            return response()->json(['status' => false,'message' => 'Failed, please pass valid data',], 400);
        }

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            //return response()->json(['message' => 'Image deleted successfully!'], 200);
        }
            return response()->json([
                'status' => true,
                'message' => 'Profile Picture Updated successfully',
                'data' => $profileData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }
 
    }

    public function getDoctorsWithinRadius(Request $request)
    {
        try {

            $rules = [
                'latitude' => 'required|numeric',
                'longitute' => 'required|numeric',
                'specialist' => 'required|string',
                'customer_id'  => 'required',
                //'radius' => 'required|numeric',
            ];
            
            $messages = [
                'latitude.required' => 'Latitude is required.',
                'latitude.numeric' => 'Latitude must be a number.',
                'longitute.required' => 'Longitude is required.',
                'longitute.numeric' => 'Longitude must be a number.',
                'specialist.required' => 'Specialist field is required.',
                'specialist.string' => 'Specialist must be a string.',
                'radius.required' => 'Radius is required.',
                'radius.numeric' => 'Radius must be a number.',
            ];
            

            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json(['status' => true,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())], 422);
            }

        $latitude = $request->input('latitude');
        $longitute = $request->input('longitute');
        $specialist = $request->input('specialist');
        $radius = 10; //$request->input('radius'); // in kilometers

        // Calculate distance using Haversine formula
         /* $doctors = hvr_doctors::selectRaw("
            *, 
            ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) )
            * cos( radians( longitute ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( latitude ) ) )
            ) AS distance", [$latitude, $longitute, $latitude])
            //->where('specialist', $specialist)
            ->whereRaw("CONCAT(',', specialist, ',') LIKE '%,$specialist,%'")
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get(); */

            $doctors = hvr_doctors::selectRaw("
            id,first_name,last_name,gender,specialist,qualification,expeirence,latitude,longitute,address,profile,profile_photo,profile_status,firebaseUserId,
            ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) )
            * cos( radians( longitute ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( latitude ) ) )
            ) AS distance", [$latitude, $longitute, $latitude])
            ->where('profile_status', '=', 1)
            ->whereRaw("CONCAT(',', specialist, ',') LIKE '%,$specialist,%'")
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();

            if ($doctors->count() > 0) {
                    foreach($doctors as $doctor) {
                        $doctorid =  $doctor['id'];
                        $customer_id = $request->customer_id;
                        
                        $favoriteDoctors = favorite::where('customer_id', $customer_id)
                        ->where('doctor_id', $doctorid)
                        ->first();

                        if($favoriteDoctors){
                            $is_favorite = 1;
                            $favorite_id   = $favoriteDoctors->id;
                        } else{
                            $is_favorite = 0;
                            $favorite_id = 0;
                        }
                    $doctor['is_favorite'] = $is_favorite;
                    $doctor['favorite_id'] = $favorite_id;  

                    }
                $message = 'Retrieved doctors within '. $radius.'km radius.';
            } else {
                $message = 'No Active Doctors Found within '. $radius.'km radius.';
            }    

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $doctors,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
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
           // $customerData = hvr_doctors::where('email', $request->email)->first();
            $customer = User::where('email', $request->email)->orderBy('id', 'desc')->first();

            if ($customer) {

            $randomPassword = generateRandomPassword(10);
            $customer->password = Hash::make($randomPassword);
           // $name = $customerData->first_name.' '.$customerData->last_name;
            $name = $customer->name;
            $customer->save();
            Mail::to($request->email)->send(new OrderShippedMail($randomPassword,$name));

           /* $Userslogin->password = Hash::make($randomPassword);
            $Userslogin->save(); */

            $firebaseUser = FirebaseAuth::getUserByEmail($request->email);
            if ($firebaseUser) {
                FirebaseAuth::updateUser($firebaseUser->uid, ['password' => $randomPassword]);
            }

            if (Mail::failures()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email failed. Use the following password for login.',
                    'data' =>['passcode'=>$randomPassword]
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Your new password has been successfully sent to your email address.',
                    'data' => 'Doctor User'
                ], 200);
            }
            
            } else {
                  $response =   $this->validateUserEmail($request->email);
                return response()->json($response, 200);
            }
        }catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred, Please try again later!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validateUserEmail($email){

        $customer = User::where('email', $email)->first();

            if ($customer) {
            $randomPassword = generateRandomPassword(10);
            $customer->password = Hash::make($randomPassword);
            $name = $customer->name;
            Mail::to($email)->send(new OrderShippedMail($randomPassword,$name));
            $customer->save();
            if (Mail::failures()) {
                return [
                    'status' => false,
                    'message' => 'Email failed. Please try again later!',
                ];
            } else {
                return [
                    'status' => true,
                    'message' => 'Your new password has been successfully sent to your email address.',
                   // 'password' => $randomPassword
                ];
            }
            } else {
                return [
                    'status' => false,
                    'message' => 'Invalid Email, please enter registered email',
                ];
            }
            
    }

    public function logoutuser(Request $request)
    {
        if ($request !== null || !empty($request)) {

           /* if($request->usertype == 'Customer'){
               $request->Customers()->currentAccessToken()->delete();
            } else if($request->usertype == 'Doctor'){
                $request->hvr_doctors()->currentAccessToken()->delete();
            } else {
                $request->user()->currentAccessToken()->delete();
            } */
           $request->user()->currentAccessToken()->delete();
         
           $HTTP_RAW_POST_DATA = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ];

            return response()->json(['status' => true,'message' => 'Successfully logged out','data'=>$HTTP_RAW_POST_DATA],200);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong!','data'=>$HTTP_RAW_POST_DATA],200);
        }
    }

    public function customerchangePassword(Request $request)
    {
        try {
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

        //$DoctorData = hvr_doctors::where('id', $request->id)->first();
        $customer = User::where('id', $request->id)->first();


        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['status'=> false,'message' => 'Please enter valid old password'], 401);
        }

        $customer->password = bcrypt($request->new_password);
        $customer->save();

       /* $Userslogin->password = bcrypt($request->new_password);
        $Userslogin->save(); */

        return response()->json([
            'status'=> true,
            'message' => 'Password updated successfully'], 200);

        }catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred, Please try again later!',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function ViewUploadedDocuments($id){
        try {

            $documents = upload_images_documents::where('uploaded_user_id', $id)->get();


                if (!$documents) {
                    return $this->apiResponse(false, 'No Data found', []);
                }else {
                   $response = [
                    'status' => true,
                    'message' => 'Success',
                    'data' => $documents,
                ];
        
                return response()->json($response);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    public function GetBanners($id = null){
        try {

            if($id == null){
                $documents = upload_images_documents::where('document_type', 'Banner')->orderBy('id', 'desc')->get();
            } else {
                $documents = upload_images_documents::where('document_type', 'Banner')->orderBy('id', 'desc')->first();
            }


                if (!$documents) {
                    return $this->apiResponse(false, 'No Data found', []);
                }else {
                   $response = [
                    'status' => true,
                    'message' => 'Success',
                    'data' => $documents,
                ];
        
                return response()->json($response);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    public function uploadDocuments(Request $request){

        if($request->document_type == 'IMAGE' || $request->document_type == 'Banner') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
            ], [
                'file.required' => 'Please upload an image.',
                'file.image' => 'The file must be an image.',
                'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]);
        } else if($request->document_type == 'CSV') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|mimes:csv,excel,xls,xlsx,doc,docx,dot,dotx,rtf,odt|max:5120', // 50MB max size (5120 KB)
            ], [
                'file.required' => 'Please upload a CSV / Excel / Word file.',
                'file.mimes' => 'Only CSV and Excel files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]);
        } else if($request->document_type == 'PDF') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|mimes:pdf|max:51200', // 50MB max size (5120 KB)
            ], [
                'file.required' => 'Please upload a PDF file.',
                'file.mimes' => 'Only PDF files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]); 
        } else if($request->document_type == 'VIDEOLINK') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:VIDEOLINK', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|url'
            ], [
                'file.required' => 'Please enter a YouTube video URL.',
                'file.url' => 'Please enter a valid URL.',
                'file.regex' => 'Please enter a valid YouTube video URL.'
            ]); 
        } else {
            return response()->json(['status'=> false,'message' => 'Please share valid data',], 422);
        }
    
        if ($validator->fails()) {
            return response()->json(['status'=> false,
                        'message' => 'Validation error',
                        'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
        
        if($request->document_type == 'VIDEOLINK'){
            $result = $request->file;
            $message = 'Video link updated successfully!';
        } else {
            $result  = $request->file('file')->storePublicly('documents','public');
            $message = 'Document Uploaded successfully';
        }

        $uploadDocument = new upload_images_documents();
        $uploadDocument->document_url = $result;
        $uploadDocument->document_type = $request->document_type;
        $uploadDocument->uploaded_user_id = $request->user_id;
        $uploadDocument->uploaded_user_type = $request->user_type;
        $uploadDocument->file_name =  $request->file_name;
        $uploadDocument->save();

        if($uploadDocument){
        $uploadDocument->save();
            return response()->json([
                'status' => true,
                'message' => $message,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please Try Again',
            ], 500);
        }
        

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteDocuments($id){
        try {

            $imageData = upload_images_documents::find($id);
            
        if($imageData) {
                $imagePath = $imageData->document_url;
                $imageData->delete();

                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    //return response()->json(['message' => 'Image deleted successfully!'], 200);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Record deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Data passed',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createUser(Request $request)
    {
        try {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
            return response()->json(['status' => false,'message' => 'Email already registered'], 400);
            }

            $existingMobile = hvr_doctors::where('phone', $request->phone)->first();
            if ($existingMobile) {
            return response()->json(['status' => false,'message' => 'Mobile Number already registered'], 400);
            }

            $validateUser = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'email' => 'required|email|unique:hvr_doctors,email',
                'phone' => 'required',
                'qualification' => 'required',
                'expeirence' => 'required',
                'latitude' => 'required',
                'longitute' => 'required',
                'address' => 'required',
                'profile' => 'required',
                'password' => 'required',
                'specialist' => 'required',
                'NMC_Registration_NO' => 'required'
             ]);
 
             if ($validateUser->fails()) {
                 
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }
 
            $lastInsertId = User::orderBy('id', 'desc')->first()->id;
            $newinsertingId = $lastInsertId + 1;
            $roles  = 'Doctor';
            $name = $request->first_name.' '.$request->last_name;
            $user = User::create([
                'id'=> $newinsertingId,
                'name' => $name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => $roles
            ]);

            if ($user) {
                //$result  = $request->file('logo')->storePublicly('ptofilephoto','public');
                $docotrs = hvr_doctors::create([
                    'id' => $newinsertingId,
                    'first_name' => $request->first_name ? $request->first_name : 'Guest',
                    'last_name' => $request->last_name ? $request->last_name : 'User',
                    'gender' => $request->gender ? $request->gender : 'Male',
                    'email' => $request->email,
                    'phone' => $request->phone ? $request->phone : rand(1000000000, 9999999999),
                    'qualification' => $request->qualification ? $request->qualification : 'Not Specified',
                    'expeirence' => $request->expeirence ? $request->expeirence : 'None',
                    'latitude' => $request->latitude ? $request->latitude : '1',
                    'longitute' => $request->longitute ? $request->longitute : '1.0',
                    'address' => $request->address ? $request->address : 'None',
                    'profile' => $request->profile ? $request->profile : 'About',
                    'profile_photo' => $request->profile_photo ? $request->profile_photo : '0',
                    'password' => Hash::make($request->password) ? Hash::make($request->password) : Hash::make('Sree@1234'),
                    'specialist' => $request->specialist ? $request->specialist : '1',
                    'NMC_Registration_NO' => $request->NMC_Registration_NO ? $request->NMC_Registration_NO : '0'
                ]);

                    if($docotrs){
                        $categoryarray = explode(",", $request->specialist);
                        foreach ($categoryarray as $cat) {
                            appcategoryconfig::create([
                            'user_type' => $roles,
                            'user_id' => $newinsertingId,
                            'category_id' => $cat
                            ]);
                        }
                    $name = $request->first_name.' '.$request->last_name;
                    Mail::to($request->email)->send(new WelcomeEmail($request->password,$name,$request->email));
                    $pushnotification = $this->SendPushNotification($newinsertingId,'Doctor');
                    return response()->json([
                    'status' => true,
                    'message' => 'Successfully Registered',
                    'FCMResponse' => $pushnotification
                    ], 200);  

                    } else {
                        User::destroy($newinsertingId);
                        return response()->json([
                            'status' => false,
                            'message' => 'Something went wrong, Please Try Again!',
                        ], 500);
                    }

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);
            }
        }catch (\Exception $e) {
            User::destroy($newinsertingId);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function UpdateFirebaseToken(Request $request){
        
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
            'firebaseUserId' => 'required|string',
            'firebaseUserToken' => 'required|string',
            'firebaseAuthId' => 'required|string',
            'role'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {

            $doctor = User::findOrFail($request->userId);
            $doctor->FbUserID = $request->firebaseUserId;
            $doctor->FbToken = $request->firebaseUserToken;
            $doctor->FBAuth = $request->firebaseAuthId;
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->save();

            if($request->role == 'Doctor') {
            $doctor = hvr_doctors::findOrFail($request->userId);
            $doctor->firebaseUserId = $request->firebaseUserId;
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->save();
            } else if($request->role == 'Hospital') {
                $doctor = hospital::findOrFail($request->userId);
                $doctor->firebaseUserId = $request->firebaseUserId;
                $doctor->save();

            } else if($request->role == 'Diagnositcs') {
                $doctor = Diagnositcs::findOrFail($request->userId);
                $doctor->firebaseUserId = $request->firebaseUserId;
                $doctor->save();

            } else if($request->role == 'Pharmacy') {
                $doctor = Pharmacy::findOrFail($request->userId);
                $doctor->firebaseUserId = $request->firebaseUserId;
                $doctor->save();

            } else if($request->role == 'Customer') {
                $doctor = Customers::findOrFail($request->userId);
                $doctor->firebaseUserId = $request->firebaseUserId;
                $doctor->save();
            } else if($request->role == 'Admin') {
                $doctor = User::findOrFail($request->userId);
                $doctor->FbUserID = $request->firebaseUserId;
                $doctor->save();
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Data passed, Please try Again!',
                ], 403);
            }
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully',
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
                    $pmessage = "A new doctor just registered with us. Please verify and approve.";
    
    $notificationTitle = "Registration Alert";
    $notificationBody = "A new doctor just registered with us. Please verify and approve.";
    $role = "User Registration";
    $userId = ".$newinsertingId.";
    //$firebaseUserId = "q55qcFxbwjcNjpbvBSywhedxDyw1";
    $type = "profile";
    $androidTitle = "Dear ".$unames." Great News!";
    $androidBody = "A new doctor just registered with us. Please verify and approve.";
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
    
                        $pushNotification = PushNotification::create([
                            'title' => $ptitle,
                            'message' => $pmessage,
                            'user_id' => $admins->id,
                            'role' => 'Admin',
                            'status' => 0,
                            'customer_id' => $newinsertingId
                        ]);
                        
                }
    
                return $response;
            }
    
        }

        public function updateSPushnotificationtatus($id)
        {
            try {
            $affected = PushNotification::where('id', $id)->update(['status' => '1']);

            if ($affected) {
                return response()->json(['status' => true,'message' => 'Record updated successfully.'],200);
            } else {
                return response()->json(['status' => false,'message' => 'Failed to update record.'], 500);
            }

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while updating the data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        public function GetPushNotification($id)
        {
            try {
                $data = PushNotification::where('user_id', $id)->get();
                if ($data->isEmpty()) {
                    return response()->json(['status' => false,'message' => 'No records found.'], 404);
                }

                return response()->json(['status' => true,'message' => 'success','data'=>$data], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while updating the data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }


        function generateFCMToken($serviceAccountPath)
        {

            $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
            $now_seconds = time();
            $exp_seconds = $now_seconds + (60 * 60);
            $payload = array(
                "iss" => $serviceAccount['client_email'],
                "sub" => $serviceAccount['client_email'],
                "aud" => "https://oauth2.googleapis.com/token",
                "iat" => $now_seconds,
                "exp" => $exp_seconds,
                "scope" => "https://www.googleapis.com/auth/firebase.messaging"
            );
            $jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');
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

        public function GenerateFirebaseToken()
        {
            $serviceAccountPath = env('FirebasePAth');
            $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
            $now_seconds = time();
            $exp_seconds = $now_seconds + (60 * 60);
            $payload = array(
                "iss" => $serviceAccount['client_email'],
                "sub" => $serviceAccount['client_email'],
                "aud" => "https://oauth2.googleapis.com/token",
                "iat" => $now_seconds,
                "exp" => $exp_seconds,
                "scope" => "https://www.googleapis.com/auth/firebase.messaging"
            );
            $jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');
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
            //return $response['access_token'];
            return response()->json([
                'status' => true,
                'message' => 'Token Generated Suceessfully',
                'data' => $response,
            ], 200);
        }
}
