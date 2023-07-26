<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WatchRequest;
use App\Models\WatchRequestImage;
use Illuminate\Http\Request;
use Exception;
use Validator;
use Illuminate\Support\Facades\DB;

class WatchesController extends ApiController
{

    public function newArrivals(Request $request)
    {
        $products = Product::latest()->take(5)->get();
        return response()->json(['data' => $products, 'status' => true,'message'=>"New Arrivals Found Successfully",'error'=>false],200);

    }
    public function findWatch(Request $request)
    {
//        return ("gkdflgkf");
        try{
            $validator = Validator::make($request->all(), [
                'first_name'                => 'required',
                'last_name'                 => 'required',
                'email'                     => 'required',
                'phone_number'              =>  'required',
                'category_id'               =>  'required',
                'brand_id'                  =>  'required',
                'budget'                    =>  'required',
                'best_time_to_contact'      =>  'required',
                'message'                   =>  'required',
                'images'                    =>  "required",
                'images.*'                  =>  'image|mimes:jpeg,png,jpg,gif'

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }



            DB::beginTransaction();
           $create_request = WatchRequest::create([
                        'first_name'        =>  $request->first_name,
                        'last_name'         =>  $request->last_name,
                        'email'             =>  $request->email,
                        'phone_number'      =>  $request->phone_number,
                        'brand_id'          =>  $request->brand_id,
                        'description'       =>  $request->message,
                        'category_id'       =>  $request->category_id,
                        'budget'            =>  $request->budget,
                        'contact_time'      =>  $request->best_time_to_contact,
                        'request_type_id'   =>  1

            ]);
            if($create_request){
                foreach($request->file('images') as $image)
                {
                    $name = rand().time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $name);
                    WatchRequestImage::create(['watch_request_id'=>$create_request->id,'image'=>$name]);
                }
                DB::commit();

                return response()->json([ 'status' => true,'message'=>"Request submited successfully",'error'=>false],200);
            }else{
                DB::rollBack();
                return response()->json(['status' => false,'message'=>"Request not submited at this time. Please try again later.",'error'=>true],200);
            }


        }catch(Exception $e){

            return response()->json(['status' => false, 'message' =>$e->getMessage() , 'error' => true], 200);
        }

    }

    public function sellWatch(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'first_name'                => 'required',
                'last_name'                 => 'required',
                'email'                     => 'required',
                'phone_number'              =>  'required',
                'brand_id'                  =>  'required',
                'budget'                    =>  'required',
                'message'                   =>  'required',
                'sell_trade'                =>  'required',
                'watch_age'                 =>  'required',
                'model'                     =>  'required',
                'model_number'              =>  'required',
                'papers'                     =>  'required',
                'box'                       =>  'required',

            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }



            DB::beginTransaction();
           $create_request = WatchRequest::create([
                        'first_name'        =>  $request->first_name,
                        'last_name'         =>  $request->last_name,
                        'email'             =>  $request->email,
                        'phone_number'      =>  $request->phone_number,
                        'brand_id'          =>  $request->brand_id,
                        'description'       =>  $request->message,
                        'budget'            =>  $request->budget,
                        'sell_trade'        =>  $request->sell_trade,
                        'request_type_id'   =>  2,
                        'watch_age'         =>  $request->watch_age,
                        'model'             =>  $request->model,
                        'model_number'      =>  $request->model_number,
                        'papers'            =>  $request->papers,
                        'box'               =>  $request->box,

            ]);
            if($create_request){

                DB::commit();

                return response()->json(['status' => true,'message'=>"Request submited successfully",'error'=>false],200);
            }else{
                DB::rollBack();
                return response()->json(['status' => false,'message'=>"Request not submited at this time. Please try again later.",'error'=>true],200);
            }


        }catch(Exception $e){

            return response()->json(['status' => false,'message' =>$e->getMessage() , 'error' => true], 200);
        }

    }

    public function consignWatch(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'first_name'                => 'required',
                'last_name'                 => 'required',
                'email'                     => 'required',
                'phone_number'              =>  'required',
                'brand_id'                  =>  'required',
                'budget'                    =>  'required',
                'message'                   =>  'required',
                'model'                     =>  'required',
                'model_number'              =>  'required',
                'serial_number'             =>  'required',
                'papers'                    =>  'required',
                'box'                       =>  'required',
                'images'                    =>  "required",
                'images.*'                  =>  'image|mimes:jpeg,png,jpg,gif'

            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }



            DB::beginTransaction();
           $create_request = WatchRequest::create([
                        'first_name'        =>  $request->first_name,
                        'last_name'         =>  $request->last_name,
                        'email'             =>  $request->email,
                        'phone_number'      =>  $request->phone_number,
                        'brand_id'          =>  $request->brand_id,
                        'budget'            =>  $request->budget,
                        'description'       =>  $request->message,
                        'model'             =>  $request->model,
                        'model_number'      =>  $request->model_number,
                        'serial_number'     =>  $request->serial_number,
                        'papers'            =>  $request->papers,
                        'box'               =>  $request->box,
                        'request_type_id'   =>  3

            ]);
            if($create_request){
                foreach($request->file('images') as $image)
                {
                    $name = rand().time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $name);
                    WatchRequestImage::create(['watch_request_id'=>$create_request->id,'image'=>$name]);
                }
                DB::commit();

                return response()->json(['status' => true,'message'=>"Request submited successfully",'error'=>false],200);
            }else{
                DB::rollBack();
                return response()->json(['status' => false,'message'=>"Request not submited at this time. Please try again later.",'error'=>true],200);
            }


        }catch(Exception $e){

            return response()->json(['status' => false, 'message' =>$e->getMessage() , 'error' => true], 200);
        }

    }
    
    public function testImageUpoad(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name'                    =>  "required",
                'images'                    =>  "required",
                'images.*'                  =>  'image|mimes:jpeg,png,jpg,gif'

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
                // foreach($request->file('images') as $image)
                // {
                //     $name = rand().time().'.'.$image->getClientOriginalExtension();
                //     $destinationPath = public_path('/images');
                //     $image->move($destinationPath, $name);
                //     WatchRequestImage::create(['watch_request_id'=>$create_request->id,'image'=>$name]);
                // }
                

                return response()->json([ 'status' => true, "count"=>count($request->file('images')),'message'=>"Request submited successfully",'error'=>false],200);
            


        }catch(Exception $e){

            return response()->json(['status' => false, 'message' =>$e->getMessage() , 'error' => true], 200);
        }

    }
}
