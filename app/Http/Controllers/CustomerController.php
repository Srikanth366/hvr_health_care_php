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
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use App\Models\hvr_doctors;
use App\Models\favorite;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Country;

require_once app_path('helpers.php');

class CustomerController extends Controller
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
                return response()->json([
                    'status' => true,
                    'message' => 'Customer Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
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
        $customer->updated_at  = date('Y-m-d H:i:s');
        $result  = $request->file('file')->storePublicly('customerptofile','public');

        $customer->profile_photo = $result;

        $customer->save();
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

        $customer->password = bcrypt($request->new_password);
        $customer->save();

      /*  $Userslogin->password = bcrypt($request->new_password);
        $Userslogin->save(); */

        return response()->json([
            'status'=> true,
            'message' => 'Password updated successfully',
            'data' => $customerData], 200);

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
            $doctorDetails = hvr_doctors::find($request->doctor_id);
            $customerDetails = Customers::find($request->customer_id);

            $isFavorite = favorite::where('doctor_id', $request->doctor_id)
                    ->where('customer_id', $request->customer_id)
                    ->exists();

        if ($isFavorite) {
                return response()->json([
                    'status' => false,
                    'message' => 'Doctor was already added in the favorites',
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

            $favorite_doctors = DB::table('favorites')
            ->join('hvr_doctors', 'favorites.doctor_id', '=', 'hvr_doctors.id')
            ->where('favorites.customer_id', $id)
            ->select('favorites.id as favorite_pk_id','hvr_doctors.id as doctor_id','first_name','last_name','gender','specialist','qualification','expeirence','latitude','longitute','address','profile','profile_photo','profile_status')
            ->get();

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

    
}
