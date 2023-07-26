<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use App\Http\Controllers\ApiController;
use App\Models\BasicSetting;
use Validator;
use DB;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        if ($request->account_type == 0) {
            $validatedData = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:55',
                    'email' => 'email|required|unique:users',
                    'password' => 'required|confirmed',
                    'phone' => 'required',
                    'longitude' => 'required',
                    'account_type' => 'required',
                    'latitude' => 'required',
                    'address' => 'required',
                    'dob' => 'required',
                    'image' => 'required|image|mimes:jpeg,png,jpg',
                ));
        }else{
            $validatedData = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:55',
                    'email' => 'email|required|unique:users',
                    'phone' => 'required',
                    'longitude' => 'required',
                    'account_type' => 'required',
                    'latitude' => 'required',
                    'address' => 'required',
                    'dob' => 'required',
                    'image' => 'required|image|mimes:jpeg,png,jpg',
                ));
        }

        if ($validatedData->fails())
        {
            $error = $validatedData->errors()->first();
            return $this->errorResponse($error, 200);
        }
        $validatedData = $request->all();
        if ($request->account_type == 0) {
            $validatedData['password'] = bcrypt($request->password);
        }
        $validatedData['is_admin'] = 0;
        $validatedData['account_type'] = $request->account_type;
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('image');
                $image = rand().$image;
                $request->file('image')->move($destinationPath, $image);
                $validatedData['image'] = $image;

            }
        }

        $user = User::create($validatedData);
        $user->access_token = $user->createToken('authToken')->accessToken;
        if ($user->account_type == 0){
            $user->sendEmailVerificationNotification();
            $message = "Your Account has been created successfully. For verification check your email";
        }else{

            $message = "Your Account has been created successfully.";
        }

        return response([
            'data' => $user,
            'message' => $message,
            'error' => false
        ],200);

    }


    public function login(Request $request)
    {
        try{
            $validatedData= Validator::make(
                $request->all(),
                array(
                    'email' => 'email|required',
                    'password' => 'required'
                ));
            if ($validatedData->fails())
            {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $loginData = $request->all();
            unset($loginData["fcm_token"]);
            if(!auth()->attempt($loginData)) {
                return response()->json(['message' => "Invalid Credentials", 'code' => 1,'error'=>true], 200);
            }

            $user = auth()->user();
            $token = auth()->user()->createToken('authToken')->accessToken;
            $user->fcm_token = $request->fcm_token;
            $user->save();
            $user->access_token = $token;
           
            $image = BasicSetting::where('user_id',$user->id)->first();
            if($image!=''){
                $user->image = $image->image;
            }
    
//        if(!$user->email_verified_at and $user->account_type == 0) {
//            $user->sendEmailVerificationNotification();
//            return response()->json(['message' => "Sorry! Verify your email First", 'data' => $user, 'code' => 2,'error'=> false, 'isVerified' =>false], 200);
//        }

            return response()->json(['message' => "Great! Login Successfully", 'data' => $user, 'error'=> false], 200);

        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }

    public function logout()
    {

        try{
            $user = auth()->user();
            if ($user){
                $accessToken = auth()->user()->token();
                DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $accessToken->id)
                    ->update([
                        'revoked' => true
                    ]);
                $accessToken->revoke();
                $user->fcm_token = null;
                $user->save();
                return response([
                    'message' => "Logout Successfully",
                    "error" => false
                ],200);
            }else{
                return response([
                    'message' => "User Not Found",
                    "error" => true
                ],200);
            }
        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }

    public function updateToken(Request $request)
    {
        $id = auth()->user()->id;

        $user = User::find($id);

        if($user){
            $user->fcm_token = $request->fcm_token;
            $user->save();
            return response([
                'message' => " Fcm Token Updated Successfully",
                'error' => false
            ], 200);
        } else {
            return response([
                'message' => "User Not Found",
                'error' => true
            ], 200);
        }

    }




}
