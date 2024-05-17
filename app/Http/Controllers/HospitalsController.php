<?php

namespace App\Http\Controllers;

use App\Models\hvr_doctors;
use App\Models\Pharmacy;
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
use App\Models\Appointments;
use App\Models\Appointment_history;
use App\Models\Diagnositcs;

class HospitalsController extends Controller
{
    function gethospitalslist($id = null){
        try {
            
           /* $systemrole = 'Hospital';
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

                /* $sql = "select `hospitals`.*, group_concat(specialists.speciality) as specialities from `hospitals` 
                inner join `appcategoryconfigs` on `hospitals`.`id` = `appcategoryconfigs`.`user_id` 
                inner join `specialists` on `appcategoryconfigs`.`category_id` = `specialists`.`id` 
                where `appcategoryconfigs`.`user_type` = 'Hospital' and `hospitals`.`id` = $id
                group by `hospitals`.`id`,`hospitals`.`hospital_name`";   
                
                $hospitals = DB::select($sql); */
           /* } */

           if($id == null){
                $hospitals = hospital::orderBy("id","desc")->get();
                if (!$hospitals) {
                    return $this->apiResponse(false, 'No Data found.', []);
                }
                foreach ($hospitals as $hospital){
                    $appconfig = appcategoryconfig::where("user_id", $hospital->id)->pluck('category_id')->implode(',');
                    $categoryIds = explode(',', $appconfig);
                    $specialityNames = Specialists::whereIn('id', $categoryIds)->pluck('speciality')->toArray();
                    $hospital['specialities'] = implode(', ', $specialityNames);
                    $WorkingHours = "";
                }
           } else {
                $hospitals = hospital::where('id', $id)->first();
                if (!$hospitals) {
                    return $this->apiResponse(false, 'No Data found.', []);
                } else {
                    $appconfig = appcategoryconfig::where("user_id", $hospitals->id)->pluck('category_id')->implode(',');
                    $categoryIds = explode(',', $appconfig);
                    $specialityNames = Specialists::whereIn('id', $categoryIds)->pluck('speciality')->toArray();
                    $hospitals['specialities'] = implode(', ', $specialityNames);
                    $WorkingHours = availability::where('user_id', $id)->get();
                }
            
          }

          return $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $hospitals,
            'WorkingHours' => $WorkingHours];

            //return $this->apiResponse(true, 'Success', $hospitals);
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }
    function GetSpecialityWiseAllUsersdata(Request $request){
        try {
            
            $validateUser = Validator::make($request->all(), [
               'speciality_id' => 'required',
               'user_role' => 'required'
            ]);

            if ($validateUser->fails()) {
                
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }

            $role = $request->user_role;

           if($request->speciality_id == 0){

                if($role == 'Doctor'){
                    $data = hvr_doctors::where('profile_status', 1)->get();
                } else if($role == 'Hospital'){
                    $data = hospital::where('status', 1)->get();
                } else if($role == 'Diagnositcs'){
                    $data = Diagnositcs::where('status', 1)->get();
                } else if($role == 'Pharmacy'){
                    $data = Pharmacy::where('status', 1)->get();
                } else {
                    return $this->apiResponse(false, 'The data provided is invalid.', []);
                }
                return $this->apiResponse(true, 'Success', $data);
                /* $data = ['Doctor' => $doctors, 
                            'Hospital' => $hospital, 
                            'Diagnositcs' => $Diagnositcs, 
                            'Pharmacy' => $Pharmacy]; */ 
                
           } else {
                
            if($role == 'Hospital'){
                    $data = hospital::join('appcategoryconfigs', 'hospitals.id', '=', 'appcategoryconfigs.user_id')
                    ->where('hospitals.status', 1)
                    ->where('appcategoryconfigs.category_id', $request->speciality_id)
                    ->select('hospitals.*')
                    ->get();
            } else if($role == 'Diagnositcs'){
                $data = Diagnositcs::join('appcategoryconfigs', 'diagnositcs.id', '=', 'appcategoryconfigs.user_id')
                    ->where('diagnositcs.status', 1)
                    ->where('appcategoryconfigs.category_id', $request->speciality_id)
                    ->select('diagnositcs.*')
                    ->get();
            } else if($role == 'Pharmacy'){
                $data = Pharmacy::join('appcategoryconfigs', 'pharmacy.id', '=', 'appcategoryconfigs.user_id')
                    ->where('pharmacy.status', 1)
                    ->where('appcategoryconfigs.category_id', $request->speciality_id)
                    ->select('pharmacy.*')
                    ->get();
            } else {
                return $this->apiResponse(false, 'The data provided is invalid.', []);
            }

            if ($data->isEmpty()) {
                return $this->apiResponse(false, 'No matching data found.', []);
            } else {
                return $this->apiResponse(true, 'Success', $data);
            }
        }

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
               // $result  = $request->file('logo')->storePublicly('ptofilephoto','public');
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
               'logo' => 0
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

                    Mail::to($request->email)->send(new WelcomeEmail($request->password,$request->director_name,$request->email));
                    return response()->json([
                    'status' => true,
                    'message' => 'Hospital Created Successfully',
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
           
          /* 
            $name = $request->first_name.' '.$request->last_name;
                
         */
            

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

                $hospitalData = hospital::find($request->id);
                if (!$hospitalData) {
                    return response()->json([
                        'status' => false,'message' => 'Hospital data not found'], 400);
                }
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

    public function SetWorkingHours(Request $request){
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
            'message' => 'Working Hours Added Successfully',
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
                    return $this->apiResponse(false, 'No Data found.', []);
                }

                return $this->apiResponse(true, 'Working hours deleted successfully.', []);
            } else {
                return $this->apiResponse(false, 'Please pass valid data', []);
            }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    public function DeleteWorkingHours($id){
        try {
            if($id){
                $hospitalWorkingHours = availability::find($id);

                if (!$hospitalWorkingHours) {
                    return $this->apiResponse(false, 'Failed, Please try again', []);
                }
                $hospitalWorkingHours->delete();
                return $this->apiResponse(true, 'Success', $hospitalWorkingHours);
            } else {
                return $this->apiResponse(false, 'Unauthorized Access', []);
            }

            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }

    public function BookAppointment(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'doctor_type' => 'required',
                'PatientID' => 'required',
                'DoctorID' => 'required',
                'AppointmentDate' => 'required',
                'AppointmentTime' => 'required',
                'Notes' => 'required',
                'name' => 'required',
                'age' => 'required',
            ]);

             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }
             
             $appoinmentData = Appointments::create([
                'doctor_type' => $request->doctor_type,
                'PatientID' => $request->PatientID,
                'DoctorID' => $request->DoctorID,
                'AppointmentDate' => $request->AppointmentDate,
                'AppointmentTime' => $request->AppointmentTime,
                'Notes' => $request->Notes ? $request->Notes : 'Notes Not Provided.',
                'name' => $request->name,
                'age' => $request->age,
            ]);

            if($appoinmentData){
            return response()->json([
            'status' => true,
            'message' => 'Appointment request received. We will confirm details shortly.',
            ], 200);  

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);
            }

        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    public function ConfirmBookAppointment(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'AppointmentID' => 'required',
                'requested_user_type' => 'required',
                'notes' => 'required',
                'Appointment_status' => 'required',
            ]);

             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

