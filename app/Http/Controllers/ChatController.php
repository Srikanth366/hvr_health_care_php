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
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Specialists;
use App\Models\appcategoryconfig;
use App\Models\hospital;
use App\Models\Diagnositcs;
use App\Models\Pharmacy;
use App\Models\Chatrequest;

class ChatController extends Controller
{
    public function RequestForChat(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'doctor_type' => 'required',
                'doctorID' => 'required',
                'patientID' => 'required',
                'notes' => 'required',
             ]);
 
             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

             $countData = Chatrequest::where('doctorID', $request->doctorID)
             ->where('patientID', $request->patientID)
             ->orderBy('id', 'desc')
             ->first();

             if($countData){
                   $status = $countData->status;
                   
                   $user_chat_status = $countData->user_chat_status;
                   if($user_chat_status == 1){
                    return response()->json(['status' => false,'message' => 'Your chat account has been blocked.','request_status' => 3], 201);
                   } else if($status == 0){
                    return response()->json(['status' => false,'message' => 'Your chat account is under review.','request_status' => 0], 201);
                   } else if($status == 1){
                    return response()->json(['status' => false,'message' => 'Your chat account has been approved.','request_status' => 1], 201);
                   } else if($status == 2){
                   // return response()->json(['status' => false,'message' => 'Your chat account has been Rejected.','request_status' => 2], 201);
                   }
             }
 
            $user = Chatrequest::create([
                'doctor_type' => $request->doctor_type,
                'doctorID' => $request->doctorID,
                'patientID' => $request->patientID,
                'notes' => $request->notes
            ]);
            
            if ($user) {
                 return response()->json([
                     'status' => true,
                     'message' => 'Request placed successfully! Your account will be reviewed and chat activated soon.',
                     'request_status' => 0
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

    public function ApprovedOrRejectChat(Request $request){
        try {

            $validateUser = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
             ]);
 
             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

           
             $Chatrequest =  Chatrequest::find($request->id);
             if($Chatrequest){
                    if($request->status == 3){
                        $Chatrequest->status = $request->status;
                        $Chatrequest->user_chat_status  = 1;
                    } else {
                        $Chatrequest->status = $request->status;
                    }
                    $Chatrequest->save();
                    return response()->json(['status' => true,
                                        'message' => 'Status Updated Successfully.',
                                        'request_status' => $request->status], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Please share Valid Data',
                ], 403);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function GetChatRequestdata(Request $request){
        try {
            $validateUser = Validator::make($request->all(), [
                'userid' => 'required',
                'role' => 'required',
             ]);
 
             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation error',
                     'errors' => implode(', ', $validateUser->errors()->all())
                 ], 400);
             }

             if($request->role == 'Customer'){
                $chatRequests = Chatrequest::where('patientID', $request->userid)->get();
             } else {
                $chatRequests = Chatrequest::where('doctorID', $request->userid)->get();
             }
             
             if ($chatRequests->isEmpty()) {
                 return response()->json(['status' => false,'message' => 'No chat requests found.'], 404);
             } else {

                    foreach($chatRequests as $chat){
                        if($chat->user_chat_status == 1) {
                            $chat['is_chat_accepted'] = "Your chat account has been blocked.";
                        } else {
                            if($chat->status == 0) {
                              $chat['is_chat_accepted'] = "Your chat account is under review.";
                            } else if($chat->status == 1) {
                                $chat['is_chat_accepted'] = "Your chat account has been approved.";
                            } else if($chat->status == 2) {
                                $chat['is_chat_accepted'] = "Your chat account has been Rejected.";
                              }
                        }
                        
                    }

                    return response()->json(['status' => true,
                                        'message' => 'Success',
                                        'request_status' => $chatRequests], 200);
             }
            
                /*return response()->json([
                    'status' => false,
                    'message' => 'Please share Valid Data',
                ], 403); */

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }


    }
}
