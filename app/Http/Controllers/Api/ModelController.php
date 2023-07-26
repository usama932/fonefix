<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Brand;
use DB;
use Auth;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponser;

class ModelController extends ApiController
{
    use ApiResponser;

    public function addDevice(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'brand_id' => 'required',
                    'type' => 'required',
                    'pre_repair' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $data = new Device();
            $data->name = $input['name'];
            $data->brand_id = $input['brand_id'];
            $data->type = $input['type'];
            $data->pre_repair = $input['pre_repair'];
            if (auth()->user()->role == 2) {
                $data->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $data->user_id = auth()->user()->parent_id;
            }
            $data->save();
            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Device has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }


    }

    public function updateDevice(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'id' => 'required',
                    'brand_id' => 'required',
                    'type' => 'required',
                    'pre_repair' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            DB::beginTransaction();
            $data = Device::findOrFail($request->id);
            $data->name = $input['name'];
            $data->brand_id = $input['brand_id'];
            $data->type = $input['type'];
            $data->pre_repair = $input['pre_repair'];
            if (auth()->user()->role == 2) {
                $data->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $data->user_id = auth()->user()->parent_id;
            }
            $data->save();

            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Device has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }


    }

    public function deleteDevice(Request $request)
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
            $data = Device::find($id);
            if (!$data) {
                return response([
                    'message' => "Device not Found",
                    'error' => true
                ], 200);
            }

            $data->delete();
            DB::commit();
            return response([
                'message' => "Device Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function export()
    {
        try {
            if (auth()->user()->role == 2) {
                $user_id = auth()->user()->id;
            } elseif (auth()->user()->role == 3) {
                $user_id = auth()->user()->parent_id;
            } elseif (auth()->user()->role == 1) {
                $user_id = auth()->user()->id;
            }
            $data = Device::where("user_id",$user_id)->get();
            (new FastExcel($data))->export("api-devices$user_id.csv", function ($pass) {
                $brand = Brand::findOrFail($pass->brand_id);
                if($pass->type == 1){
                    $type = 'Mobile';
                }else{
                    $type = 'Laptop';
                }
                return [
                    'Name' => $pass->name,
                    'Brand' => $brand->name,
                    'Type' => $type,
                    'Pre Repair' => $pass->pre_repair,
                    'Created At' => $pass->created_at,
                ];

            });
            $url = url("public/api-devices$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Devices Export successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }

    }

    public function importSave(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,txt,xlsx',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }


            $file = $request->file('file');
            if ($request->hasFile('file')) {
                if ($request->file('file')->isValid()) {
                    $destinationPath = "uploads/users/";
                    $extension = $file->getClientOriginalExtension('file');
                    $fileName = $file->getClientOriginalName('file'); // renameing image
                    $request->file('file')->move($destinationPath, $fileName);
                    $readFile = $destinationPath . $fileName;
//                $organization = auth()->user()->id;
//                $request->session()->put('organization', $organization);
                    $wfts = (new FastExcel)->import($readFile, function ($line) {
                        if (auth()->user()->role == 2) {
                            $user_id = auth()->user()->id;
                        } elseif (auth()->user()->role == 3) {
                            $user_id = auth()->user()->parent_id;
                        } else {
                            $user_id = auth()->user()->id;
                        }
                        $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();

                        if (!$brand){
                            $brand = new Brand();
                            $brand->name = $line['Brand'];
                            $brand->user_id = $user_id;
                            $brand->save();
                        }
                        $user = Device::where([["name",$line['Name']],["user_id",$user_id]])->first();
                        if (!$user){
                            $user = new Device();
                        }
                        if ($line['Type'] == 'Mobile'){
                            $user->type = 1;
                        }else{
                            $user->type = 2;
                        }
                        $user->name = $line['Name'];
                        $user->pre_repair = $line['Pre Repair'];
                        $user->brand_id = $brand->id;
                        $user->user_id = $user_id;
                        return $user->save();
                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Devices Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function getDevice(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Device::orderBy('devices.id', "Desc")
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Device::orderBy('id', "Desc")
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Device::orderBy('devices.id', "Desc")->where("devices.user_id", $user->id)
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Device::orderBy('id', "Desc")->where("user_id", $user->id)
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Device::orderBy('devices.id', "Desc")->where("devices.user_id", $user->parent_id)
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Device::orderBy('id', "Desc")->where("user_id", $user->parent_id)
                    ->get()->count();
            }
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
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }
    }

    public function searchDevice(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Device::orderBy('devices.id', "Desc")
                    ->where([['devices.name', 'like', "%{$request->keyword}%"]])
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->get();

            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Device::orderBy('devices.id', "Desc")->where("devices.user_id", $user->id)
                    ->where([['devices.name', 'like', "%{$request->keyword}%"], ["devices.user_id", $user->id]])
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->get();


            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Job::orderBy('devices.id', "Desc")->where("devices.user_id", $user->parent_id)
                    ->where([['devices.name', 'like', "%{$request->keyword}%"], ["devices.user_id", $user->parent_id]])
                    ->leftJoin('brands', 'devices.brand_id', '=', 'brands.id')
                    ->select(
                        'devices.*',
                        'brands.name as brand_name'
                    )
                    ->get();

            }
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
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }
    }

    public function getDeviceDetailInfo(Request $request)
    {
        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'brand_id' => 'required'
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            return response([
                "data" => $this->getDeviceDetail($request->brand_id),
                'message' => "shop enquires",
                'error' => false
            ], 200);

        } catch (Exception $e) {

        }
    }




}
