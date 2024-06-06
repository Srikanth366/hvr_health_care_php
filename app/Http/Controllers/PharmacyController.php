<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShippedMail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Pharmacy;
use App\Models\appcategoryconfig;
use App\Models\availability;
use App\Models\Appointments;
use App\Models\Appointment_history;
use App\Models\Specialists;
use App\Models\WorkingHour;


class PharmacyController extends Controller
{
    function getpharmacylist($id = null){
        try {

            if($id == null){
                 $Diagnositcs = Pharmacy::orderBy("id","desc")->get();
                 if (!$Diagnositcs) {
                     return $this->apiResponse(false, 'No Data found.', []);
                 }
                 foreach ($Diagnositcs as $Diagnositc){
                     $appconfig = appcategoryconfig::where("user_id", $Diagnositc->id)->pluck('category_id')->implode(',');
                     $categoryIds = explode(',', $appconfig);
                     $specialityNames = Specialists::whereIn('id', $categoryIds)->pluck('speciality')->toArray();
                     $Diagnositc['specialities'] = implode(', ', $specialityNames);
                     $WorkingHours = "";
                 }
            } else {
                 $Diagnositcs = Pharmacy::where('id', $id)->first();
                 if (!$Diagnositcs) {
                     return $this->apiResponse(false, 'No Data found.', []);
                 } else {
                     $appconfig = appcategoryconfig::where("user_id", $Diagnositcs->id)->pluck('category_id')->implode(',');
                     $categoryIds = explode(',', $appconfig);
                     $specialityNames = Specialists::whereIn('id', $categoryIds)->pluck('speciality')->toArray();
                     $Diagnositcs['specialities'] = implode(', ', $specialityNames);
                    // $WorkingHours = availability::where('user_id', $id)->get();
                     $WorkingHours  = WorkingHour::where('user_id', $id)->get();
                 }
             
           }

           return $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $Diagnositcs,
            'WorkingHours' => $WorkingHours];

             //return $this->apiResponse(true, 'Success', $Diagnositcs);
             }catch (\Exception $e) {
                 return $this->apiResponse(false, 'Failed', [], $e->getMessage());
             }
    }

    function CreatePharmacy(Request $request){
        try {

            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
            return response()->json(['status' => false,'message' => 'Email already registered'], 400);
            }

            $validateUser = Validator::make($request->all(), [
               'pharmacy_name' => 'required',
               'pharmacist_name' => 'required',
               'email' => 'required|email|unique:pharmacy,email',
               'mobile' => 'required',
               'gender' => 'required',
               'Category' => 'required',
               //'accrediations_NABL' => 'required',
               'latitude' => 'required',
               'longitude' => 'required',
               'drug_licence_number' => 'required',
               'experience' => 'required',
               'profile_description' => 'required',
               'registered_address' => 'required',
               'password' => 'required',
              // 'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ], [
               // 'logo.required' => 'Please upload an image.',
               // 'logo.image' => 'The file must be an image.',
               // 'logo.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
               // 'logo.max' => 'The file size must be less than 5MB.',
            ]);

            if ($validateUser->fails()) {
                
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

           

            $roles  = 'Pharmacy';
            $lastInsertId = User::orderBy('id', 'desc')->first()->id;
            $newinsertingId = $lastInsertId + 1;
            $user = User::create([
                'id'=> $newinsertingId,
                'name' => $request->pharmacy_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => $roles
            ]);

            if ($user) {
               // $result  = $request->file('logo')->storePublicly('ptofilephoto','public');
                $diagnostics = Pharmacy::create([
               'id' => $newinsertingId,
               'pharmacy_name' => $request->pharmacy_name ? $request->pharmacy_name : 'Guest',
               'pharmacist_name' => $request->pharmacist_name ? $request->pharmacist_name : 'Guest',
               'gender' => $request->gender ? $request->gender : 'Not Specified',
               'email' => $request->email ? $request->email : '',
               'mobile' => $request->mobile ? $request->mobile : '',
               'Category' => $request->Category ? $request->Category : '',
               'drug_licence_number' => $request->drug_licence_number ? $request->drug_licence_number : '',
               'latitude' => $request->latitude ? $request->latitude : '',
               'longitude' => $request->longitude ? $request->longitude : '',
               //'accrediations_NABL' => $request->accrediations_NABL ? $request->accrediations_NABL : '',
               'experience' => $request->experience ? $request->experience : '',
               'profile_description' => $request->profile_description ? $request->profile_description : '',
               'registered_address' => $request->registered_address ? $request->registered_address : '',
               'logo' => 0
           ]);

                    if($diagnostics){
                        $categoryarray = explode(",", $request->Category);
                        foreach ($categoryarray as $cat) {
                            appcategoryconfig::create([
                            'user_type' => $roles,
                            'user_id' => $newinsertingId,
                            'category_id' => $cat
                            ]);
                        }

                    Mail::to($request->email)->send(new WelcomeEmail($request->password,$request->pharmacist_name,$request->email));
                    return response()->json([
                    'status' => true,
                    'message' => 'Pharmacy Added Successfully',
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

        } catch (\Throwable $th) {
            User::destroy($newinsertingId);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

        /*
    Update Hospitals Data
    */
    public function updatePharmacyData(Request $request){
        try {
            $validateUser = Validator::make($request->all(), [
               'pharmacy_name' => 'required',
               'pharmacist_name' => 'required',
               //'email' => 'required|email|unique:hospitals,email',
               'mobile' => 'required',
               'gender' => 'required',
               'Category' => 'required',
               'drug_licence_number' => 'required',
               'latitude' => 'required',
               'longitude' => 'required',
               'experience' => 'required',
               'profile_description' => 'required',
               'registered_address' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

                $hospitalData = Pharmacy::find($request->id);
                if (!$hospitalData) {
                    return response()->json([
                        'status' => false,'message' => 'Pharmacy data not found'], 400);
                }
                $hospitalData->pharmacy_name = $request->pharmacy_name;
                $hospitalData->pharmacist_name = $request->pharmacist_name;
                $hospitalData->mobile = $request->mobile;
                $hospitalData->gender = $request->gender;
                $hospitalData->Category = $request->Category;
                $hospitalData->drug_licence_number = $request->drug_licence_number;
                $hospitalData->latitude = $request->latitude;
                $hospitalData->longitude = $request->longitude;
                $hospitalData->experience = $request->experience;
                $hospitalData->profile_description = $request->profile_description;
                $hospitalData->registered_address = $request->registered_address;
                $hospitalData->save();

                    if($hospitalData){
                        $roles  = 'Pharmacy';
                        appcategoryconfig::where('user_id', $request->id)->delete();
                        $categoryarray = explode(",", $request->Category);
                        foreach ($categoryarray as $cat) {
                            appcategoryconfig::create([
                            'user_type' => $roles,
                            'user_id' => $request->id,
                            'category_id' => $cat
                            ]);
                        }

                    return response()->json([
                    'status' => true,
                    'message' => 'Data Updated Successfully',
                    ], 200);  

                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Something went wrong, Please Try Again!',
                        ], 500);
                    }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
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
}
