<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\internationalpatient;

class InternationPatientController extends Controller
{
    public function index(){
        try {
                $list = internationalpatient::orderByDesc('id')->get();
                if (!$list) {
                    return $this->apiResponse(true, 'No Data found', []);
                }else {
                    return $this->apiResponse(true, 'Success', $list);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    } 

    public function create(Request $request){

        try {
            // Validate the request
            $validateUser = Validator::make($request->all(), [
               'name' => 'required',
               'gender' => 'required',
               'email' => 'required|email',
               'country' => 'required',
               'mobile_code' => 'required',
               'mobile' => 'required',
               'service_request' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }


            $user = internationalpatient::create([
               'name' => $request->name,
               'gender' => $request->gender,
               'email' => $request->email,
               'mobile' => $request->mobile,
               'mobile_code' => $request->mobile_code,
               'country' => $request->country,
               'service_request'=> $request->service_request,
           ]);
           
           if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'Your request has been placed successfully. Our admin team will contact you shortly.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to save data, pls try again!',
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
