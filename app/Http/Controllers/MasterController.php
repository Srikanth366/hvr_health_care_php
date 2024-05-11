<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\masterdata;
use Illuminate\Support\Facades\File;

class MasterController extends Controller
{
    public function index(){

    }

    function addMaster(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
        ], [
            'file.required' => 'Please upload an image.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
            'file.max' => 'The file size must be less than 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        try {
            $result  = $request->file('file')->storePublicly('icons','public');
            $Specialists = new masterdata;
            $Specialists->name = $request->name;
            $Specialists->icon = $result;
            $Specialists->created_at  = date('Y-m-d H:i:s');
            $Specialists->save();

            return response()->json([
                'status' => true,
                'message' => 'Data saved successfully',
                'data' => [ $Specialists,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    function updateMaster(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:specialists,id',
            'name' => 'required|string|max:255',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max size (5120 KB)
        ], [
            'file.required' => 'Please upload an image.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
            'file.max' => 'The file size must be less than 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $result  = $request->file('file')->storePublicly('icons','public');
            $specialist = masterdata::findOrFail($request->id);
            $specialist->name = $request->name;
            $specialist->icon = $result;
            $specialist->updated_at  = date('Y-m-d H:i:s');
            $specialist->save();

            if($specialist){
                
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => $specialist,
            ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'something went wrong!'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    function getMasterData(){
        try {
        $specialties = masterdata::orderBy("id")->get();
        return $this->apiResponse(true, 'Success', $specialties);
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
