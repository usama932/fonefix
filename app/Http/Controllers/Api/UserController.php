<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Brand;
use App\Models\Bucket;
use App\Models\Country;
use App\Models\Device;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Provinces;
use App\Models\RequestedEvent;
use App\Models\SearchHistory;
use App\Models\ShopUser;
use App\Models\User;
use App\Models\UserBrand;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use Response;

class UserController extends ApiController
{
    public function searchShops(Request $request)
    {

        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = User::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([['role', 2], ["is_admin", 1], ['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } else {
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function searchUsers(Request $request)
    {

        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = User::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([["is_admin", 0], ['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 2) {
                $id = auth()->user()->id;
                $data = User::orderBy("users.id", "Desc")
                    ->select('name', 'users.id')
                    ->where([["is_admin", 0], ['name', 'like', "%{$request->keyword}%"]])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 3) {
                $id = auth()->user()->parent_id;
                $data = User::orderBy("users.id", "Desc")
                    ->select('name', 'users.id')
                    ->where([["is_admin", 0], ['name', 'like', "%{$request->keyword}%"]])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function searchBrands(Request $request)
    {

        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Brand::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([['name', 'like', "%{$request->keyword}%"]])
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 2) {
                $id = auth()->user()->id;
                $data = Brand::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([['name', 'like', "%{$request->keyword}%"], ["user_id", $user->id]])
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 3) {
                $id = auth()->user()->parent_id;
                $data = Brand::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([['name', 'like', "%{$request->keyword}%"], ["user_id", $user->parent_id]])
                    ->get();

                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }


        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function getModels(Request $request)
    {

        try {
            $user = auth()->user();

            $data = Device::orderBy("id", "Desc")
                ->where([['type', $request->type], ['brand_id', $request->brand_id]])
                ->get();

            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function addUser(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:55',
                'email' => 'email|required',
                'phone_no' => 'required'

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json(['status' => false, 'message' => "Email Already Exist", 'error' => true], 200);
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone_no;
            $user->password = bcrypt("12345607");
            if (auth()->user()->role == 2) {
                $user->parent_id = auth()->user()->id;
            }
            $user->save();
            if (auth()->user()->role == 2) {
                $shop_user = ShopUser::where([["user_id", auth()->user()->id], ["customer_id", $user->id]])->first();
                if (!$shop_user) {
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = auth()->user()->id;
                    $shop_user->save();
                }
            } elseif (auth()->user()->role == 3) {
                $shop_user = ShopUser::where([["user_id", auth()->user()->parent_id], ["customer_id", $user->id]])->first();
                if (!$shop_user) {
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = auth()->user()->parent_id;
                    $shop_user->save();
                }
            }
            DB::commit();

            // $data = User::where('id',$user->id)->first()->pluck('id','name','email','phone');
            $data = User::where('id', $user->id)->orderBy("id", "Desc")
                ->select('name', 'id', 'email', 'phone')
                ->first();
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => $data, 'message' => "User added successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function getGenericData(Request $request)
    {

        try {
            $user = auth()->user();


            $devices = array(
                array("id" => 1, "name" => "Mobile"),
                array("id" => 2, "name" => "Laptop"),
            );


            if ($user->is_admin == 1 && $user->role == 1) {
                $id = $user->id;
                $countries = Country::orderBy("id", "Desc")
                    ->select('name', 'countries.id')
                    ->join('country_user', function ($join) use ($id) {
                        $join->on('country_user.country_id', '=', 'countries.id')
                            ->where('country_user.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();

                $brands = Brand::orderBy("id", "Desc")
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();


                return response([
                    'countries' => $countries,
                    'brands' => $brands,
                    'devices' => $devices,

                    'message' => "Records Found",
                    'error' => false
                ], 200);
            } elseif ($user->is_admin == 1 && $user->role == 2) {
                $id = $user->id;
                $countries = Country::orderBy("id", "Desc")
                    ->select('name', 'countries.id')
                    ->join('country_user', function ($join) use ($id) {
                        $join->on('country_user.country_id', '=', 'countries.id')
                            ->where('country_user.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();

                $brands = Brand::orderBy("id", "Desc")
                    ->where("user_id", $id)
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();


                return response([
                    'countries' => $countries,
                    'brands' => $brands,
                    'devices' => $devices,

                    'message' => "Records Found",
                    'error' => false
                ], 200);
            } elseif ($user->is_admin == 1 && $user->role == 3) {
                $id = $user->parent_id;
                $countries = Country::orderBy("id", "Desc")
                    ->select('name', 'countries.id')
                    ->join('country_user', function ($join) use ($id) {
                        $join->on('country_user.country_id', '=', 'countries.id')
                            ->where('country_user.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();

                $brands = Brand::orderBy("id", "Desc")
                    ->where("user_id", $id)
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();


                return response([
                    'countries' => $countries,
                    'brands' => $brands,
                    'devices' => $devices,

                    'message' => "Records Found",
                    'error' => false
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function getCountryProvinces(Request $request)
    {

        try {
            $country_id = $request->id;
            $user = auth()->user();


            if ($user->is_admin == 1 && $user->role == 1) {
                $id = $user->id;
                $data = Provinces::orderBy("id", "Desc")
                    ->where("provinces.country_id", $country_id)
                    ->select('name', 'provinces.id')
                    ->join('user_provinces', function ($join) use ($id) {
                        $join->on('user_provinces.province_id', '=', 'provinces.id')
                            ->where('user_provinces.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();


                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 2) {
                $id = $user->id;
                $data = Provinces::orderBy("id", "Desc")
                    ->where("provinces.country_id", $country_id)
                    ->select('name', 'provinces.id')
                    ->join('user_provinces', function ($join) use ($id) {
                        $join->on('user_provinces.province_id', '=', 'provinces.id')
                            ->where('user_provinces.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();


                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            } elseif ($user->is_admin == 1 && $user->role == 3) {
                $id = $user->parent_id;
                $data = Provinces::orderBy("id", "Desc")
                    ->where("provinces.country_id", $country_id)
                    ->select('name', 'provinces.id')
                    ->join('user_provinces', function ($join) use ($id) {
                        $join->on('user_provinces.province_id', '=', 'provinces.id')
                            ->where('user_provinces.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();


                if ($data->isNotEmpty()) {
                    return response([
                        'data' => $data,
                        'message' => "Records",
                        'error' => false
                    ], 200);
                } else {
                    return response([
                        'data' => [],
                        'message' => "Records Not Found",
                        'error' => true
                    ], 200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function addFullUser(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:55',
                'email' => 'email|required',
                'phone_no' => 'required',

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }


            $user = User::where("email", $request->email)->first();
            if (!$user) {
                $user = new User();
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->country_id = $request->country_id;
            $user->province_id = $request->province_id;
            $user->city = $request->city;
            $user->postal_code = $request->postal_code;
            $user->line1 = $request->line1;
            $user->line2 = $request->line2;
            $user->phone = $request->phone_no;
            $user->alternative_phone = $request->alternative_phone;
            $user->location = $request->location;

            $user->active = $request->active;
            if ($request->active == 0) {
                $user->disable_reason = $request->disable_reason;
            }
            $user->password = bcrypt("12345607");
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            if (auth()->user()->role == 2) {
                $user->parent_id = auth()->user()->id;
            }
            $user->save();
            if (auth()->user()->role == 2) {
                $shop_user = ShopUser::where([["user_id", auth()->user()->id], ["customer_id", $user->id]])->first();
                if (!$shop_user) {
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = auth()->user()->id;
                    $shop_user->save();
                }
            } elseif (auth()->user()->role == 3) {
                $shop_user = ShopUser::where([["user_id", auth()->user()->parent_id], ["customer_id", $user->id]])->first();
                if (!$shop_user) {
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = auth()->user()->parent_id;
                    $shop_user->save();
                }
            }
            if ($request->devices) {
                $devices = json_decode($request->devices);

                foreach ($devices as $device) {
                    $brand = new UserBrand();
                    if ($device->id != 0) {
                        $brand = UserBrand::findOrFail($device->id);
                    }
                    $old_brands = UserBrand::where([["brand_id", $device->brandId], ["user_id", $user->id]])->get();
                    foreach ($old_brands as $bra) {
                        $bra->delete();
                    }
                    $old_devices = UserDevice::where([["brand_id", $device->brandId], ["user_id", $user->id]])->get();
                    foreach ($old_devices as $dev) {
                        $dev->delete();
                    }
                    $brand->brand_id = $device->brandId;
                    $brand->device = $device->deviceTypeID;
                    $brand->user_id = $user->id;
                    $brand->save();
                    foreach ($device->deviceIDs as $deviceID) {
                        $save_id = new UserDevice();
                        $save_id->user_brand_id = $brand->id;
                        $save_id->brand_id = $device->brandId;
                        $save_id->user_id = $user->id;
                        $save_id->device_id = $deviceID;
                        $save_id->save();
                    }
                }
            }

            DB::commit();

            // $data = User::where('id',$user->id)->first()->pluck('id','name','email','phone');
            $data = User::where('id', $user->id)->orderBy("id", "Desc")
                ->select('name', 'id', 'email', 'phone')
                ->first();

            return response()->json(['status' => true, 'data' => $user, 'message' => "User added successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
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
            $data = User::join('shop_users', function ($join) use ($user_id) {
                $join->on('shop_users.customer_id', '=', 'users.id')
                    ->where('shop_users.user_id', '=', $user_id);
            })
            ->select(
                'users.*'
            )
            ->get();
            (new FastExcel($data))->export("api-clients$user_id.csv", function ($pass) {
                return [
                    'Name' => $pass->name,
                    'Phone' => $pass->phone,
                    'Alternative Phone' => $pass->alternative_phone,
                    'Address Line 1' => $pass->line1,
                    'Address Line 2' => $pass->line2,
                    'City' => $pass->city,
                    'PostalCode' => $pass->postal_code,
                    'Email' => $pass->email,
                    'Location' => $pass->location,
                ];
            });
            $url = url("public/api-clients$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Users Export successfully", 'error' => false], 200);
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

                        $user = User::where([["email", $line['Email']]])->first();
                        if (!$user) {
                            $user = new User();
                            $user->password = bcrypt("12345607");
                        }

                        $user->active = 1;


                        $user->name = $line['Name'];
                        $user->phone = $line['Phone'];
                        $user->alternative_phone = $line['Alternative Phone'];
                        $user->line1 = $line['Address Line 1'];
                        $user->line2 = $line['Address Line 2'];
                        $user->city = $line['City'];
                        $user->postal_code = $line['PostalCode'];
                        $user->email = $line['Email'];
                        $user->location = $line['Location'];
                        $user->save();
                        $shop_user = ShopUser::where([["user_id", $user_id], ["customer_id", $user->id]])->first();
                        if (!$shop_user) {
                            $shop_user = new ShopUser();
                            $shop_user->customer_id = $user->id;
                            $shop_user->user_id = $user_id;
                            $shop_user->save();
                        }
                        return $user;

                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Users Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function editUsers(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|max:55',
                'email' => 'email|required',
                'phone_no' => 'required'

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }


            $user = User::find($request->id);
            if (!$user) {
                return response()->json(['status' => false, 'message' => "User Not Found", 'error' => false], 200);

            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->country_id = $request->country_id;
            $user->province_id = $request->province_id;
            $user->city = $request->city;
            $user->postal_code = $request->postal_code;
            $user->line1 = $request->line1;
            $user->line2 = $request->line2;
            $user->phone = $request->phone_no;
            $user->alternative_phone = $request->alternative_phone;
            $user->location = $request->location;

            $user->active = $request->active;
            if ($request->active == 0) {
                $user->disable_reason = $request->disable_reason;
            }
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            if ($request->devices) {
                $devices = json_decode($request->devices);

                foreach ($devices as $device) {
                    $brand = new UserBrand();
                    if ($device->id != 0) {
                        $brand = UserBrand::findOrFail($device->id);
                    }
//                    $old_brands = UserBrand::where([["brand_id",$device->brandId],["user_id",$user->id]])->get();
//                    foreach($old_brands as $bra){
//                        $bra->delete();
//                    }
                    $old_devices = UserDevice::where([["brand_id", $device->brandId], ["user_id", $user->id]])->get();
                    foreach ($old_devices as $dev) {
                        $dev->delete();
                    }
                    $brand->brand_id = $device->brandId;
                    $brand->device = $device->deviceTypeID;
                    $brand->user_id = $user->id;
                    $brand->save();
                    foreach ($device->deviceIDs as $deviceID) {
                        $save_id = new UserDevice();
                        $save_id->user_brand_id = $brand->id;
                        $save_id->brand_id = $device->brandId;
                        $save_id->user_id = $user->id;
                        $save_id->device_id = $deviceID;
                        $save_id->save();
                    }
                }
            }
            DB::commit();

            // $data = User::where('id',$user->id)->first();
//            $data = User::where('id', $user->id)->orderBy("id", "Desc")
//                ->select('name', 'id', 'email', 'phone')
//                ->first();
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => $user, 'message' => "User Updated successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function getUsers(Request $request)
    {
        try {
            if (auth()->user()->role == 2) {
                $id = auth()->user()->id;
            } elseif (auth()->user()->role == 3) {
                $id = auth()->user()->parent_id;
            } else {
                $id = null;
            }
            if ($id) {
                $users = User::orderBy("id", "Desc")
                    ->select('name', 'users.id', 'email', 'phone')
                    ->where([["is_admin", 0]])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->skip($request->offset)
                    ->take(10)
                    ->get();
            } else {
                $users = User::orderBy("id", "Desc")
                    ->select('name', 'id', 'email', 'phone')
                    ->where([["is_admin", 0]])
                    ->skip($request->offset)
                    ->take(10)
                    ->get();
            }

            //if is_admin = 0  user
            // if is_amin = 1 and role =1 Super Admin
            // if is_amin = 1 and role =2 Shop
            // if is_amin = 1 and role =3 Staff


            // return $data;
            if ($users->isNotEmpty()) {
                return response([
                    'data' => $users,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'data' => [],
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
//            return response()->json(['status' => true, 'data' => $users, 'message' => "Users found successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function deleteUser(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $user = User::find($request->id);
            if (!$user) {
                return response()->json(['status' => false, 'message' => "Sorry!! User Not Found", 'error' => true], 200);
            }
            $shop_user = ShopUser::where("customer_id", $user->id)->first();
            $shop_user->delete();

            return response()->json(['status' => true, 'message' => "User Deleted Successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function deleteDevice(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $user = UserBrand::findOrFail($request->id);
            if (!$user) {
                return response()->json(['status' => false, 'message' => "Sorry!! Device Not Found", 'error' => true], 200);
            }

            $user->delete();

            return response()->json(['status' => true, 'message' => "Device Deleted Successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function userDetail(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $user = User::where("id", $request->id)->with('brands')->with('cards')->first();
            if (!$user) {
                return response()->json(['status' => false, 'message' => "Sorry!! User Not Found", 'error' => true], 200);
            }
            if ($user->country) {
                $user->country_name = $user->country->name;
            } else {
                $user->country_name = null;
            }
            if ($user->province) {
                $user->province_name = $user->province->name;
            } else {
                $user->province_name = null;
            }
            foreach ($user->brands as $brand) {
                $brand->brand_name = $brand->brand->name;
                if ($brand->device == 1) {
                    $brand->device_name = "Mobile Phone";

                } else {
                    $brand->device_name = "Laptop";
                }
                foreach ($brand->devices as $device) {
                    $device->device_name = $device->device->name;
                }
            }

            return response()->json(['status' => true, 'data' => $user, 'message' => "User found successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
}
