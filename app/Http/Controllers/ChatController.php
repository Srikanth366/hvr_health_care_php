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
use App\Models\PushNotification;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Providers\FirebaseServiceProvider;
use App\Facades\FirebaseAuth;

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

                $pushnotification = $this->SendPushNotification($request->doctorID,$request->doctor_type);

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
            $pushnotification = $this->SendChatResponseotification($Chatrequest->patientID,'Customer');

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

                        $user = Customers::select(
                            DB::raw("CONCAT(first_name, ' ', last_name) as patientName"),
                            'profile_photo as profileImage'
                        )->where('id', $chat->patientID)->first();

                        $chat['patientName']  = $user->patientName;
                        $chat['profileImage']  = $user->profileImage;
                        
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

    /**************** Send Chat notifications *************/
    function generateFCMToken($serviceAccountPath)
    {
        // Load the service account JSON file
        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    
        $now_seconds = time();
        $exp_seconds = $now_seconds + (60 * 60); // Token valid for 1 hour
    
        // Create the payload
        $payload = array(
            "iss" => $serviceAccount['client_email'],
            "sub" => $serviceAccount['client_email'],
            "aud" => "https://oauth2.googleapis.com/token",
            "iat" => $now_seconds,
            "exp" => $exp_seconds,
            "scope" => "https://www.googleapis.com/auth/firebase.messaging"
        );
    
        // Encode the JWT
        $jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');
    
        // Get the OAuth 2.0 token
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
    
        public function SendPushNotification($newinsertingId,$roles){
    
            $serviceAccountPath = env('FirebasePAth');
            $authBearerToken = $this->generateFCMToken($serviceAccountPath);
    
           // $adminUsers = User::where('roles', 'Admin')->get();
           $user = User::where('id', $newinsertingId)->first();
            if ($user) {
                $firebaseUserId = $user->FbUserID;
            } else {
                $firebaseUserId = '';
            }

           /* echo "<pre>";
            print_r($user);
            exit; */
    
            if (!$user) {
                return response()->json(['message' => 'No users found'], 404);
            } else {
    
                $FMProjetID = env('ProjetID');              
                    $unames = $user->name;
                    $FbUserID = $user->FbUserID;
                    $FbToken = $user->FbToken;
                    $FBAuth = 'Bearer '.$authBearerToken;
    
                    $ptitle = "Dear ".$unames.",";
                    $pmessage = "You have received a new chat request.";
    
    $notificationTitle = "Chat Request Alert";
    $notificationBody = "You have received a new chat request.";
    $role = "Chat Request";
    $userId = ".$newinsertingId.";
    //$firebaseUserId = "q55qcFxbwjcNjpbvBSywhedxDyw1";
    $type = "chatrequest";
    $androidTitle = "Dear ".$unames.",";
    $androidBody = "You have received a new chat request.";
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
    
                   // if($response){
                        $pushNotification = PushNotification::create([
                            'title' => $ptitle,
                            'message' => $pmessage,
                            'user_id' => $user->id,
                            'role' => $roles,
                            'status' => 0,
                        ]);
                        /* if ($pushNotification) {
                            return response()->json(['message' => 'Push notification created successfully!'], 201);
                        } */
                   // }
                
    
                return $response;
            }
    
        }

        public function SendChatResponseotification($newinsertingId,$roles){
            
            $serviceAccountPath = env('FirebasePAth');
            $authBearerToken = $this->generateFCMToken($serviceAccountPath);
    
           $user = User::where('id', $newinsertingId)->first();
            if ($user) {
                $firebaseUserId = $user->FbUserID;
            } else {
                $firebaseUserId = '';
            }

           /* if($chat_status == 0){
                $ChatStatus = 'Pending';
            } else if($chat_status == 1){
                $ChatStatus = 'Approved';
            } else if($chat_status == 2){
                $ChatStatus = 'Rejected';
            } else {
                $ChatStatus = 'Blocked';
            } */

    
            if (!$user) {
                return response()->json(['message' => 'No users found'], 404);
            } else {
    
                    $FMProjetID = env('ProjetID');              
                    $unames = $user->name;
                    $FbUserID = $user->FbUserID;
                    $FbToken = $user->FbToken;
                    $FBAuth = 'Bearer '.$authBearerToken;
    
                    $ptitle = "Dear ".$unames.",";
                    $pmessage = "Chat request status has been updated.";
    
                    $notificationTitle = "Chat Request Status";
                    $notificationBody = "Chat request status has been updated.";
                    $role = "Chat Request Status";
                    $userId = ".$newinsertingId.";
                    $type = "chatrequest";
                    $androidTitle = "Dear ".$unames.",";
                    $androidBody = "Chat request status has been updated.";
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
                "userId" => $newinsertingId,
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

  //  echo "<pre>"; print_r($data); exit;
                    
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

                   // print_r($response);
    
                    $pushNotification = PushNotification::create([
                        'title' => $ptitle,
                        'message' => $pmessage,
                        'user_id' => $user->id,
                        'role' => $roles,
                        'status' => 0,
                    ]);
    
                return $response;
            }
    
        }
    /************* Send Chant NotificationEnd *************/
}
