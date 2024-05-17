<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\insurance_request;

class InsuranceRequestsController extends Controller
{
    public function index(){
        try {
            $list = insurance_request::orderByDesc('id')->get();
            if (!$list) {
                return $this->apiResponse(false, 'No Data found', []);
            }else {
                return $this->apiResponse(true, 'Success', $list);
            }
        }catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }  
    }

    public function GetMyInsuranceRequests($id){
        try {
                $list = insurance_request::where('customer_id', $id)->get();
                if ($list->count() == 0) {
                    return $this->apiResponse(false, 'No Data found', []);
                }else {
                    return $this->apiResponse(true, 'Success', $list);
                }
            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }   
    }

    public function GetInsuranceRequestDetails($id){
        try {
                $list = insurance_request::where('id', $id)->get();
                if ($list->count() == 0) {
                    return $this->apiResponse(false, 'No Data found', []);
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
               'mobile' => 'required',
               'city' => 'required',
               'address' => 'required',
               'pincode' => 'required',
               'description' => 'required',
               'customer_id' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => implode(', ', $validateUser->errors()->all())
                ], 400);
            }


            $user = insurance_request::create([
               'name' => $request->name,
               'gender' => $request->gender,
               'email' => $request->email,
               'mobile' => $request->mobile,
               'city' => $request->city,
               'address' => $request->address,
               'pincode'=> $request->pincode,
               'description'=> $request->description,
               'customer_id'=> $request->customer_id
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
