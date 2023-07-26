<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use App\Models\Brand;
use App\Models\IdCard;
use App\Models\Job;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;

class JobController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJobs(Request $request){

        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1){
                $data = Job::orderBy('id',"Desc")
                     ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                     ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                     ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                     ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                     ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                         ->select(
                             'jobs.*',
                             'brands.name as brand_name',
                             'couriers.name as courier_name',
                             'id_cards.name as id_card_name',
                             'users.name as customer_name'
                         )
                    ->skip($request->offset)->take(10)
                    ->get();
                $data_count = Job::orderBy('id',"Desc")
                    ->get()->count();
            }else if ($user->is_admin == 1 && $user->role == 2){
                $data = Job::orderBy('id',"Desc")->where("user_id",$user->id)
                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name'
                    )
                    ->skip($request->offset)->take(10)
                    ->get();
                $data_count = Job::orderBy('id',"Desc")->where("user_id",$user->id)
                    ->get()->count();
            }
            if($data->isNotEmpty()){
                return response([
                    'data' => $data,
                    'count' => $data_count,
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

    public function jobDetail(Request $request){

        try{
            $user = auth()->user();

            $data = Job::where("jobs.id",$request->id)
                ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                ->select(
                    'jobs.*',
                    'brands.name as brand_name',
                    'couriers.name as courier_name',
                    'id_cards.name as id_card_name',
                    'users.name as customer_name'
                )
//                ->with('customer')
                ->with('cards')
//                ->with('shop')
                ->with('preRepairs')
                ->with('parts')
                ->first();

            if($data){
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
    public function getGenericData(Request $request){

        try{
            $user = auth()->user();

            $devices = array(
                'Mobile' => 1,
                'Laptop' => 2
            );
            $devices = array (
                array("id"=> 1,"name"=>"Mobile"),
                array("id"=> 2,"name"=>"Laptop"),
            );

            $status = array (
                array("id"=> 1,"name"=>"Accepted"),
                array("id"=> 2,"name"=>"Progressing"),
                array("id"=> 3,"name"=>"Completed"),
            );
            if($user->is_admin == 1 && $user->role == 1){
                $shops = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([['role' ,2],["is_admin",1]])
                    ->take(50)->get();
                $users = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([["is_admin",0]])
                    ->take(50)->get();
                $id_cards = IdCard::orderBy("id", "Desc")
                    ->select('name','id')
                    ->take(50)->get();
                $brands = Brand::orderBy("id", "Desc")
                    ->select('name','id')
                    ->take(50)->get();

                return response([
                    'shops' => $shops,
                    'users' => $users,
                    'id_cards' => $id_cards,
                    'brands' => $brands,
                    'devices' => $devices,
                    'status' => $status,
                    'message' => "Records Found",
                    'error' => false
                ], 200);
            }elseif($user->is_admin == 1 && $user->role == 2){
                $users = User::orderBy("id", "Desc")
                    ->select('name','id')
                    ->where([["is_admin",0],["parent_id",$user->id]])
                    ->take(50)->get();
                $id_cards = IdCard::orderBy("id", "Desc")
                    ->select('name','id')
                    ->take(50)->get();
                $brands = Brand::orderBy("id", "Desc")
                    ->select('name','id')
                    ->take(50)->get();
                return response([
                    'shops' => [],
                    'users' => $users,
                    'id_cards' => $id_cards,
                    'brands' => $brands,
                    'devices' => $devices,
                    'status' => $status,
                    'message' => "Records  Found",
                    'error' => false
                ], 200);
            }
        }
        catch(Exception $e){
            return response()->json(['message' =>$e->getMessage() , 'error' => true], 200);
        }
    }
    public function searchJobs(Request $request){

        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1){
                $data = Job::orderBy('jobs.id',"Desc")
                    ->where('jobs.id', 'like', "%{$request->keyword}%")
                    ->orWhere('jobs.job_sheet_number', 'like', "%{$request->keyword}%")
                    ->orWhere('jobs.serial_number', 'like', "%{$request->keyword}%")
                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name'
                    )
                    ->get();
            }else if ($user->is_admin == 1 && $user->role == 2){
                $data = Job::orderBy('jobs.id',"Desc")
                    ->where([['jobs.id', 'like', "%{$request->keyword}%"],["user_id",$user->id]])
                    ->orWhere([['jobs.job_sheet_number', 'like', "%{$request->keyword}%"],["user_id",$user->id]])
                    ->orWhere([['jobs.serial_number', 'like', "%{$request->keyword}%"],["user_id",$user->id]])
                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name'
                    )
                    ->get();
            }
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
    public function userSearch(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'key' => 'required'
        ]);
        $user_id = auth()->user()->id;

        $user_detail = User::where('name', 'like', "%{$request->key}%")->get();
        //    return $user_detail;
        if (!$user_detail) {
            return response([
                'message' => "Search Not Found",
                'error' => true
            ], 200);
        }

        foreach ($user_detail as $detail) {
            $id = $detail['id'];
            $isInvited = Invitation::where([['sender_id', $user_id], ['receiver_id', $id], ['event_id', $request->event_id]])->count();
            if ($isInvited > 0) {
                $detail['isInvited'] = true;
            } else {
                $detail['isInvited'] = false;
            }
        }
        return response([
            'data' => $user_detail,
            'message' => "User Search",
            'error' => false
        ], 200);
    }

}
