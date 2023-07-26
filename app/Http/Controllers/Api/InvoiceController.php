<?php

namespace App\Http\Controllers\Api;

use App\Models\BasicSetting;
use App\Models\Brand;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\UsePart;
use DB;
use Auth;
use Hash;
use Exception;
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

class InvoiceController extends ApiController
{
    use ApiResponser;

    public function getInvoices(Request $request){
        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Invoice::orderBy('invoices.id', "Desc")->whereNull('job_id')
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')

                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
                    )
                    ->skip($request->offset)->take(30)

                    ->get();


                $data_count = Invoice::orderBy('id', "Desc")->whereNull('job_id')
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Invoice::orderBy('invoices.id', "Desc")->where("invoices.user_id", $user->id)->whereNull('job_id')
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')

                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Invoice::orderBy('id', "Desc")->where("user_id", $user->id)->whereNull('job_id')
                    ->get()->count();
            }else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Invoice::orderBy('products.id', "Desc")->where("products.user_id", $user->parent_id)->whereNull('job_id')
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')

                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Invoice::orderBy('id', "Desc")->where("user_id", $user->parent_id)->whereNull('job_id')
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

    public function add(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'discount' => 'required',
                    'customer_id' => 'required',
                    'total' => 'required',
                    'products*' => 'required',
                    'quantity*' => 'required',
                    'payment_method' => 'required',
                    'discount_type' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            $invoice = new Invoice();
            $invoice->total = $request->total;
            $invoice->discount = $request->discount;
            if (auth()->user()->role == 1){
                $invoice->user_id =  auth()->user()->id;
            }
            if (auth()->user()->role == 2){
                $invoice->user_id =  auth()->user()->id;
            }
            if (auth()->user()->role == 3){
                $invoice->user_id =  auth()->user()->parent_id;
            }
            $invoice->discount_type = $request->discount_type;
            $invoice->payment_method = $request->payment_method;
            $invoice->customer_id = $request->customer_id;
            $invoice->job_id = $request->job_id;
            $invoice->number = date('YmdHis');
            $invoice->save();
            foreach ($input["products"] as $index => $product_id) {
                $product = Product::findOrFail($product_id);
                $user = new UsePart();
                $user->description = $product->name;
                $user->product_id = $product->id;
                $user->amount = $product->sale_price;
                $user->invoice_id = $invoice->id;
                $user->quantity = $input['quantity'][$index];
                $user->save();
                if ($product->manage_stock){
                    $product->decrement("quantity",$input['quantity'][$index]);
                    $product->save();
                }
            }
            $data = Invoice::where("id",$invoice->id)
                ->with("parts")->
                first();

            $customer = User::find($data->customer_id);
            $data->customer_name =$customer->name;
            $data->customer_phone = $customer->phone;
            $data->user_name = User::find($data->user_id)->name;
            $data->shop_info = BasicSetting::where("user_id", $data->user_id)->first();
            if ($data->payment_method == 1){
                $data->payment_type = "Cash";
            }elseif ($data->payment_method == 2){
                $data->payment_type = "Card";
            }else{
                $data->payment_type = "Credit";
            }
            return response([
                "data" => $data,
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
            $data = Invoice::find($id);
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

    public function getSingle(Request $request)
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
            $id = $request->id;
            $data = Invoice::where("id",$id)
                ->with("parts")->
                first();
            if (!$data) {
                return response([
                    'message' => "Record not Found",
                    'error' => true
                ], 200);
            }
            $customer = User::find($data->customer_id);
            $data->customer_name =$customer->name;
            $data->customer_phone = $customer->phone;
            $data->user_name = User::find($data->user_id)->name;
            $data->shop_info = BasicSetting::where("user_id", $data->user_id)->first();
            if ($data->payment_method == 1){
                $data->payment_type = "Cash";
            }elseif ($data->payment_method == 2){
                $data->payment_type = "Card";
            }else{
                $data->payment_type = "Credit";
            }
            return response([
                'data' => $data,
                'message' => "Record Found Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function searchInvoices(Request $request){
        try{
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Invoice::orderBy('invoices.id', "Desc")
                    ->where([['invoices.number', 'like', "%{$request->keyword}%"], ["invoices.job_id", null]])
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')

                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
                    )
                    ->get();

            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Invoice::orderBy('invoices.id', "Desc")->where("invoices.user_id", $user->id)
                    ->where([['invoices.number', 'like', "%{$request->keyword}%"], ["invoices.user_id", $user->id], ["invoices.job_id", null]])
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')
                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
                    )
                    ->get();

            }else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Invoice::orderBy('products.id', "Desc")->where("products.user_id", $user->parent_id)->whereNull('job_id')
                    ->where([['invoices.number', 'like', "%{$request->keyword}%"], ["invoices.user_id", $user->parent_id], ["invoices.job_id", null]])
                    ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')
                    ->select(
                        'invoices.*',
                        'users.name as customer_name'
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

}
