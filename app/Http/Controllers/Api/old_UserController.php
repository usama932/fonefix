<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Brand;
use App\Models\Bucket;
use App\Models\Device;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Notification;
use App\Models\RequestedEvent;
use App\Models\SearchHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;

class UserController extends ApiController
{
    public function searchShops(Request $request){

        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1){
                $data = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([['role' ,2],["is_admin",1],['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if($data->isNotEmpty()){
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                }else{
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }else{
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }

        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }


    public function searchUsers(Request $request){

        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1){
                $data = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([["is_admin",0],['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if($data->isNotEmpty()){
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                }else{
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }elseif($user->is_admin == 1 && $user->role == 2){
                $data = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([["parent_id" => $user->id],["is_admin",0],['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if($data->isNotEmpty()){
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                }else{
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }

        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }

    public function searchBrands(Request $request){

        try{
            $user = auth()->user();

            $data = Brand::orderBy("id", "Desc")
                ->select('name','id')
                ->where([['name', 'like', "%{$request->keyword}%"]])
                ->get();

            if($data->isNotEmpty()){
                return response([
                    'data' => $data,
                    'message' => "Records",
                    'error' => false
                ], 200);
            }else{
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }
    public function getModels(Request $request){

        try{
            $user = auth()->user();

            $data = Device::orderBy("id", "Desc")
                ->where([['type',  $request->type],['brand_id',  $request->brand_id]])
                ->get();

            if($data->isNotEmpty()){
                return response([
                    'data' => $data,
                    'message' => "Records",
                    'error' => false
                ], 200);
            }else{
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }
}
