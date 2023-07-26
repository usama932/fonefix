<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use DB;
use Auth;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponser;

class BrandController extends ApiController
{
    use ApiResponser;

    public function addBrand(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $data = new Brand();
            $data->name = $input['name'];
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
                'message' => "Great! Brand has been saved successfully!",
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

    public function updateBrand(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            DB::beginTransaction();
            $data = Brand::findOrFail($request->id);
            $data->name = $input['name'];
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
                'message' => "Great! Brand has been saved successfully!",
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

    public function deleteBrand(Request $request)
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
            $data = Brand::find($id);
            if (!$data) {
                return response([
                    'message' => "Brand not Found",
                    'error' => true
                ], 200);
            }

            $data->delete();
            DB::commit();
            return response([
                'message' => "Brand Deleted Successfully",
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
            $data = Brand::where("user_id",$user_id)->get();
            (new FastExcel($data))->export("api-brands$user_id.csv", function ($pass) {

                return [
                    'Name' => $pass->name,
                    'Created At' => $pass->created_at,
                ];

            });
            $url = url("public/api-brands$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Brands Export successfully", 'error' => false], 200);
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
                        $user = Brand::where([["name",$line['Name']],["user_id",$user_id]])->first();
                        if (!$user){
                            $user = new Brand();
                        }
                        $user->name = $line['Name'];
                        $user->user_id = $user_id;
                        return $user->save();

                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Brands Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function getBrand(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Brand::orderBy('brands.id', "Desc")
                    ->select(
                        'brands.*'
//                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Brand::orderBy('id', "Desc")
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Brand::orderBy('brands.id', "Desc")->where("brands.user_id", $user->id)
                    ->select(
                        'brands.*'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Brand::orderBy('id', "Desc")->where("user_id", $user->id)
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Brand::orderBy('brands.id', "Desc")->where("brands.user_id", $user->parent_id)
                    ->select(
                        'brands.*'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Brand::orderBy('id', "Desc")->where("user_id", $user->parent_id)
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

    public function searchBrand(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Brand::orderBy('brands.id', "Desc")
                    ->where([['brands.name', 'like', "%{$request->keyword}%"]])
                    ->select(
                        'brands.*'
                    )
                    ->get();

            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Brand::orderBy('brands.id', "Desc")->where("brands.user_id", $user->id)
                    ->where([['brands.name', 'like', "%{$request->keyword}%"], ["brands.user_id", $user->id]])
                    ->select(
                        'brands.*'
                    )
                    ->get();


            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Job::orderBy('brands.id', "Desc")->where("brands.user_id", $user->parent_id)
                    ->where([['brands.name', 'like', "%{$request->keyword}%"], ["brands.user_id", $user->parent_id]])
                    ->select(
                        'brands.*'
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

    public function getBrandDetailInfo(Request $request)
    {
        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'Brand_id' => 'required'
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            return response([
                "data" => $this->getBrandDetail($request->Brand_id),
                'message' => "shop enquires",
                'error' => false
            ], 200);

        } catch (Exception $e) {

        }
    }




}
