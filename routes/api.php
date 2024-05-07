<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctorspeciality;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ErrorController;


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
Route::put("updatespeciality",[Doctorspeciality::class,"updateSpecialists"]);
Route::delete("deletespeciality/{id}",[Doctorspeciality::class,"Deletepecialists"]);
Route::get("DoctorProfile/{id}",[DoctorController::class,"getDoctorProfile"]);


Route::get("ActiveDoctors",[DoctorController::class,"getActivedoctors"]);
    Route::get("InactiveDoctors",[DoctorController::class,"getNotActivedoctors"]);
    //Route::get("DoctorProfile/{id}",[DoctorController::class,"getDoctorProfile"]);
    Route::put("UpdateDoctorstatus",[DoctorController::class,"updateDoctorStatus"]);
    Route::put("DoctorProfileUpdate",[DoctorController::class,"updateDoctorProfile"]);
    Route::post("uploadProfile",[DoctorController::class,"UploadProfile"]);
    Route::get('viewprofile',[DoctorController::class,"viewprofile"]);



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

Route::post("UserToken",[UserController::class,"generateToken"]);
Route::post("login",[UserController::class,'index']);


Route::post("createCustomer",[CustomerController::class,"createCustomer"]);
Route::post("CustomerPicture",[CustomerController::class,"UploadProfile"]);
Route::post("CustomerLogin",[CustomerController::class,"loginUser"]);
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
