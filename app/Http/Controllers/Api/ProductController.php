<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Compatible;
use App\Models\DeviceCompatible;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ShopUser;
use App\Models\UserBrand;
use App\Models\UserDevice;
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

class ProductController extends ApiController
{
    use ApiResponser;

    public function getProducts(Request $request){
        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Product::orderBy('products.id', "Desc")
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)

                    ->get();


                $data_count = Product::orderBy('id', "Desc")
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Product::orderBy('products.id', "Desc")->where("products.user_id", $user->id)
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Product::orderBy('id', "Desc")->where("user_id", $user->id)
                    ->get()->count();
            }else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Product::orderBy('products.id', "Desc")->where("products.user_id", $user->parent_id)
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Product::orderBy('id', "Desc")->where("user_id", $user->parent_id)
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
        }catch(Exception $e){
            return response([

                'message' => $e,
                'error' => true
            ],200);
        }
    }
    public function getAllProducts(Request $request){
        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Product::orderBy('products.id', "Desc")
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.id',
                        'products.quantity',
                        'products.name',
                        'products.sale_price',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )

                    ->get();


            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Product::orderBy('products.id', "Desc")->where("products.user_id", $user->id)
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.id',
                        'products.quantity',
                        'products.sale_price',
                        'products.name',
                        'brands.name as brand_name'
                    )

                    ->get();

            }else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Product::orderBy('products.id', "Desc")->where("products.user_id", $user->parent_id)
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.id',
                        'products.quantity',
                        'products.sale_price',
                        'products.name',
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
                    'category_id' => 'required',
                    //		    'price' => 'required|numeric',
                    //		    'sku' => 'required',
                    'description' => 'required',
                    //		    'shop' => 'required',
                    'purchase_price' => 'required|numeric',
                    'sale_price' => 'required|numeric',
                    'images*' => 'required|image|mimes:jpeg,png,jpg',
                    'devices*' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $data = new Product();

            $data->active = $request->active;
            $data->reason =  $request->disable_reason;
            $data->product_description = $request->product_description;
            $data->imei =  $request->imei;
            $data->serial_number =  $request->serial_number;
            $data->manage_stock = $request->manage_stock;
            $data->quantity = $request->quantity;
            $data->alert_quantity = $request->alert_quantity;
            $data->opening_stock = $request->opening_stock;
            $data->opening_stock_quantity = $request->quantity;
            $data->opening_stock_timestamp = date('Y-m-d H:i:s');
            $data->not_for_sale = $request->not_for_sale;
            $data->name = $input['name'];
            $data->sale_price = $input['sale_price'];
            $data->purchase_price = $input['purchase_price'];
            $data->minimum_sale_price = $input['minimum_sale_price'];
            $data->maximum_discount = $input['maximum_discount'];
            $data->margin = $input['margin'];
            if ($request->sku){
                $data->sku = $input['sku'];

            }else{
                $data->sku = $this->genrateRandomInteger();
            }
            $data->short_description = $input['short_description'];
            $data->description = $input['description'];
            $data->warranty = $input['warranty'];
            $data->brand_id = $input['brand_id'];
            $data->location_rack = $input['location_rack'];
            $data->location_row = $input['location_row'];
            $data->location_position = $input['location_position'];
            $data->category_id = $input['category_id'];

            if (auth()->user()->role == 2){
                $data->user_id = auth()->user()->id;
            }elseif (auth()->user()->role == 3){
                $data->user_id = auth()->user()->parent_id;
            }
