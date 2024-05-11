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
use App\Models\upload_images_documents;
use App\Models\Specialists;
use App\Models\hospital;
use App\Models\appcategoryconfig;
use App\Models\availability;

class HospitalsController extends Controller
{
    function gethospitalslist($id = null){
        try {
            
            if($id == null){
            $hospitals = hospital::join('appcategoryconfigs', 'hospitals.id', '=', 'appcategoryconfigs.user_id')
                        ->join('specialists', 'appcategoryconfigs.category_id', '=', 'specialists.id')
                        ->where('appcategoryconfigs.user_type', 'Hospital')
                        ->groupBy('hospitals.id')
                        ->select('hospitals.*', DB::raw('group_concat(specialists.speciality) as specialities'))
                        ->get();
            } else {
                $hospitals = hospital::join('appcategoryconfigs', 'hospitals.id', '=', 'appcategoryconfigs.user_id')
                        ->join('specialists', 'appcategoryconfigs.category_id', '=', 'specialists.id')
                        ->where('appcategoryconfigs.user_type', 'Hospital')
                        ->where('hospitals.id', $id)
                        ->groupBy('hospitals.id')
                        ->select('hospitals.*', DB::raw('group_concat(specialists.speciality) as specialities'))
                        ->get();
            }

            if ($hospitals->isEmpty()) {
                return $this->apiResponse(true, 'No Data found.', []);
            }

            return $this->apiResponse(true, 'Success', $hospitals);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    public function createhospitals(Request $request){

        try {

            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
            return response()->json(['status' => false,'message' => 'Email already registered'], 400);
            }

            $validateUser = Validator::make($request->all(), [
               'hospital_name' => 'required',
               'director_name' => 'required',
               'email' => 'required|email|unique:hospitals,email',
               'hospital_contact_number' => 'required',
               'emergency_number' => 'required',
               'category' => 'required',
               'dmho_licence_number' => 'required',
               'latitude' => 'required',
               'longitude' => 'required',
               'accrediations' => 'required',
               'experience' => 'required',
               'profile_description' => 'required',
               'registered_address' => 'required',
               'password' => 'required',
               'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ], [
                'logo.required' => 'Please upload an image.',
                'logo.image' => 'The file must be an image.',
                'logo.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
                'logo.max' => 'The file size must be less than 5MB.',
            ]);

            if ($validateUser->fails()) {
                
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

            $roles  = 'Hospital';
            $lastInsertId = User::orderBy('id', 'desc')->first()->id;
            $newinsertingId = $lastInsertId + 1;
            $user = User::create([
                'id'=> $newinsertingId,
                'name' => $request->hospital_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => $roles
            ]);

            if ($user) {
                $result  = $request->file('logo')->storePublicly('ptofilephoto','public');
                $hospital = hospital::create([
               'id' => $newinsertingId,
               'hospital_name' => $request->hospital_name ? $request->hospital_name : 'Guest',
               'director_name' => $request->director_name ? $request->director_name : 'Guest',
               'email' => $request->email ? $request->email : '',
               'hospital_contact_number' => $request->hospital_contact_number ? $request->hospital_contact_number : '',
               'emergency_number' => $request->emergency_number ? $request->emergency_number : '',
               'category' => $request->category ? $request->category : '',
               'dmho_licence_number' => $request->dmho_licence_number ? $request->dmho_licence_number : '',
               'latitude' => $request->latitude ? $request->latitude : '',
               'longitude' => $request->longitude ? $request->longitude : '',
               'accrediations' => $request->accrediations ? $request->accrediations : '',
               'experience' => $request->experience ? $request->experience : '',
               'profile_description' => $request->profile_description ? $request->profile_description : '',
               'registered_address' => $request->registered_address ? $request->registered_address : '',
               'logo' => $result ? $result : ''
           ]);

                    if($hospital){

                        $categoryarray = explode(",", $request->category);
                        foreach ($categoryarray as $cat) {
                            appcategoryconfig::create([
                            'user_type' => $roles,
                            'user_id' => $newinsertingId,
                            'category_id' => $cat
                            ]);
                        }

                    Mail::to($request->email)->send(new WelcomeEmail($request->password,$request->hospital_name,$request->email));
                    return response()->json([
                    'status' => true,
                    'message' => 'Hospital Created Successfully',
                    ], 200);  

                    } else {
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
           
          /* 
            $name = $request->first_name.' '.$request->last_name;
                
         */
            

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /*
    Update Hospitals Data
    */
    public function updatehospitals(Request $request){
        try {
            $validateUser = Validator::make($request->all(), [
               'hospital_name' => 'required',
               'director_name' => 'required',
               //'email' => 'required|email|unique:hospitals,email',
               'hospital_contact_number' => 'required',
               'emergency_number' => 'required',
               'category' => 'required',
               'dmho_licence_number' => 'required',
               'latitude' => 'required',
               'longitude' => 'required',
               'accrediations' => 'required',
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

                $hospitalData = hospital::findOrFail($request->id);
                $hospitalData->hospital_name = $request->hospital_name;
                $hospitalData->director_name = $request->director_name;
                $hospitalData->hospital_contact_number = $request->hospital_contact_number;
                $hospitalData->emergency_number = $request->emergency_number;
                $hospitalData->category = $request->category;
                $hospitalData->dmho_licence_number = $request->dmho_licence_number;
                $hospitalData->latitude = $request->latitude;
                $hospitalData->longitude = $request->longitude;
                $hospitalData->accrediations = $request->accrediations;
                $hospitalData->experience = $request->experience;
                $hospitalData->profile_description = $request->profile_description;
                $hospitalData->registered_address = $request->registered_address;
                $hospitalData->save();

                    if($hospitalData){
                        $roles  = 'Hospital';
                        appcategoryconfig::where('user_id', $request->id)->delete();
                        $categoryarray = explode(",", $request->category);
                        foreach ($categoryarray as $cat) {
                            appcategoryconfig::create([
                            'user_type' => $roles,
                            'user_id' => $request->id,
                            'category_id' => $cat
                            ]);
                        }

                    return response()->json([
                    'status' => true,
                    'message' => 'Hospital Data Updated Successfully',
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

    public function SetAppointmentslot(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'user_type' => 'required',
                'user_id' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'day_of_week' => 'required'
             ]);

             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }
             
             $hospitalWorkingHours = availability::create([
                'user_type' => $request->user_type,
                'user_id' => $request->user_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,
            ]);

            if($hospitalWorkingHours){
            return response()->json([
            'status' => true,
            'message' => 'Working Hours Updated Successfully',
            ], 200);  

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function viewWorkingHours($id){
        try {
            if($id){
                $hospitalWorkingHours = availability::where('user_id', $id)->get();

                if ($hospitalWorkingHours->isEmpty()) {
                    return $this->apiResponse(true, 'No Data found.', []);
                }

                return $this->apiResponse(true, 'Success', $hospitalWorkingHours);
            } else {
                return $this->apiResponse(false, 'Please pass valid data', []);
            }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
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