             $appointment = Appointments::find($request->AppointmentID);
             if (!$appointment) {
                 return response()->json(['message' => 'Appointment not found'], 404);
             } else {
                $appointment->status = $request->Appointment_status;
                $appointment->save();
             }
             
             $appoinmentData = Appointment_history::create([
                'AppointmentID' => $request->AppointmentID,
                'requested_user_type' => $request->requested_user_type,
                'notes' => $request->notes,
                'Appointment_status' => $request->Appointment_status
            ]);

            if($appoinmentData){

                return response()->json([
                'status' => true,
                'message' => 'Appointment status updated successfully',
                ], 200);

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please Try Again!',
                ], 500);
            }

        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    public function ViewAppointmentsForDoctor(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required'
            ]);

             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

            
            /* $appointment = Appointments::where('DoctorID', $request->user_id)
                                     ->where('doctor_type', $request->user_type)
                                     ->orderBy('id', 'desc')
                                     ->get(); */

                $appointment = Appointments::where('DoctorID', $request->user_id)
                ->join('customer', 'appointments.PatientID', '=', 'customer.id')
                ->orderBy('appointments.id', 'desc')
                ->get(['appointments.*','customer.id as customer_id','customer.first_name','customer.last_name','customer.email','customer.mobile_number','customer.profile_photo','customer.gender']);


             if($appointment){
                return response()->json(['status'=>true, 'message' => 'Success', 'data'=>$appointment], 200);
             } else {
                return response()->json(['status'=>false, 'message' => 'Appointment not found'], 404);
             }

        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    public function GetAppointmentHistory($AppointmentId){
        try {
                $AppointmentHistory = Appointment_history::find($AppointmentId);

                if (!$AppointmentHistory) {
                    return $this->apiResponse(false, 'No appointment history found.', []);
                } else {
                    return $this->apiResponse(true, 'Success', $AppointmentHistory);
                }

        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    public function ViewAppointmentsToCustomer(Request $request){
        try{
            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required'
            ]);

             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

               /* $appointment = Appointments::where('PatientID', $request->user_id)
                ->join('users', 'appointments.PatientID', '=', 'users.id')
                ->orderBy('appointments.id', 'desc')
                ->get(['appointments.*','users.name' ,'users.email','users.roles']); */

                $appointment = Appointments::where('PatientID', $request->user_id)->get();
                foreach ($appointment as $appointmentData){
                    $UserID = $appointmentData->DoctorID;
                    $user_type = $appointmentData->doctor_type;

                    if($user_type == 'Doctor') {
                        $data = hvr_doctors::where('id', $UserID)->get()->makeHidden(['phone', 'email', 'password','specialist','NMC_Registration_NO']);
                    } else if($user_type == 'Hospital') {
                       // $data = hospital::where('status', 1)->get();
                        $data = hospital::where('id', $UserID)->get()->makeHidden(['hospital_contact_number', 'email', 'password','emergency_number','category','dmho_licence_number']);
                    } else if($user_type == 'Diagnositcs') {
                       // $data = Diagnositcs::where('status', 1)->get();
                        $data = Diagnositcs::where('id', $UserID)->get()->makeHidden(['phone', 'email', 'password','Category','licence_number']);
                    } else if($user_type == 'Pharmacy') {
                       // $data = Pharmacy::where('status', 1)->get();
                        $data = Pharmacy::where('id', $UserID)->get()->makeHidden(['mobile', 'email', 'password','Category','drug_licence_number']);
                    } else {
                        $data = "";
                    }

                    $appointmentData['user_data'] = $data;
                    //$appointmentData['is_favorite'] = '1';
                    //$appointmentData['favorite_id'] = '2'; 
                }


             if($appointment){
                return response()->json(['status'=>true, 'message' => 'Success', 'data'=>$appointment], 200);
             } else {
                return response()->json(['status'=>false, 'message' => 'Appointment not found'], 404);
             }
        }    
        catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    /* public function GlobalStatusUpdate(Request $request){
        try{
                
        }    
        catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    } */

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
