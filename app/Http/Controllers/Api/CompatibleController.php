<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Compatible;
use App\Models\Device;
use App\Models\DeviceCompatible;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use DB;
use Auth;
use Hash;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use App\Models\User;
use App\Models\Enquiry;
use Twilio\Rest\Client;
use App\Models\EnquiryBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Session;
use App\Traits\ApiResponser;

class CompatibleController extends ApiController
{
    use ApiResponser;

    public function getCompatibles(Request $request){
        try{
            $user = auth()->user();

            $data = Compatible::orderBy('compatibles.id', "Desc")->where('type_id',$request->type_id)
                ->skip($request->offset)->take(30)
                ->with('devicesApi')
                ->get();


            $data_count = Compatible::orderBy('compatibles.id', "Desc")->where('type_id',$request->type_id)
                ->get()->count();

            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'count' => $data_count,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        }catch(Exception $e){
            return response([

                'message' => $e,
                'error' => true
            ],200);
        }
    }

    public function searchCompatibles(Request $request){
        try{
            $user = auth()->user();

            $data = Compatible::orderBy('compatibles.id', "Desc")
                ->where([['compatibles.name', 'like', "%{$request->keyword}%"], ['type_id',$request->type_id]])
                ->skip($request->offset)->take(30)
                ->with('devicesApi')
                ->get();


            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        }catch(Exception $e){
            return response([

                'message' => $e,
                'error' => true
            ],200);
        }

    }

    public function add(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'type_id' => 'required',
                    'devices*' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            $data = new Compatible();
            $data->name = $input['name'];
            $data->type_id = $input['type_id'];
            $data->save();
            if($request->devices) {
                foreach ($request->devices as $device) {
                    $add_device = new DeviceCompatible();
                    $add_device->device_id = $device;
                    $add_device->compatible_id = $data->id;
                    $add_device->save();
                }
            }
            return response([
                "data" => $this->getAdded($data->id),
                'message' => "Great! Record has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }

    }

    public function export(Request $request)
    {
        try {
            if (auth()->user()->role == 2) {
                $user_id = auth()->user()->id;
            } elseif (auth()->user()->role == 3) {
                $user_id = auth()->user()->parent_id;
            } elseif (auth()->user()->role == 1) {
                $user_id = auth()->user()->id;
            }
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'type_id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $data = Type::find($request->type_id);
            if (!$data) {
                return response([
                    'message' => "Record not Found",
                    'error' => true
                ], 200);
            }
            $data = Compatible::where("type_id",$request->type_id)->get();
            (new FastExcel($data))->export("api-compatibles$user_id.csv", function ($pass) {
                $compatibles = DeviceCompatible::where("compatible_id",$pass->id)->get();
                $all = "";
                foreach($compatibles as $compatible) {
                    $device = Device::findOrFail($compatible->device_id);
                    if ($device){
                        $all = $all . $device->name ."/";
                    }
                }

                return [
                    'Name' => $pass->name,
                    'Compatible With' => $all,
                    'Created At' => $pass->created_at,
                ];

            });
            $url = url("public/api-compatibles$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Jobs Export successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }

    }

    public function update(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'type_id' => 'required',
                    'id' => 'required',
                    'devices*' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            $data = Compatible::findOrFail($request->id);
            $data->name = $input['name'];
            $data->type_id = $input['type_id'];
            $data->save();
            $olds = DeviceCompatible::where("compatible_id", $data->id)->get();
            foreach($olds as $old){
                $old->delete();
            }
            if($request->devices) {
                foreach ($request->devices as $device) {
                    $add_device = new DeviceCompatible();
                    $add_device->device_id = $device;
                    $add_device->compatible_id = $data->id;
                    $add_device->save();
                }
            }
            return response([
                "data" => $this->getAdded($data->id),
                'message' => "Great! Record has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }

    }

    public function getAdded($id)
    {
        return $data = Compatible::where('id',$id)
            ->with('devicesApi')
            ->first();
    }

    public function delete(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $id = $request->id;
            $data = Compatible::find($id);
            if (!$data) {
                return response([
                    'message' => "Record not Found",
                    'error' => true
                ], 200);
            }

            $data->delete();
            DB::commit();
            return response([
                'message' => "Record Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
