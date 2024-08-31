<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Customers;
use App\Models\hvr_doctors;
use Illuminate\Support\Facades\DB;
use App\Models\upload_images_documents;
use Carbon\Carbon;


class UserController extends Controller
{
    
    function index(Request $request)
    {
        
        //$userId = 3;
        //$user = User::find($userId);
        //$token = $user->createToken('Token Name')->plainTextToken;
        //return $token;
        $user= User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'status' => false,
                    'message' => 'These credentials do not match our records.'
                ], 404);
            } else {

                if($user->roles == "Customer")
                {
                    $userData = Customers::where('email', $request->email)->first();
                } else if($user->roles == "Doctor") {
                    $userData = hvr_doctors::where('email', $request->email)->first();
                } else {
                    $userData = $user;
                }
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $userData,
                'userid' => $user->id,
                'token' => $token
            ];
        
             return response($response, 201);
    }

    function generateToken(Request $request)
    {
        $user = User::find($request->userid);
        $token = $user->createToken('Token Name')->plainTextToken;
        return $token;
    }

    public function customerchangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials, Please use valid userid and password.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer = User::where('id', $request->id)->first();
        
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['status'=> false,'message' => 'Invalid email or password'], 401);
        }

        $customer->password = bcrypt($request->new_password);
        $customer->save();

        return response()->json([
            'status'=> true,
            'message' => 'Password updated successfully',
            'data' => $customer], 200);
    }

    /**************** Delete Users **************/
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $userDeleted = User::where('id', $id)->delete();

            $customerDeleted = Customers::where('id', $id)->delete();

            if ($userDeleted && $customerDeleted) {
                DB::commit();
                return response()->json(['message' => 'Record deleted successfully'], 200);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Record not found or could not be deleted'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    /************* Delete Users ****************/


    /**************** Upload Banners ************/
    public function AddBanners(Request $request){

        if($request->document_type == 'IMAGE' || $request->document_type == 'Banner') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'display_user_type' => 'required',
                'start_date' => 'required',
                'end_date'  => 'required'
            ], [
                'file.required' => 'Please upload an image.',
                'file.image' => 'The file must be an image.',
                'file.mimes' => 'Only JPEG, PNG, JPG, GIF, and SVG files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]);
        } else if($request->document_type == 'CSV') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|mimes:csv,excel,xls,xlsx,doc,docx,dot,dotx,rtf,odt|max:5120', // 50MB max size (5120 KB)
            ], [
                'file.required' => 'Please upload a CSV / Excel / Word file.',
                'file.mimes' => 'Only CSV and Excel files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]);
        } else if($request->document_type == 'PDF') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|mimes:pdf|max:51200', // 50MB max size (5120 KB)
            ], [
                'file.required' => 'Please upload a PDF file.',
                'file.mimes' => 'Only PDF files are allowed.',
                'file.max' => 'The file size must be less than 5MB.',
            ]); 
        } else if($request->document_type == 'VIDEOLINK') {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:VIDEOLINK', 
                'user_id' => 'required',
                'user_type' => 'required',
                'file' => 'required|url'
            ], [
                'file.required' => 'Please enter a YouTube video URL.',
                'file.url' => 'Please enter a valid URL.',
                'file.regex' => 'Please enter a valid YouTube video URL.'
            ]); 
        } else {
            return response()->json(['status'=> false,'message' => 'Please share valid data',], 422);
        }
    
        if ($validator->fails()) {
            return response()->json(['status'=> false,
                        'message' => 'Validation error',
                        'errors' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
        if($request->document_type == 'VIDEOLINK'){
            $result = $request->file;
            $message = 'Video link updated successfully!';
        } else {
            $result  = $request->file('file')->storePublicly('documents','public');
            $message = 'Document Uploaded successfully';
        }

        $uploadDocument = new upload_images_documents();
        $uploadDocument->document_url = $result;
        $uploadDocument->document_type = $request->document_type;
        $uploadDocument->uploaded_user_id = $request->user_id;
        $uploadDocument->uploaded_user_type = $request->user_type;
        $uploadDocument->file_name =  $request->file;

        $uploadDocument->display_user_type =  $request->display_user_type;
        $uploadDocument->start_date =  $request->start_date;
        $uploadDocument->end_date =  $request->end_date;
        $uploadDocument->save();

        if($uploadDocument){
        $uploadDocument->save();
            return response()->json([
                'status' => true,
                'message' => $message,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please Try Again',
            ], 500);
        }
        

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ViewBanners(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'role' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=> false,
                            'message' => 'Validation error',
                            'errors' => implode(', ', $validator->errors()->all())
                ], 422);
            }


         $currentDate = Carbon::now();
         //$currentDate = date('Y-m-d');
         //$role = 'Pharmacy';

        $documents = upload_images_documents::where('display_user_type', $request->role)
            ->where('document_type', '=', 'Banner')
            ->where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->get();

        if ($documents->isEmpty()) {
            return response()->json(['status'=>false,'message' => 'No documents found'], 404);
        } else {
            $response = ['status' => true,'message' => 'Success','data' => $documents];
            return response()->json($response);
        }
            
        /* $documents = upload_images_documents::where('document_type', 'Banner')->orderBy('id', 'desc')->get();
        if (!$documents) {
                return $this->apiResponse(false, 'No Data found', []);
        }else {
            $response = [
                'status' => true,
                'message' => 'Success',
                'data' => $documents];
                return response()->json($response);
        } */


            }catch (\Exception $e) {
                return $this->apiResponse(false, 'Failed', [], $e->getMessage());
            }
    }
    /************ Uplaod Banners ****************/



}
