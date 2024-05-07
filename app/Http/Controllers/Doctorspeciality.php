<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Specialists;
use App\Models\hvr_doctors;

class Doctorspeciality extends Controller
{

    function getdoctorspeciality(){
        try {
        $specialties = Specialists::orderBy("id")->get();
        return $this->apiResponse(true, 'Success', $specialties);
        }catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed', [], $e->getMessage());
        }
    }

    function Deletepecialists($id){

        //$doctorExists = hvr_doctors::whereIn('specialist', [$id])->exists();
        $doctorExists = hvr_doctors::where('specialist', 'like', '%' . $id . '%')->pluck('specialist');
        $specialistsArray = $doctorExists->toArray();
        $result = [];
        foreach ($specialistsArray as $string) {
            $values = explode(',', $string);
            $result = array_merge($result, $values);
        }
    
        $result = array_unique($result);
        sort($result);
        if (in_array_case_insensitive($id, $result)) {

            return response()->json([
                'status' => false,
                'message' => 'Speciality cannot be deleted as it is associated with a doctor',
            ], 422);

        } else {
            $speciality = Specialists::find($id);
            if ($speciality) {
                $speciality->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Speciality deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Speciality not found',
                ], 404);
            }
        }

    }

    function addSpecialists(Request $request){

        $validator = Validator::make($request->all(), [
            'speciality' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {   
            $Specialists = new Specialists;
            $Specialists->speciality = $request->speciality;
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

    function updateSpecialists(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:specialists,id',
            'speciality' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $specialist = Specialists::findOrFail($request->id);
            $specialist->speciality = $request->speciality;
            $specialist->updated_at  = date('Y-m-d H:i:s');
            $specialist->save();
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => $specialist,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
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
