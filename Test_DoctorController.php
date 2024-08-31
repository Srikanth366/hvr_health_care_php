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


require_once app_path('helpers.php');

class DoctorController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            // Validate the request
            $validateUser = Validator::make($request->all(), [
               'first_name' => 'required',
               'last_name' => 'required',
               'gender' => 'required',
               'email' => 'required|email|unique:hvr_doctors,email',
               'phone' => 'required|unique:hvr_doctors,phone',
               'qualification' => 'required',
               'expeirence' => 'required',
               'latitude' => 'required',
               'longitute' => 'required',
               'address' => 'required',
               'profile' => 'required',
               'password' => 'required',
               'specialist' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            // Create a new Doctor instance
            $user = hvr_doctors::create([
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'gender' => $request->gender,
               'email' => $request->email,
               'phone' => $request->phone,
               'qualification' => $request->qualification,
               'expeirence' => $request->expeirence,
               'latitude' => $request->latitude,
               'longitute' => $request->longitute,
               'address' => $request->address,
               'profile' => $request->profile,
               'profile_photo' => $request->profile_photo ? $request->profile_photo : '0',
               'password' => Hash::make($request->password),
               'specialist' => $request->specialist
           ]);
           
           if ($user) {
            $name = $request->first_name.' '.$request->last_name;
            Mail::to($request->email)->send(new WelcomeEmail($request->password,$name,$request->email));
            return response()->json([
                'status' => true,
                'message' => 'Doctor Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

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
     */

     public function loginUser(Request $request)
    {

        $user = hvr_doctors::where('email', $request->email)->first();

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
    }

    function generateToken(Request $request)
    {
        $user = hvr_doctors::find($request->userid);
        $token = $user->createToken('Token Name')->plainTextToken;
        return $token;
    }

    function getActivedoctors(){
        try {
            $doctors = hvr_doctors::where('profile_status', 1)->orderBy("id")->get();

            if ($doctors->isEmpty()) {
                return $this->apiResponse(true, 'No Data found.', []);
            }

            return $this->apiResponse(true, 'Success', $doctors);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    function getNotActivedoctors(){
        try {
            $doctors = hvr_doctors::where('profile_status', 0)->orderBy("id")->get();

            if ($doctors->isEmpty()) {
                return $this->apiResponse(true, 'No Data found', []);
            }

            return $this->apiResponse(true, 'Success', $doctors);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    function getDoctorProfile($id){
        try {
            $doctorDetails = hvr_doctors::find($id);

                if (!$doctorDetails) {
                    return $this->apiResponse(true, 'No Data found', []);
                }else {
                    return $this->apiResponse(true, 'Success', $doctorDetails);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }

    function updateDoctorStatus(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:hvr_doctors,id',
            'profile_status' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $doctor = hvr_doctors::findOrFail($request->id);
            $doctor->profile_status = $request->profile_status;
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->save();
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
            //'phone' => 'required|unique:hvr_doctors,phone',
            'qualification' => 'required',
            'expeirence' => 'required',
            'latitude' => 'required',
            'longitute' => 'required',
            'address' => 'required',
            'profile' => 'required',
            'specialist' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $doctor = hvr_doctors::findOrFail($request->id);
            $doctor->updated_at  = date('Y-m-d H:i:s');
            $doctor->first_name = $request->first_name;
            $doctor->last_name = $request->last_name;
            $doctor->gender = $request->gender;
            //$doctor->email = $request->email;
            //$doctor->phone = $request->phone;
            $doctor->qualification = $request->qualification;
            $doctor->expeirence = $request->expeirence;
            $doctor->latitude = $request->latitude;
            $doctor->longitute = $request->longitute;
            $doctor->address = $request->address;
            $doctor->profile = $request->profile;
            $doctor->specialist = $request->specialist;
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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
        ], [
            'file.required' => 'Please upload an image.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
            'file.max' => 'The file size must be less than 5MB.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
        $doctor = hvr_doctors::findOrFail($request->id);
        $doctor->updated_at  = date('Y-m-d H:i:s');
        //$result  = $request->file('file')->store('ptofilephoto');
        $result  = $request->file('file')->storePublicly('ptofilephoto','public');

        $doctor->profile_photo = $result;

        $doctor->save();
            return response()->json([
                'status' => true,
                'message' => 'Profile Picture Updated successfully',
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

    public function getDoctorsWithinRadius(Request $request)
    {
        try {

            $rules = [
                'latitude' => 'required|numeric',
                'longitute' => 'required|numeric',
                'specialist' => 'required|string',
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
                                         'message' => 'Validation Failed.',
                                          'errors' => $validator->errors()], 422);
            }

        $latitude = $request->input('latitude');
        $longitute = $request->input('longitute');
        $specialist = $request->input('specialist');
        $radius = 5; //$request->input('radius'); // in kilometers

        // Calculate distance using Haversine formula
        $doctors = hvr_doctors::selectRaw("
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
            ->get();

            if ($doctors->count() > 0) {
                $message = 'Retrieved doctors within '. $radius.'km radius.';
            } else {
                $message = 'No Records Found within '. $radius.'km radius.';
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
                'message' => 'Please enter valid email.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try{
            $customer = hvr_doctors::where('email', $request->email)->first();
            
            if ($customer) {

            $randomPassword = generateRandomPassword(10);
            $customer->password = Hash::make($randomPassword);
            $name = $customer->first_name.' '.$customer->last_name;
            Mail::to($request->email)->send(new OrderShippedMail($randomPassword,$name));
            $customer->save();
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

        $customer = hvr_doctors::where('id', $request->id)->first();
        
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
