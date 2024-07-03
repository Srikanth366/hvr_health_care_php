<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctorspeciality;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\InternationPatientController;
use App\Http\Controllers\InsuranceRequestsController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\HospitalsController;
use App\Http\Controllers\DiagnositcsController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\WorkingHourController;
use App\Providers\FirebaseServiceProvider;
use App\Facades\FirebaseAuth;

Route::get('/test-firebase-file', function() {
    $firebaseAuth = app('firebase.auth');
    dd($firebaseAuth); // This should dump the Firebase auth instance
});

Route::get('/test-firebase', function () {
    try {
        $firebaseUser = FirebaseAuth::getUserByEmail('yeddulasrikanthreddy+01@gmail.com');
        return $firebaseUser ? $firebaseUser->uid : 'User not found';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['middleware' => 'auth:sanctum'], function(){

});


/* Route::group(['middleware' => 'auth.hvr_doctor'], function(){  
}); */

Route::post("logout",[DoctorController::class,"logoutuser"]);

Route::post("addSpeciality",[Doctorspeciality::class,"addSpecialists"]);
Route::post("updatespeciality",[Doctorspeciality::class,"updateSpecialists"]);
Route::delete("deletespeciality/{id}",[Doctorspeciality::class,"Deletepecialists"]);
Route::get("DoctorProfile/{id}",[DoctorController::class,"getDoctorProfile"]);


Route::get("ActiveDoctors",[DoctorController::class,"getActivedoctors"]);
//Route::get("InactiveDoctors",[DoctorController::class,"getNotActivedoctors"]);
//Route::get("DoctorProfile/{id}",[DoctorController::class,"getDoctorProfile"]);
Route::put("UpdateDoctorstatus",[DoctorController::class,"updateDoctorStatusData"]);
Route::put("DoctorProfileUpdate",[DoctorController::class,"updateDoctorProfile"]);
Route::post("uploadProfile",[DoctorController::class,"UploadProfile"]);
Route::get('viewprofile',[DoctorController::class,"viewprofile"]);

Route::post("uploaddocuments",[DoctorController::class,"uploadDocuments"]);
Route::delete("deleteDocuments/{id}",[DoctorController::class,"deleteDocuments"]);
Route::get("ViewUploadedDocument/{id}",[DoctorController::class,"ViewUploadedDocuments"]);
Route::get("GetBanner",[DoctorController::class,"GetBanners"]);
Route::get("GetBanner/{id}",[DoctorController::class,"GetBanners"]);

//Route::get('DoctorProfile/{id}', [DoctorController::class, 'getDoctorProfile'])->middleware('auth.error.route');
//Route::get('/error', [ErrorController::class, 'routeError'])->name('error.route');

Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found',
        'message' => 'The requested route was not found.'
    ], 404);
});


Route::post("doctorsWithinRadius",[DoctorController::class,"getDoctorsWithinRadius"]);

Route::get("speciality",[Doctorspeciality::class,"getdoctorspeciality"]);
Route::post("createdoctor",[DoctorController::class,"createUser"]);
Route::post("logindoctor",[DoctorController::class,"loginUser"]);
Route::post("doctorToken",[UserController::class,"generateToken"]);
Route::put("SetFirebaseToken",[DoctorController::class,"UpdateFirebaseToken"]);

Route::get("PushNotificationStatusUpdate/{id}",[DoctorController::class,"updateSPushnotificationtatus"]);
Route::get("GetPushNotification/{id}",[DoctorController::class,"GetPushNotification"]);
Route::get("GenerateFCMToken",[DoctorController::class,"GenerateFirebaseToken"]);
Route::post("UserToken",[UserController::class,"generateToken"]);
//Route::post("login",[UserController::class,'index']);

Route::post("createCustomer",[CustomerController::class,"createCustomer"]);
Route::post("CustomerPicture",[CustomerController::class,"UploadProfile"]);
//Route::post("CustomerLogin",[CustomerController::class,"loginUser"]);
Route::post("CustomerToken",[CustomerController::class,"generateToken"]);
Route::get("CustomerProfile/{id}",[CustomerController::class,"getProfile"]);
Route::put("CustomerupdatProfile",[CustomerController::class,"updatProfile"]);
Route::post("ChangePassword",[CustomerController::class,"customerchangePassword"]);

Route::post("ResetPassword",[CustomerController::class,"AllUsersResetPassword"]);
Route::post("Useresetpassword",[DoctorController::class,"AllUsersResetPassword"]);

