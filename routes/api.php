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
use App\Http\Controllers\VersionController;
use App\Providers\FirebaseServiceProvider;
use App\Facades\FirebaseAuth;
use Illuminate\Http\Response;

Route::get('/test-firebase-file', function() {
     $firebaseAuth = app('firebase.auth');
    //$basePath = base_path(); // Gives you something like '/var/www/html/project/'
    //$publicPath = public_path();
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

/* Route::group(['middleware' => 'auth:sanctum'], function(){ */

    Route::get("DoctorProfile/{id}",[DoctorController::class,"getDoctorProfile"]);
    Route::post("DoctorProfile",[DoctorController::class,"getDoctorProfileData"]);
    Route::get("ActiveDoctors",[DoctorController::class,"getActivedoctors"]);
    //Route::get("InactiveDoctors",[DoctorController::class,"getNotActivedoctors"]);

    Route::get('viewprofile',[DoctorController::class,"viewprofile"]);
    Route::get("ViewUploadedDocument/{id}",[DoctorController::class,"ViewUploadedDocuments"]);
    Route::post("AddBanners",[UserController::class,"AddBanners"]);
    Route::post("ViewBanners",[UserController::class,"ViewBanners"]);
    Route::get("GetBanner",[DoctorController::class,"GetBanners"]);
    Route::get("GetBanner/{id}",[DoctorController::class,"GetBanners"]);
    Route::get("speciality",[Doctorspeciality::class,"getdoctorspeciality"]);
    Route::get("PushNotificationStatusUpdate/{id}",[DoctorController::class,"updateSPushnotificationtatus"]);
    Route::get("GetPushNotification/{id}",[DoctorController::class,"GetPushNotification"]);
    Route::get("GenerateFCMToken",[DoctorController::class,"GenerateFirebaseToken"]);
    Route::get("ViewInternationalPatient",[InternationPatientController::class,"index"]);
    Route::get("ViewInternationRequestsDetails/{id}",[InternationPatientController::class,"GetMyInternationRequestDetails"]);
    Route::get("ViewMyInternationRequests/{id}",[InternationPatientController::class,"GetMyInternationRequests"]);
    Route::get("countrylist",[CustomerController::class,"getcountry"]);
    Route::get("Getcustomersavoritedoctors/{id}",[CustomerController::class,"getsavedFavorites"]);
    Route::get("getUserFavorites/{id}",[CustomerController::class,"getUserFavorites"]);
    Route::get("ViewInsuranceRequest",[InsuranceRequestsController::class,"index"]);
    Route::get("ViewInsuranceDetails/{id}",[InsuranceRequestsController::class,"GetInsuranceRequestDetails"]);
    Route::get("ViewMyInsuranceRequetedlist/{id}",[InsuranceRequestsController::class,"GetMyInsuranceRequests"]);
    Route::get("getMasterData",[MasterController::class,"getMasterData"]);
    Route::get("viewhospitallist/{id}",[HospitalsController::class,"gethospitalslist"]);
    Route::get("viewhospitallist",[HospitalsController::class,"gethospitalslist"]);
    Route::post("viewhospitallist",[HospitalsController::class,"gethospitalslistfavorites"]);
    Route::get("GetAppointmentHistory/{id}",[HospitalsController::class,"GetAppointmentHistory"]);
    Route::get("viewdiagnosticlist",[DiagnositcsController::class,"getdiagnosticlist"]);
    Route::get("viewdiagnosticlist/{id}",[DiagnositcsController::class,"getdiagnosticlist"]);
    Route::post("viewdiagnosticlist",[DiagnositcsController::class,"getdiagnosticlistfavorites"]);
    Route::get("CustomerProfile/{id}",[CustomerController::class,"getProfile"]);
    Route::get("viewpharmacylist",[PharmacyController::class,"getpharmacylist"]);
    Route::get("viewpharmacylist/{id}",[PharmacyController::class,"getpharmacylist"]);
    Route::post("viewpharmacylist",[PharmacyController::class,"getpharmacylistfavorites"]);
    Route::get("GetWorkingHours/{id}",[WorkingHourController::class,"GetWorkingHours"]);

    /************** POST APIS ***************/
    Route::post("GlobalSearch",[HospitalsController::class,"GlobalSearch"]);
    /*** Chat  */
    Route::post("RequestForChat",[ChatController::class,"RequestForChat"]);
    Route::post("ApprovedOrRejectChat",[ChatController::class,"ApprovedOrRejectChat"]);
    Route::post("GetChatRequestdata",[ChatController::class,"GetChatRequestdata"]);
    /**** Chat Ed */
    /** Set Working Hours */
    Route::post("SetWorkingHours",[WorkingHourController::class,"store"]);
    Route::delete("deleteWorkingHours/{id}",[WorkingHourController::class,"destroy"]);
    Route::post("GetAppointmentslots",[WorkingHourController::class,"GetAppointmentslots"]);
    /** Set Working Hours End */
    Route::post("BookAppointment",[HospitalsController::class,"BookAppointment"]);
    Route::post("AppointmentStatusUpdate",[HospitalsController::class,"ConfirmBookAppointment"]);
    Route::post("DoctorAppointments",[HospitalsController::class,"ViewAppointmentsForDoctor"]);
    Route::post("CustomerAppointments",[HospitalsController::class,"ViewAppointmentsToCustomer"]);
    Route::post("createDiagnostics",[DiagnositcsController::class,"CreateDiagnostics"]);
    Route::post("CreatePharmacy",[PharmacyController::class,"CreatePharmacy"]);
    Route::put("updatePharmacyData",[PharmacyController::class,"updatePharmacyData"]);
    Route::post("CreateInsuranceRequest",[InsuranceRequestsController::class,"create"]);
    Route::post("CreateInternationalPatient",[InternationPatientController::class,"create"]);
    Route::post("addMaster",[MasterController::class,"addMaster"]);
    Route::post("updateMaster",[MasterController::class,"updateMaster"]);
    Route::post("createhospitals",[HospitalsController::class,"createhospitals"]);
    Route::post("SpecialityWiseAllUsersdata",[HospitalsController::class,"GetSpecialityWiseAllUsersdata"]);
    Route::post("CustomerFavorites",[CustomerController::class,"SaveCustomerFaverate"]);
    Route::post("doctorsWithinRadius",[DoctorController::class,"getDoctorsWithinRadius"]);
    Route::post("CustomerPicture",[CustomerController::class,"UploadProfile"]);
    Route::post("addSpeciality",[Doctorspeciality::class,"addSpecialists"]);
    Route::post("updatespeciality",[Doctorspeciality::class,"updateSpecialists"]);
    Route::post("uploadProfile",[DoctorController::class,"UploadProfile"]);
    Route::post("uploaddocuments",[DoctorController::class,"uploadDocuments"]);

    Route::post("ChangePassword",[CustomerController::class,"customerchangePassword"]);
    Route::post("doctorchangepassword",[DoctorController::class,"customerchangePassword"]);
    Route::post("userhangepassword",[UserController::class,"customerchangePassword"]);

    Route::post('versionSave', [VersionController::class, 'versionSave']);
    Route::get('AllVersions', [VersionController::class, 'index']);
    Route::post('versionCheck', [VersionController::class, 'versionCheck']);
    
    /************** POST APIS END ***************/

    /******** PUT & Delete APIS *****************/
    Route::put("updateDiagnostics",[DiagnositcsController::class,"updateDiagnosticCenterData"]);
    Route::put("updatehospitals",[HospitalsController::class,"updatehospitals"]);
    Route::put("CustomerupdatProfile",[CustomerController::class,"updatProfile"]);
    Route::put("UpdateDoctorstatus",[DoctorController::class,"updateDoctorStatusData"]);
    Route::put("DoctorProfileUpdate",[DoctorController::class,"updateDoctorProfile"]);

    Route::delete("deletespeciality/{id}",[Doctorspeciality::class,"Deletepecialists"]);
    Route::delete('deleteuser/{id}', [CustomerController::class, 'destroy']);
    Route::delete('removedoctorfavorites/{id}', [CustomerController::class, "DeletesavedFavorites"]);
    Route::delete("deleteDocuments/{id}",[DoctorController::class,"deleteDocuments"]);
    /**********PUT & Delete APIS END ******************/
/* }); */

Route::get('/sessionout', function() {
    return response()->json(['status'=>false,'error' => 'Authentication / Session Failed','message' => 'Session timed out.'], 401);
})->name('sessionout');


Route::post("logout",[DoctorController::class,"logoutuser"]);
Route::post("createdoctor",[DoctorController::class,"createUser"]);
Route::post("logindoctor",[DoctorController::class,"loginUser"]);
Route::post("doctorToken",[UserController::class,"generateToken"]);
Route::put("SetFirebaseToken",[DoctorController::class,"UpdateFirebaseToken"]);
Route::post("UserToken",[UserController::class,"generateToken"]);
Route::post("createCustomer",[CustomerController::class,"createCustomer"]);
Route::post("CustomerToken",[CustomerController::class,"generateToken"]);
Route::post("ResetPassword",[CustomerController::class,"AllUsersResetPassword"]);
Route::post("Useresetpassword",[DoctorController::class,"AllUsersResetPassword"]);
Route::post("LoginPage",[UserController::class,"CommonloginAllUsers"]);

//Route::post("CreateWorkingHours",[HospitalsController::class,"SetWorkingHours"]);
//Route::get("viewWorkingHours/{userid}",[HospitalsController::class,"viewWorkingHours"]);
//Route::delete("deleteWorkingHours/{id}",[HospitalsController::class,"DeleteWorkingHours"]);
//Route::post("CustomerLogin",[CustomerController::class,"loginUser"]);
//Route::post("login",[UserController::class,'index']);
//Route::get('DoctorProfile/{id}', [DoctorController::class, 'getDoctorProfile'])->middleware('auth.error.route');

Route::get('/error', [ErrorController::class, 'routeError'])->name('error.route');
Route::fallback(function () {
    return response()->json([
        'status' => false,
        'error' => 'Route not found',
        'message' => 'The requested route was not found.'
    ], 404);
});


Route::get('/method-not-allowed', function () {
    return response()->json([
        'status' => false,
        'message' => '405 Method Not Allowed',
        'details' => 'The HTTP method you used is not allowed for this user.'
    ], Response::HTTP_METHOD_NOT_ALLOWED);
})->name('methodnotallowed');