//            if ($request->hasFile('image')) {
//                if ($request->file('image')->isValid()) {
//                    $this->validate($request, [
//                        'image' => 'required|image|mimes:jpeg,png,jpg'
//                    ]);
//                    $file = $request->file('image');
//                    $destinationPath = public_path('/uploads');
//                    //$extension = $file->getClientOriginalExtension('logo');
//                    $image = $file->getClientOriginalName('image');
//                    $image = rand().$image;
//                    $request->file('image')->move($destinationPath, $image);
//                    $data->image = $image;
//
//                }
//            }
            $data->save();
            $data->devices()->sync($request->devices,false);
            if ($request->hasFile('images')) {
                $allowedfileExtension = [ 'jpg', 'png', 'svg', 'jpeg', 'gif'];
                $files = $request->file('images');

                foreach ($files as $key => $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    //$file->move('storage/photos', $filename);
                    $check = in_array($extension, $allowedfileExtension);
                    $fullpath = $filename . '.' . $extension ; // adding full path

                    if ($check) {
                        // removing 2nd loop
                        $destinationPath = public_path('/uploads');
                        $file->move($destinationPath, $filename); // you should include extension here for retrieving in blade later
                        $img = new ProductImage();
                        $img->product_id = $data->id;
                        $img->image = $filename;
                        $img->thumb = $input['thumb'][$key];
                        $img->save();
                    }else {
                        return response([

                            'message' => "warning!  Sorry Only Upload png , jpg , jpeg ,svg ,gif ",
                            'error' => true
                        ], 200);
                    }
                }
            }
            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Record has been saved successfully!",
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
    public function update(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'category_id' => 'required',
                    //		    'price' => 'required|numeric',
                    //		    'sku' => 'required',
                    'description' => 'required',
                    //		    'shop' => 'required',
                    'purchase_price' => 'required|numeric',
                    'sale_price' => 'required|numeric',
                    'images*' => 'required|image|mimes:jpeg,png,jpg',
                    'devices*' => 'required',
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $data = Product::findOrFail($request->id);
            if (!$data) {
                return response([
                    'message' => "Record not Found",
                    'error' => true
                ], 200);
            }
            $data->active = $request->active;
            $data->reason =  $request->disable_reason;
            $data->product_description = $request->product_description;
            $data->imei =  $request->imei;
            $data->serial_number =  $request->serial_number;
            $data->manage_stock = $request->manage_stock;
            $data->quantity = $request->quantity;
            $data->alert_quantity = $request->alert_quantity;
            $data->opening_stock = $request->opening_stock;
            $data->opening_stock_quantity = $request->quantity;
            $data->opening_stock_timestamp = date('Y-m-d H:i:s');
            $data->not_for_sale = $request->not_for_sale;
            $data->name = $input['name'];
            $data->sale_price = $input['sale_price'];
            $data->purchase_price = $input['purchase_price'];
            $data->minimum_sale_price = $input['minimum_sale_price'];
            $data->maximum_discount = $input['maximum_discount'];
            $data->margin = $input['margin'];
            if ($request->sku){
                $data->sku = $input['sku'];

            }else{
                $data->sku = $this->genrateRandomInteger();
            }
            $data->short_description = $input['short_description'];
            $data->description = $input['description'];
            $data->warranty = $input['warranty'];
            $data->brand_id = $input['brand_id'];
            $data->location_rack = $input['location_rack'];
            $data->location_row = $input['location_row'];
            $data->location_position = $input['location_position'];
            $data->category_id = $input['category_id'];

            if (auth()->user()->role == 2){
                $data->user_id = auth()->user()->id;
            }elseif (auth()->user()->role == 3){
                $data->user_id = auth()->user()->parent_id;
            }
//            if ($request->hasFile('image')) {
//                if ($request->file('image')->isValid()) {
//                    $this->validate($request, [
//                        'image' => 'required|image|mimes:jpeg,png,jpg'
//                    ]);
//                    $file = $request->file('image');
//                    $destinationPath = public_path('/uploads');
//                    //$extension = $file->getClientOriginalExtension('logo');
//                    $image = $file->getClientOriginalName('image');
//                    $image = rand().$image;
//                    $request->file('image')->move($destinationPath, $image);
//                    $data->image = $image;
//
//                }
//            }
            $data->save();
            $data->devices()->sync($request->devices,false);
            if ($request->hasFile('images')) {
                $allowedfileExtension = [ 'jpg', 'png', 'svg', 'jpeg', 'gif'];
                $files = $request->file('images');

                foreach ($files as $key => $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    //$file->move('storage/photos', $filename);
                    $check = in_array($extension, $allowedfileExtension);
                    $fullpath = $filename . '.' . $extension ; // adding full path

                    if ($check) {
                        // removing 2nd loop
                        $destinationPath = public_path('/uploads');
                        $file->move($destinationPath, $filename); // you should include extension here for retrieving in blade later
                        $img = new ProductImage();
                        $img->product_id = $data->id;
                        $img->image = $filename;
                        $img->thumb = $input['thumb'][$key];
                        $img->save();
                    }else {
                        return response([

                            'message' => "warning!  Sorry Only Upload png , jpg , jpeg ,svg ,gif ",
                            'error' => true
                        ], 200);
                    }
                }
            }
            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Record has been saved successfully!",
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

    function genrateRandomInteger($len = 6)
    {
        $last = -1;
        $code = '';
        for ($i = 0; $i < $len; $i++) {
            do {
                $next_digit = mt_rand(0, 9);
            } while ($next_digit == $last);
            $last = $next_digit;
            $code .= $next_digit;
        }
        $code = "FF-" . $code;
        $sku = Product::where("sku", $code)->first();
        if ($sku) {
            $this->genrateRandomInteger();
        }
        return $code;
    }

    public function searchProducts(Request $request){
        try{
            $data = auth()->user();
            if ($data->is_admin == 1 && $data->role == 1) {
                $data = Product::orderBy('products.id', "Desc")
                    ->where([['products.name', 'like', "%{$request->keyword}%"]])
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )

                    ->get();

            } else if ($data->is_admin == 1 && $data->role == 2) {

                $data = Product::orderBy('products.id', "Desc")
                    ->where([['products.name', 'like', "%{$request->keyword}%"], ["products.user_id", $data->id]])
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
                        'brands.name as brand_name'
                    )

                    ->get();


            }else if ($data->is_admin == 1 && $data->role == 3) {

                $data = Product::orderBy('products.id', "Desc")
                    ->where([['products.name', 'like', "%{$request->keyword}%"], ["products.user_id", $data->parent_id]])
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')

                    ->select(
                        'products.*',
//                        'enquiries.*'
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
        }catch(Exception $e){
            return response([

                'message' => $e,
                'error' => true
            ],200);
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

                        $user = Product::where([["sku",$line['Sku']],["user_id",$user_id]])->first();
                        if (!$user){
                            $user = new Product();
                        }
                        $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();

                        if (!$brand){
                            $brand = new Brand();
                            $brand->name = $line['Brand'];
                            $brand->user_id = $user_id;
                            $brand->save();
                        }
                        $category = Category::where([["name",$line['Category']],["user_id",$user_id]])->first();

                        if (!$category){
                            $category = new Category();
                            $category->name = $line['Category'];
                            $category->user_id = $user_id;
                            $category->save();
                        }
                        if ($line['Active'] == 'Yes'){
                            $user->active = 1;
                        }else{
                            $user->active = 0;
                        }
                        if ($line['Manage Stock'] == 'Yes'){
                            $user->manage_stock = 1;
                        }else{
                            $user->manage_stock = 0;
                        }
                        if ($line['Product Description'] == 'Yes'){
                            $user->product_description = 1;
                        }else{
                            $user->product_description = 0;
                        }
                        $user->name = $line['Name'];
                        $user->sku = $this->genrateRandomInteger();
                        $user->imei = $line['IMEI'];
                        $user->serial_number = $line['Serial Number'];
                        $user->alert_quantity = $line['Alert Quantity'];
                        $user->quantity = $line['Quantity'];
                        $user->location_position = $line['Location Position'];
                        $user->location_row = $line['Location Row'];
                        $user->location_rack = $line['Location Rack'];
                        $user->warranty = $line['Warranty'];
                        $user->description = $line['Description'];
                        $user->short_description = $line['Short Description'];
                        $user->maximum_discount = $line['Maximum Discount'];
                        $user->minimum_sale_price = $line['Minimum Sale Price'];
                        $user->margin = $line['Margin'];
                        $user->sale_price = $line['Sale Price'];
                        $user->purchase_price = $line['Purchase Price'];
                        $user->category_id = $category->id;
                        $user->brand_id = $brand->id;
                        $user->user_id = $user_id;
                        return $user->save();

                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Products Import successfully", 'error' => false], 200);
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
            $data = Product::where("user_id",$user_id)->get();
            (new FastExcel($data))->export("api-products$user_id.csv", function ($pass) {
                if($pass->active == 1){
                    $active = 'Yes';
                }else{
                    $active = 'No';
                }
                if($pass->manage_stock == 1){
                    $manage = 'Yes';
                }else{
                    $manage = 'No';
                }
                if($pass->product_description == 1){
                    $product_description = 'Yes';
                }else{
                    $product_description = 'No';
                }
                if($pass->brand_id){
                    $record = Brand::findOrFail($pass->brand_id);
                    $brand = $record->name;
                }else{
                    $brand = '';
                }
                if($pass->category_id){
                    $record = Category::findOrFail($pass->category_id);
                    $category = $record->name;
                }else{
                    $category = '';
                }
                return [
                    'Name' => $pass->name,
                    'Brand' => $brand,
                    'Sku' => $pass->sku,
                    'Purchase Price' => $pass->purchase_price,
                    'Sale Price' => $pass->sale_price,
                    'Margin' => $pass->margin,
                    'Minimum Sale Price' => $pass->minimum_sale_price,
                    'Maximum Discount' => $pass->maximum_discount,
                    'Short Description' => $pass->short_description,
                    'Description' => $pass->description,
                    'Warranty' => $pass->warranty,
                    'Location Rack' => $pass->location_rack,
                    'Location Row' => $pass->location_row,
                    'Location Position' => $pass->location_position,
                    'Quantity' => $pass->quantity,
                    'Alert Quantity' => $pass->alert_quantity,
                    'Serial Number' => $pass->serial_number,
                    'IMEI' => $pass->imei,
                    'Product Description' => $product_description,
                    'Category' => $category,
                    'Manage Stock' => $manage,
                    'Active' => $active,

                ];

            });
            $url = url("public/api-products$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Products Export successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }

    }

    public function makeDefault(Request $request)
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
            $data = ProductImage::find($id);
            if (!$data) {
                return response([
                    'message' => "Record not Found",
                    'error' => true
                ], 200);
            }

            $old_images = ProductImage::where("product_id", $data->product_id)->get();
            foreach ($old_images as $old_image) {
                $old_image->thumb = 0;
                $old_image->save();
            }
            $data->thumb = 1;
            $data->save();
            DB::commit();
            return response([
                'message' => "Record Saved Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
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
            $data = Product::find($id);
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

    public function deleteImage(Request $request)
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
            $data = ProductImage::find($id);
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