Route::post("CustomerFavorites",[CustomerController::class,"SaveCustomerFaverate"]);
Route::get("Getcustomersavoritedoctors/{id}",[CustomerController::class,"getsavedFavorites"]);
Route::delete('removedoctorfavorites/{id}', [CustomerController::class, "DeletesavedFavorites"]);

Route::post("doctorchangepassword",[DoctorController::class,"customerchangePassword"]);
Route::post("userhangepassword",[UserController::class,"customerchangePassword"]);


Route::post("LoginPage",[UserController::class,"CommonloginAllUsers"]);

Route::get("countrylist",[CustomerController::class,"getcountry"]);

Route::post("CreateInternationalPatient",[InternationPatientController::class,"create"]);
Route::get("ViewInternationalPatient",[InternationPatientController::class,"index"]);
Route::get("ViewInternationRequestsDetails/{id}",[InternationPatientController::class,"GetMyInternationRequestDetails"]);
Route::get("ViewMyInternationRequests/{id}",[InternationPatientController::class,"GetMyInternationRequests"]);



Route::post("CreateInsuranceRequest",[InsuranceRequestsController::class,"create"]);
Route::get("ViewInsuranceRequest",[InsuranceRequestsController::class,"index"]);
Route::get("ViewInsuranceDetails/{id}",[InsuranceRequestsController::class,"GetInsuranceRequestDetails"]);
Route::get("ViewMyInsuranceRequetedlist/{id}",[InsuranceRequestsController::class,"GetMyInsuranceRequests"]);


Route::post("addMaster",[MasterController::class,"addMaster"]);
Route::post("updateMaster",[MasterController::class,"updateMaster"]);
Route::get("getMasterData",[MasterController::class,"getMasterData"]);


Route::post("createhospitals",[HospitalsController::class,"createhospitals"]);
Route::get("viewhospitallist",[HospitalsController::class,"gethospitalslist"]);
Route::get("viewhospitallist/{id}",[HospitalsController::class,"gethospitalslist"]);
Route::post("SpecialityWiseAllUsersdata",[HospitalsController::class,"GetSpecialityWiseAllUsersdata"]);
Route::put("updatehospitals",[HospitalsController::class,"updatehospitals"]);
//Route::post("CreateWorkingHours",[HospitalsController::class,"SetWorkingHours"]);
//Route::get("viewWorkingHours/{userid}",[HospitalsController::class,"viewWorkingHours"]);
//Route::delete("deleteWorkingHours/{id}",[HospitalsController::class,"DeleteWorkingHours"]);
Route::post("BookAppointment",[HospitalsController::class,"BookAppointment"]);
Route::post("AppointmentStatusUpdate",[HospitalsController::class,"ConfirmBookAppointment"]);
Route::post("DoctorAppointments",[HospitalsController::class,"ViewAppointmentsForDoctor"]);
Route::post("CustomerAppointments",[HospitalsController::class,"ViewAppointmentsToCustomer"]);
Route::get("GetAppointmentHistory/{id}",[HospitalsController::class,"GetAppointmentHistory"]);


Route::get("viewdiagnosticlist",[DiagnositcsController::class,"getdiagnosticlist"]);
Route::get("viewdiagnosticlist/{id}",[DiagnositcsController::class,"getdiagnosticlist"]);
Route::post("createDiagnostics",[DiagnositcsController::class,"CreateDiagnostics"]);
Route::put("updateDiagnostics",[DiagnositcsController::class,"updateDiagnosticCenterData"]);

Route::get("viewpharmacylist",[PharmacyController::class,"getpharmacylist"]);
Route::get("viewpharmacylist/{id}",[PharmacyController::class,"getpharmacylist"]);
Route::post("CreatePharmacy",[PharmacyController::class,"CreatePharmacy"]);
Route::put("updatePharmacyData",[PharmacyController::class,"updatePharmacyData"]);

/*** Chat  */
Route::post("RequestForChat",[ChatController::class,"RequestForChat"]);
Route::post("ApprovedOrRejectChat",[ChatController::class,"ApprovedOrRejectChat"]);
Route::post("GetChatRequestdata",[ChatController::class,"GetChatRequestdata"]);
/**** Chat Ed */

/** Set Working Hours */
Route::post("SetWorkingHours",[WorkingHourController::class,"store"]);
Route::delete("deleteWorkingHours/{id}",[WorkingHourController::class,"destroy"]);
Route::get("GetWorkingHours/{id}",[WorkingHourController::class,"GetWorkingHours"]);
Route::post("GetAppointmentslots",[WorkingHourController::class,"GetAppointmentslots"]);
/** Set Working Hours End */

Route::post("GlobalSearch",[HospitalsController::class,"GlobalSearch"]);