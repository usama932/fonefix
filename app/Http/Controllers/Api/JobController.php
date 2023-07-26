<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use App\Models\BasicSetting;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Device;
use App\Models\Enquiry;
use App\Models\EnquiryBrand;
use App\Models\IdCard;
use App\Models\Job;
use App\Models\JobPreRepair;
use App\Models\JobSetting;
use App\Models\PreRepair;
use App\Models\Setting;
use App\Models\ShopUser;
use App\Models\User;
use App\Models\UserCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Rap2hpoutre\FastExcel\FastExcel;
use Twilio\Rest\Client;
use Validator;
use App\Helper\JobResponse;
use App\Models\Courier;
use App\Models\Status;
use App\Models\Type;
use Response;
use PDF;

use App\Mail\DynamicSMTPMail;
use Illuminate\Support\Facades\Config;
use Swift_Mailer;
use Swift_SmtpTransport;

class JobController extends ApiController
{
    public $job_response;
    public function __construct()
    {
        $this->job_response = new JobResponse();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJobs(Request $request)
    {

        try {
            $job = auth()->user();
            if(!isset($request->status) || $request->status == 0) {

                if ($job->is_admin == 1 && $job->role == 1) {
                    $data = Job::orderBy('jobs.id', "Desc")
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )


                        ->skip($request->offset)->take(30)

                        ->get();

                    // $status = array(
                    //     array("id" => 1, "name" => "Accepted"),
                    //     array("id" => 2, "name" => "Progressing"),
                    //     array("id" => 3, "name" => "Completed"),
                    // );
                    $statuses = Status::where("user_id",auth()->user()->id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);

                    $data_count = Job::orderBy('id', "Desc")
                        ->get()->count();
                } else if ($job->is_admin == 1 && $job->role == 2) {

                    $data = Job::orderBy('jobs.id', "Desc")->where("jobs.user_id", $job->id)
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )
                        ->skip($request->offset)->take(30)
                        ->get();
                    $statuses = Status::where("user_id",auth()->user()->id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);
                    $data_count = Job::orderBy('id', "Desc")->where("user_id", $job->id)
                        ->get()->count();
                }else if ($job->is_admin == 1 && $job->role == 3) {

                    $data = Job::orderBy('jobs.id', "Desc")->where("jobs.user_id", $job->parent_id)
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )
                        ->skip($request->offset)->take(30)
                        ->get();
                    $statuses = Status::where("user_id",auth()->user()->parent_id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);
                    $data_count = Job::orderBy('id', "Desc")->where("user_id", $job->parent_id)
                        ->get()->count();
                }
            }

            else{

                if ($job->is_admin == 1 && $job->role == 1) {
                    $data = Job::orderBy('jobs.id', "Desc")->where(["jobs.status_id" => $request->status])
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )


                        ->skip($request->offset)->take(30)

                        ->get();

                    // $status = array(
                    //     array("id" => 1, "name" => "Accepted"),
                    //     array("id" => 2, "name" => "Progressing"),
                    //     array("id" => 3, "name" => "Completed"),
                    // );
                    $statuses = Status::where("user_id",auth()->user()->id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);

                    $data_count = Job::orderBy('id', "Desc")->where(["jobs.status_id" => $request->status])
                        ->get()->count();
                } else if ($job->is_admin == 1 && $job->role == 2) {

                    $data = Job::orderBy('jobs.id', "Desc")->where([["jobs.user_id", $job->id],["jobs.status_id", $request->status]])
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )
                        ->skip($request->offset)->take(30)
                        ->get();
                    $statuses = Status::where("user_id",auth()->user()->id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);
                    $data_count = Job::orderBy('id', "Desc")->where([["jobs.user_id", $job->id],["jobs.status_id", $request->status]])
                        ->get()->count();
                }else if ($job->is_admin == 1 && $job->role == 3) {

                    $data = Job::orderBy('jobs.id', "Desc")->where([["jobs.user_id", $job->parent_id],["jobs.status_id", $request->status]])
                        ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                        ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                        ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                        ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                        ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                        ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                        ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                        ->select(
                            'jobs.*',
                            'brands.name as brand_name',
                            'couriers.name as courier_name',
                            'id_cards.name as id_card_name',
                            'users.name as customer_name',
                            'statuses.name as status_name',
                            'statuses.color as status_color',
                            'statuses.id as status_id',
                            'invoices.id as invoice_id'
                        )
                        ->skip($request->offset)->take(30)
                        ->get();
                    $statuses = Status::where("user_id",auth()->user()->parent_id)->get();


                    $status = $statuses->map->only(['id', 'name', 'color']);
                    $data_count = Job::orderBy('id', "Desc")->where([["jobs.user_id", $job->parent_id],["jobs.status_id", $request->status]])
                        ->get()->count();
                }
            }
            // else{

            //     $data = Job::orderBy('jobs.id', "Desc")->where([["jobs.status_id", $request->status],["jobs.user_id", $job->id]])
            //     ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
            //     ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
            //     ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
            //     ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
            //     ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
            //     ->select(
            //         'jobs.*',
            //         'brands.name as brand_name',
            //         'couriers.name as courier_name',
            //         'id_cards.name as id_card_name',
            //         'users.name as customer_name'
            //     )
            //     ->skip($request->offset)->take(30)
            //     ->get();

            //     $statuses = Status::get();


            //     $status = $statuses->map->only(['id', 'name', 'color']);
            // $data_count = Job::orderBy('id', "Desc")->where("user_id", $job->id)
            //     ->get()->count();
            // }
            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'status' => $status,
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
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function addJobs(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
//                'shop_id' => 'required',
                'user_id' => 'required',
                'brand_id' => 'required',
                'device_model' => 'required',
                'serial_number' => 'required',
                'problem_by_customer' => 'required',
                'condition_of_product' => 'required',
                'cost' => 'required',
                // 'status' => 'required',
                'idCards*' => 'required|mimes:jpeg,png,jpg,doc,docx,pdf,pdfx',
                'add_jobsheet' => 'required',
                'profile' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $job = new Job();

            $input = $request->all();
            //
            if ($request->service_type == 4) {
                $job->tracking_id = $request->tracking_id;
                $job->courier_id = $request->courier_id;
            }
            if (auth()->user()->role == 2){
                $id = auth()->user()->id;

            }elseif (auth()->user()->role == 3){
                $id = auth()->user()->parent_id;

            }else{
                $id = $input['shop_id'];
            }
            $job->user_id           = $id;

            $sh = User::find($id);
            $job->job_sheet_number = "FF-".date('YmdHis');
            if ($sh->jobSetting) {
                if ($sh->jobSetting->jos_sheet_prefix) {
                    $prefix = $sh->jobSetting->jos_sheet_prefix;
                    $job->job_sheet_number = "$prefix" . date('YmdHis');
                }
            }

            $job->customer_id       = $input['user_id'];
            $job->service_type      = $input['service_type'];
            $job->brand_id          = $input['brand_id'];
            $job->device_id = $input['device_model'];
            $job->serial_number = $input['serial_number'];

            if ($request->product_configuration){
//                $job->product_configuration = implode(', ', $input['product_configuration']);
                $job->product_configuration = $request->product_configuration;
            }
            if ($request->problem_by_customer){
//                $job->problem_by_customer = implode(', ', $input['problem_by_customer']);
                $job->problem_by_customer = $request->problem_by_customer;
            }
            if ($request->condition_of_product){
//                $job->condition_of_product = implode(', ', $input['condition_of_product']);
                $job->condition_of_product = $request->condition_of_product;
            }
            $job->comment = $input['comment'];
            $job->cost = $input['cost'];
            $job->status_id = $input['status'];
            $job->expected_delivery = $input['expected_delivery'];
            $job->job_sheet_number = date('Y-m-d');
            $job->email = $input['notify_mail'];
            $job->sms = $input['notify_sms'];
            $job->description = $input['description'];
            if ($request->pattern) {
                $job->pattern = $input['pattern'];
            }
            if ($request->password) {
                $job->password = $input['password'];
            }

            $job->id_card_id = $input['id_card_id'];

            $job->save();


            $repair = $request->pre_repair;
            // $pre_repair = json_decode($repair, true);
            foreach (json_decode($repair) as $key => $val) {
                foreach ($val as $name => $value) {
                    $job_pre_repair = new JobPreRepair();
                    $job_pre_repair->name = $name;
                    $job_pre_repair->value = $value;
                    $job_pre_repair->job_id = $job->id;
                    $job_pre_repair->save();
                }
            }

            if ($request->hasFile('document')) {
                if ($request->file('document')->isValid()) {
                    $this->validate($request, [
                        'document' => 'required|mimes:jpeg,png,jpg,doc,docx,txt,pdf'
                    ]);
                    $file = $request->file('document');
                    $destinationPath = public_path('/uploads');
                    //$extension = $file->getClientOriginalExtension('logo');
                    $image = $file->getClientOriginalName('document');
                    $image = rand() . $image;

                    $request->file('document')->move($destinationPath, $image);
                    $job->document = $image;
                }
            }
            $job->save();


            $job_sheet = $request->add_jobsheet;


            if ($request->hasFile('idCards')) {
                $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'pdfx', 'doc'];
                $files = $request->file('idCards');
                $i=0;
                foreach ($files as $key => $file) {
                    // return $job_sheet[$key];
                    $filename = $file->getClientOriginalName();

                    $extension = $file->getClientOriginalExtension();


                    //$file->move('storage/photos', $filename);
                    $check = in_array($extension, $allowedfileExtension);
                    $fullpath = time().rand() . '.' . $extension; // adding full path

                    // return $fullpath;

                    if ($check) {
                        // removing 2nd loop
                        $destinationPath = public_path('/uploads');
                        $file->move($destinationPath, $fullpath); // you should include extension here for retrieving in blade later
                        $img = new UserCard();
                        $img->job_id = $job->id;
                        if ($request->profile) {
                            $img->user_id = Auth::id();
                        }

                        $img->use = $job_sheet[$i];
                        $img->id_card_id = $request->id_card_id;
                        $img->image = $fullpath;

                        $img->save();
                        $i++;
                    } else {
                        return response()->json(['message' => 'warning!  Sorry Only Upload png , jpg , doc', 'error' => true], 200);
                    }
                }
            }


            $url = route('job-pdf-public',[$job->id,$job->shop->name]);
            $status = $job->stat->name;
            $msg = "Your Job $status <br> <a href='$url'>Show Pdf</a>";


            $us = $job->customer;
            $sms_template = $job->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$job->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$job->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$job->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$job->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$job->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$job->device->name,$sms_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$job->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
//                $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }

            $whatsapp_template = $job->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$job->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$job->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$job->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$job->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$job->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$job->device->name,$whatsapp_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$job->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }





            $mail_template = $job->stat->email_body;
            $mail_template = str_replace("{customer_name}",$job->customer->name,$mail_template);
            $mail_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$mail_template);
            $mail_template = str_replace("{status}",$job->stat->name,$mail_template);
            $mail_template = str_replace("{serial_number}",$job->serial_number,$mail_template);
            $mail_template = str_replace("{delivery_date}",$job->expected_delivery,$mail_template);
            $mail_template = str_replace("{brand}",$job->brand->name,$mail_template);
            $mail_template = str_replace("{device_model}",$job->device->name,$mail_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $mail_template = str_replace("{device}",$device,$mail_template);
            $mail_template = str_replace("{business_name}",$job->shop->name,$mail_template);
            $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

            //Send Mail
            if ($job->email){
                $mail_setting = Auth::user()->mailSetting;
                if (!$mail_setting){
                    $mail_setting = User::findOrFail(1)->mailSetting;
                }
                if ($mail_setting){
                    if($job->shop->number_of_emails > 0){
                        if ($mail_setting->type == 2){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                                'apikey' =>  $mail_setting->mailchimp_apikey,
                            ];
                            $this->sendThroughMailchimp($data);
                        }elseif ($mail_setting->type == 1){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                            ];
                            $this->approach3($data);
                        }
                        $job->shop->number_of_emails = $job->shop->number_of_emails - 1;
                        $job->shop->save();
                    }

                }
            }


            //Send SMS
            if ($job->sms){
                $sms_setting = Auth::user()->smsSetting;
                if (!$sms_setting){
                    $sms_setting = User::findOrFail(1)->smsSetting;
                }

                $whatsapp_setting = Auth::user()->whatsappSetting;
                if (!$whatsapp_setting){
                    $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                }
                if ($sms_setting){
                    if($job->shop->number_of_sms > 0){
                        if ($sms_setting->type == 2){
                            $data = (object) [
                                'phone' => $us->phone,
                                'account_sid' => $sms_setting->twilio_account_sid,
                                'auth_token' => $sms_setting->twilio_auth_token,
                                'twilio_number' =>  $sms_setting->twilio_number,
                                'msg' =>  $sms_template,
                            ];
                            $this->sendThroughTwilio($data);
                        }elseif ($sms_setting->type == 1){
                            $data = (object) [
                                'apikey' => $sms_setting->pearlsms_api_key,
                                'sender' => $sms_setting->pearlsms_sender,
                                'header' => $sms_setting->pearlsms_header,
                                'footer' => $sms_setting->pearlsms_footer,
                                'username' => $sms_setting->pearlsms_username,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughPearl($data);
                            $response = json_decode($response);
                            if ($response->status != "ERROR"){
                                $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                $job->shop->save();
                            }
                        }elseif ($sms_setting->type == 3){
                            $data = (object) [
                                'apikey' => $sms_setting->bulksms_apikey,
                                'sender' => $sms_setting->bulksms_sendername,
                                'username' => $sms_setting->bulksms_username,
                                'sms_type' => $job->stat->sms_type,
                                'sms_peid' => $job->stat->sms_peid,
                                'sms_template_id' => $job->stat->sms_template_id,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughBulk($data);
                            $response = json_decode($response);
                            if ($response[0]->status == "success"){
                                $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                $job->shop->save();
                            }
                        }

                    }



                }

                if ($whatsapp_setting){
                    if($job->shop->number_of_whatsapp > 0){
                        if ($whatsapp_setting->type == 1){
                            $data = (object) [
                                'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                'to' => str_replace("+","",$us->phone),
                                'msg' =>  $whatsapp_template,
                                'id' =>  $job->id,
                                'pdf' =>  $pdf,
                            ];
                            $this->sendThroughCloud($data);
                        }elseif ($whatsapp_setting->type == 2){
                            if($job->shop->number_of_whatsapp > 0){
                                $data = (object) [
                                    'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                    'to' => str_replace("+","",$us->phone),
                                    'msg' =>  $whatsapp_template,
                                ];
                                $this->sendThroughVonage($data);

                            }
                        }
                        $job->shop->number_of_whatsapp = $job->shop->number_of_whatsapp - 1;
                        $job->shop->save();
                    }



                }
            }

            $job->shop->number_of_jobs = $job->shop->number_of_jobs - 1;
            $job->shop->save();



            $data = Job::where('jobs.id', $job->id)
                ->orderBy('id', "Desc")
                ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                ->leftJoin('statuses as status', 'jobs.status_id', '=', 'status.id')
                ->select(
                    'jobs.*',
                    'brands.name as brand_name',
                    'couriers.name as courier_name',
                    'id_cards.name as id_card_name',
                    'users.name as customer_name',
                    'status.name as status_name',
                    'status.color as status_color'
                )
                ->first();

            //$data = $this->job_response->getSingleJobResponse($job->id);

            // $pre_repair = JobPreRepair::where('job_id', $job->id)->get();
            // $job_card = UserCard::where('job_id', $job->id)->get();
            // return $job_card;

            // $save_files['pre_repair'] = $pre_repair;
            // $save_files['userCard'] = $job_card;

            return response([
                'data' => $data,
                'message' => "Great! Job has been added successfully!",
                'error' => false
            ], 200);


            // return $save_files;



        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function editJob(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
                'shop_id' => 'required',
                'user_id' => 'required',
                'brand_id' => 'required',
                'device_model' => 'required',
                'serial_number' => 'required',
                'problem_by_customer' => 'required',
                'condition_of_product' => 'required',
                'cost' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $input = $request->all();
            $job = Job::find($request->job_id);
            if(!$job){
                return response([
                    'message' => "Job Not Found",
                    'error' => true
                ], 200);
            }
            $old_status = $job->status_id;
            $new_status = $request->status;
            $old_expected_delivery = $job->expected_delivery;
            $new_expected_delivery = $request->expected_delivery;
            if ($request->service_type == 4) {
                $job->tracking_id = $request->tracking_id;
                $job->courier_id = $request->courier_id;
            }
            $job->user_id           = $input['shop_id'];
            $job->customer_id       = $input['user_id'];
            $job->service_type      = $input['service_type'];
            $job->brand_id          = $input['brand_id'];
            $job->device_id = $input['device_model'];
            $job->serial_number = $input['serial_number'];

            if ($request->product_configuration){
//                $job->product_configuration = implode(', ', $input['product_configuration']);
                $job->product_configuration = $request->product_configuration;
            }
            if ($request->problem_by_customer){
//                $job->problem_by_customer = implode(', ', $input['problem_by_customer']);
                $job->problem_by_customer = $request->problem_by_customer;
            }
            if ($request->condition_of_product){
//                $job->condition_of_product = implode(', ', $input['condition_of_product']);
                $job->condition_of_product = $request->condition_of_product;
            }
            $job->comment = $input['comment'];
            $job->cost = $input['cost'];
            $job->status_id = $input['status'];
            $job->expected_delivery = $input['expected_delivery'];
            //$job->job_sheet_number = date('Y-m-d');
            $job->email = $input['notify_mail'];
            $job->sms = $input['notify_sms'];
            $job->description = $input['description'];
            if ($request->pattern) {
                $job->pattern = $input['pattern'];
            }
            if ($request->password) {
                $job->password = $input['password'];
            }

            $job->id_card_id = $input['id_card_id'];

            $job->save();



            $repair_check = $request->repair_check;
            // return $repair_check;
            $pre_repair = json_decode($repair_check, true);


            foreach ($pre_repair as $check) {
                $pre_repair = JobPreRepair::where('id', $check['id'])->first();
                if ($pre_repair) {
                    $pre_repair->name = $check['title'];
                    $pre_repair->value = $check['value'];

                    $pre_repair->save();
                }
            }

            $idCards = $request->update_idCards;


            $id_cards = json_decode($idCards, true);

            foreach ($id_cards as $ids) {


                $update_idCards = UserCard::where('id', $ids['id'])->first();

                if ($update_idCards) {
                    $update_idCards->use = $ids['jobsheet'];
                    if ($request->profile) {
                        $update_idCards->user_id = Auth::id();
                    }
                    $update_idCards->save();
                }
            }


            $job_sheet = $request->new_jobsheet;

            if ($request->hasFile('new_idCards')) {

                $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'pdfx', 'doc'];
                $files = $request->file('new_idCards');
                // return response()->json(['message' => count($files) .' jobsheets'. count($job_sheet), 'error' => true], 200);
                $i=0;
                foreach ($files as $key => $file) {

                    // return $job_sheet[$key];
                    $filename = $file->getClientOriginalName();

                    $extension = $file->getClientOriginalExtension();


                    //$file->move('storage/photos', $filename);
                    $check = in_array($extension, $allowedfileExtension);
                    $fullpath = time().rand() . '.' . $extension; // adding full path

                    if ($check) {

                        // removing 2nd loop
                        $destinationPath = public_path('/uploads');

                        $file->move($destinationPath, $fullpath); // you should include extension here for retrieving in blade later

                        $img = new UserCard();
                        $img->job_id = $job->id;

                        if ($request->profile) {
                            $img->user_id = Auth::id();
                        }

                        $img->use = $job_sheet[$i];

                        $img->id_card_id = $request->id_card_id;
                        $img->image = $fullpath;

                        $img->save();
                        $i++;
                    } else {
                        return response()->json(['message' => 'warning!  Sorry Only Upload png , jpg , doc', 'error' => true], 200);
                    }
                }
            }
            if ($old_status != $new_status or $old_expected_delivery != $new_expected_delivery) {
                $url = route('job-pdf-public',[$job->id,$job->shop->name]);

                $us = $job->customer;
                $sms_template = $job->stat->sms_template;
                $sms_template = str_replace("{customer_name}",$job->customer->name,$sms_template);
                $sms_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$sms_template);
                $sms_template = str_replace("{status}",$job->stat->name,$sms_template);
                $sms_template = str_replace("{serial_number}",$job->serial_number,$sms_template);
                $sms_template = str_replace("{delivery_date}",$job->expected_delivery,$sms_template);
                $sms_template = str_replace("{brand}",$job->brand->name,$sms_template);
                $sms_template = str_replace("{device_model}",$job->device->name,$sms_template);
                $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
                $sms_template = str_replace("{device}",$device,$sms_template);
                $sms_template = str_replace("{business_name}",$job->shop->name,$sms_template);
                if (str_contains($sms_template, '{pdf}')) {
                    $pdf = 1;
                    $sms_template = str_replace("{pdf}",$url,$sms_template);
//                $sms_template = "$sms_template  $url";
                }else{
                    $pdf = 0;
                }

                $whatsapp_template = $job->stat->whatsapp_template;
                $whatsapp_template = str_replace("{customer_name}",$job->customer->name,$whatsapp_template);
                $whatsapp_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$whatsapp_template);
                $whatsapp_template = str_replace("{status}",$job->stat->name,$whatsapp_template);
                $whatsapp_template = str_replace("{serial_number}",$job->serial_number,$whatsapp_template);
                $whatsapp_template = str_replace("{delivery_date}",$job->expected_delivery,$whatsapp_template);
                $whatsapp_template = str_replace("{brand}",$job->brand->name,$whatsapp_template);
                $whatsapp_template = str_replace("{device_model}",$job->device->name,$whatsapp_template);
                $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
                $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
                $whatsapp_template = str_replace("{business_name}",$job->shop->name,$whatsapp_template);
                if (str_contains($whatsapp_template, '{pdf}')) {
                    $pdf = 1;
                    $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                    $whatsapp_template = "$whatsapp_template  $url";
                }else{
                    $pdf = 0;
                }





                $mail_template = $job->stat->email_body;
                $mail_template = str_replace("{customer_name}",$job->customer->name,$mail_template);
                $mail_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$mail_template);
                $mail_template = str_replace("{status}",$job->stat->name,$mail_template);
                $mail_template = str_replace("{serial_number}",$job->serial_number,$mail_template);
                $mail_template = str_replace("{delivery_date}",$job->expected_delivery,$mail_template);
                $mail_template = str_replace("{brand}",$job->brand->name,$mail_template);
                $mail_template = str_replace("{device_model}",$job->device->name,$mail_template);
                $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
                $mail_template = str_replace("{device}",$device,$mail_template);
                $mail_template = str_replace("{business_name}",$job->shop->name,$mail_template);
                $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

                //Send Mail
                if ($job->email){
                    $mail_setting = Auth::user()->mailSetting;
                    if (!$mail_setting){
                        $mail_setting = User::findOrFail(1)->mailSetting;
                    }
                    if ($mail_setting){
                        if($job->shop->number_of_emails > 0){
                            if ($mail_setting->type == 2){
                                $data = (object) [
                                    'from_name' => Auth::user()->email,
                                    'from_email' => Auth::user()->email,
                                    'to_name' => $us->name,
                                    'to_email' =>  $us->email,
                                    'msg' =>  $mail_template,
                                    'apikey' =>  $mail_setting->mailchimp_apikey,
                                ];
                                $this->sendThroughMailchimp($data);
                            }elseif ($mail_setting->type == 1){
                                $data = (object) [
                                    'from_name' => Auth::user()->email,
                                    'from_email' => Auth::user()->email,
                                    'to_name' => $us->name,
                                    'to_email' =>  $us->email,
                                    'msg' =>  $mail_template,
                                ];
                                $this->approach3($data);
                            }
                            $job->shop->number_of_emails = $job->shop->number_of_emails - 1;
                            $job->shop->save();
                        }

                    }
                }


                //Send SMS
                if ($job->sms){
                    $sms_setting = Auth::user()->smsSetting;
                    if (!$sms_setting){
                        $sms_setting = User::findOrFail(1)->smsSetting;
                    }

                    $whatsapp_setting = Auth::user()->whatsappSetting;
                    if (!$whatsapp_setting){
                        $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                    }
                    if ($sms_setting){
                        if($job->shop->number_of_sms > 0){
                            if ($sms_setting->type == 2){
                                $data = (object) [
                                    'phone' => $us->phone,
                                    'account_sid' => $sms_setting->twilio_account_sid,
                                    'auth_token' => $sms_setting->twilio_auth_token,
                                    'twilio_number' =>  $sms_setting->twilio_number,
                                    'msg' =>  $sms_template,
                                ];
                                $this->sendThroughTwilio($data);
                            }elseif ($sms_setting->type == 1){
                                $data = (object) [
                                    'apikey' => $sms_setting->pearlsms_api_key,
                                    'sender' => $sms_setting->pearlsms_sender,
                                    'header' => $sms_setting->pearlsms_header,
                                    'footer' => $sms_setting->pearlsms_footer,
                                    'username' => $sms_setting->pearlsms_username,
                                    'phone' => $us->phone,
                                    'msg' =>  $sms_template,
                                ];
                                $response =  $this->sendThroughPearl($data);
                                $response = json_decode($response);
                                if ($response->status != "ERROR"){
                                    $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                    $job->shop->save();
                                }
                            }elseif ($sms_setting->type == 3){
                                $data = (object) [
                                    'apikey' => $sms_setting->bulksms_apikey,
                                    'sender' => $sms_setting->bulksms_sendername,
                                    'username' => $sms_setting->bulksms_username,
                                    'sms_type' => $job->stat->sms_type,
                                    'sms_peid' => $job->stat->sms_peid,
                                    'sms_template_id' => $job->stat->sms_template_id,
                                    'phone' => $us->phone,
                                    'msg' =>  $sms_template,
                                ];
                                $response =  $this->sendThroughBulk($data);
                                $response = json_decode($response);
                                if ($response[0]->status == "success"){
                                    $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                    $job->shop->save();
                                }
                            }

                        }



                    }

                    if ($whatsapp_setting){
                        if($job->shop->number_of_whatsapp > 0){
                            if ($whatsapp_setting->type == 1){
                                $data = (object) [
                                    'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                    'to' => str_replace("+","",$us->phone),
                                    'msg' =>  $whatsapp_template,
                                    'id' =>  $job->id,
                                    'pdf' =>  $pdf,
                                ];
                                $response = $this->sendThroughCloud($data);
                                $response = json_decode($response);
                            }elseif ($whatsapp_setting->type == 2){
                                if($job->shop->number_of_whatsapp > 0){
                                    $data = (object) [
                                        'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                        'to' => str_replace("+","",$us->phone),
                                        'msg' =>  $whatsapp_template,
                                    ];
                                    $this->sendThroughVonage($data);

                                }
                            }
                            $job->shop->number_of_whatsapp = $job->shop->number_of_whatsapp - 1;
                            $job->shop->save();
                        }



                    }
                }


            }
            $data = $this->job_response->getSingleJobResponse($request->job_id);
            // $data = Job::where("jobs.id", $job->id)
            //     ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
            //     ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
            //     ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
            //     ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
            //     ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
            //     ->select(
            //         'jobs.*',
            //         'brands.name as brand_name',
            //         'devices.name as device_model',
            //         'devices.type as device_name',
            //         'couriers.name as courier_name',
            //         'id_cards.name as id_card_name',
            //         'users.name as customer_name',
            //         'users.phone as customer_phone_no'
            //     )
            //     ->with('cards')
            //     ->with('preRepairs')
            //     ->with('parts')
            //     ->first();

            if ($data) {
                // $device_name = $data['device_name'];
                // if ($device_name == 1) {
                //     $data['device_name'] = "Mobile Phones";
                // } else {
                //     $data['device_name'] = "Laptops";
                // }
                // $data['device_type_id'] = $device_name;
                // $data['status_id'] =$data->getRawOriginal('status');
                return response([
                    'data' => $data,
                    'message' => "Job Updated Successfully",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'message' => "Job Not Found",
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function jobDetail(Request $request)
    {

        try {
            $job = auth()->user();
            $data = $this->job_response->getSingleJobResponse($request->id);


            if ($data) {

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
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function getGenericData(Request $request)
    {

        try {
            $job = auth()->user();

            $devices = array(
                array("id" => 1, "name" => "Mobile"),
                array("id" => 2, "name" => "Laptop"),
            );
            $payments = array(
                array("id" => 1, "name" => "Cash"),
                array("id" => 2, "name" => "Card"),
                array("id" => 3, "name" => "Credit"),
            );

            if ($job->is_admin == 1 && $job->role == 1) {
                $shops = User::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([['role', 2], ["is_admin", 1]])
                    // ->take(50)
                    ->get();
                $jobs = User::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->where([["is_admin", 0]])
                    // ->take(50)
                    ->get();
                $id_cards = IdCard::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->take(50)->get();
                $brands = Brand::orderBy("id", "Desc")
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();
                $couriers = Courier::select('id', 'name')->get();
                $categories = Category::select('id', 'name')->get();
                $types = Type::select('id', 'name')->get();
                $statuses = Status::get();


                $status = $statuses->map->only(['id', 'name', 'color']);
                return response([
                    'shops' => $shops,
                    'users' => $jobs,
                    'id_cards' => $id_cards,
                    'brands' => $brands,
                    'devices' => $devices,
                    'couriers' => $couriers,
                    'categories' => $categories,
                    'types' => $types,
                    'status' => $status,
                    'payments' => $payments,
                    'message' => "Records Found",
                    'error' => false
                ], 200);
            } elseif ($job->is_admin == 1 && $job->role == 2) {
                $id = $job->id;
                $jobs = User::orderBy("users.id", "Desc")
                    ->select('name', 'users.id')
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();
                $id_cards = IdCard::orderBy("id", "Desc")
                    ->where([["user_id", $id]])
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();
                $brands = Brand::orderBy("id", "Desc")
                    ->where([["user_id", $id]])
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();
                $couriers = Courier::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $categories = Category::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $types = Type::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $statuses = Status::where("user_id",auth()->user()->id)->get();


                $status = $statuses->map->only(['id', 'name', 'color']);
                return response([
                    'shops' => [],
                    'users' => $jobs,
                    'id_cards' => $id_cards,
                    'brands' => $brands,
                    'devices' => $devices,
                    'couriers' => $couriers,
                    'categories' => $categories,
                    'types' => $types,
                    'status' => $status,
                    'payments' => $payments,
                    'message' => "Records  Found",
                    'error' => false
                ], 200);
            }elseif ($job->is_admin == 1 && $job->role == 3) {
                $id = $job->parent_id;
                $jobs = User::orderBy("id", "Desc")
                    ->select('name', 'id')
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    // ->take(50)
                    ->get();
                $id_cards = IdCard::orderBy("id", "Desc")
                    ->where([["user_id", $id]])
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();
                $brands = Brand::orderBy("id", "Desc")
                    ->where([["user_id", $id]])
                    ->select('name', 'id')
                    // ->take(50)
                    ->get();
                $couriers = Courier::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $categories = Category::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $types = Type::select('id', 'name')
                    ->where([["user_id", $id]])
                    ->get();
                $statuses = Status::where("user_id",auth()->user()->id)->get();


                $status = $statuses->map->only(['id', 'name', 'color']);
                return response([
                    'shops' => [],
                    'users' => $jobs,
                    'id_cards' => $id_cards,
                    'brands' => $brands,
                    'devices' => $devices,
                    'couriers' => $couriers,
                    'categories' => $categories,
                    'types' => $types,
                    'status' => $status,
                    'payments' => $payments,
                    'message' => "Records  Found",
                    'error' => false
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function searchJobs(Request $request)
    {

        try {
            $job = auth()->user();

            if ($job->is_admin == 1 && $job->role == 1) {
                $data = Job::orderBy('jobs.id', "Desc")
                    // ->where('jobs.id', 'like', "%{$request->keyword}%")
                    ->orWhere('jobs.job_sheet_number', 'like', "%{$request->keyword}%")
                    ->orWhere('jobs.serial_number', 'like', "%{$request->keyword}%")
                    ->orwhere('users.name',  'like', "%{$request->keyword}%")

                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                    ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name',
                        'statuses.name as status_name',
                        'statuses.color as status_color',
                        'statuses.id as status_id',
                        'invoices.id as invoice_id'
                    )
                    ->get();
            } else if ($job->is_admin == 1 && $job->role == 2) {
                $data = Job::orderBy('jobs.id', "Desc")
                    ->where([['jobs.id', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->id]])
                    ->orWhere([['jobs.job_sheet_number', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->id]])
                    ->orWhere([['jobs.serial_number', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->id]])
                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                    ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name',
                        'statuses.name as status_name',
                        'statuses.color as status_color',
                        'statuses.id as status_id',
                        'invoices.id as invoice_id'
                    )
                    ->get();
            }else if ($job->is_admin == 1 && $job->role == 3) {
                $data = Job::orderBy('jobs.id', "Desc")
                    ->where([['jobs.id', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->parent_id]])
                    ->orWhere([['jobs.job_sheet_number', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->parent_id]])
                    ->orWhere([['jobs.serial_number', 'like', "%{$request->keyword}%"], ["jobs.user_id", $job->parent_id]])
                    ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
                    ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
                    ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
                    ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
                    ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
                    ->leftJoin('statuses', 'jobs.status_id', '=', 'statuses.id')
                    ->leftJoin('invoices', 'jobs.id', '=', 'invoices.job_id')

                    ->select(
                        'jobs.*',
                        'brands.name as brand_name',
                        'couriers.name as courier_name',
                        'id_cards.name as id_card_name',
                        'users.name as customer_name',
                        'statuses.name as status_name',
                        'statuses.color as status_color',
                        'statuses.id as status_id',
                        'invoices.id as invoice_id'
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
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function userSearch(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'key' => 'required'
        ]);
        $job_id = auth()->user()->id;

        $job_detail = User::where('name', 'like', "%{$request->key}%")->get();
        //    return $job_detail;
        if (!$job_detail) {
            return response([
                'message' => "Search Not Found",
                'error' => true
            ], 200);
        }

        foreach ($job_detail as $detail) {
            $id = $detail['id'];
            $isInvited = Invitation::where([['sender_id', $job_id], ['receiver_id', $id], ['event_id', $request->event_id]])->count();
            if ($isInvited > 0) {
                $detail['isInvited'] = true;
            } else {
                $detail['isInvited'] = false;
            }
        }
        return response([
            'data' => $job_detail,
            'message' => "User Search",
            'error' => false
        ], 200);
    }
    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'

        ]);


        $job = Job::where('id', $request->id)->first();
        $old_status = $job->status_id;
        $new_status = $request->status;
        $job->status_id = $request->status;
        $job->save();

        if ($old_status != $new_status) {
            $url = route('job-pdf-public',[$job->id,$job->shop->name]);

            $us = $job->customer;
            $sms_template = $job->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$job->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$job->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$job->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$job->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$job->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$job->device->name,$sms_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$job->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
//                $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }

            $whatsapp_template = $job->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$job->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$job->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$job->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$job->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$job->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$job->device->name,$whatsapp_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$job->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }





            $mail_template = $job->stat->email_body;
            $mail_template = str_replace("{customer_name}",$job->customer->name,$mail_template);
            $mail_template = str_replace("{job_sheet_no}",$job->job_sheet_number,$mail_template);
            $mail_template = str_replace("{status}",$job->stat->name,$mail_template);
            $mail_template = str_replace("{serial_number}",$job->serial_number,$mail_template);
            $mail_template = str_replace("{delivery_date}",$job->expected_delivery,$mail_template);
            $mail_template = str_replace("{brand}",$job->brand->name,$mail_template);
            $mail_template = str_replace("{device_model}",$job->device->name,$mail_template);
            $device = ($job->device->type == 1) ? "Mobile" : "Laptop";
            $mail_template = str_replace("{device}",$device,$mail_template);
            $mail_template = str_replace("{business_name}",$job->shop->name,$mail_template);
            $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

            //Send Mail
            if ($job->email){
                $mail_setting = Auth::user()->mailSetting;
                if (!$mail_setting){
                    $mail_setting = User::findOrFail(1)->mailSetting;
                }
                if ($mail_setting){
                    if($job->shop->number_of_emails > 0){
                        if ($mail_setting->type == 2){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                                'apikey' =>  $mail_setting->mailchimp_apikey,
                            ];
                            $this->sendThroughMailchimp($data);
                        }elseif ($mail_setting->type == 1){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                            ];
                            $this->approach3($data);
                        }
                        $job->shop->number_of_emails = $job->shop->number_of_emails - 1;
                        $job->shop->save();
                    }

                }
            }


            //Send SMS
            if ($job->sms){
                $sms_setting = Auth::user()->smsSetting;
                if (!$sms_setting){
                    $sms_setting = User::findOrFail(1)->smsSetting;
                }

                $whatsapp_setting = Auth::user()->whatsappSetting;
                if (!$whatsapp_setting){
                    $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                }
                if ($sms_setting){
                    if($job->shop->number_of_sms > 0){
                        if ($sms_setting->type == 2){
                            $data = (object) [
                                'phone' => $us->phone,
                                'account_sid' => $sms_setting->twilio_account_sid,
                                'auth_token' => $sms_setting->twilio_auth_token,
                                'twilio_number' =>  $sms_setting->twilio_number,
                                'msg' =>  $sms_template,
                            ];
                            $this->sendThroughTwilio($data);
                        }elseif ($sms_setting->type == 1){
                            $data = (object) [
                                'apikey' => $sms_setting->pearlsms_api_key,
                                'sender' => $sms_setting->pearlsms_sender,
                                'header' => $sms_setting->pearlsms_header,
                                'footer' => $sms_setting->pearlsms_footer,
                                'username' => $sms_setting->pearlsms_username,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughPearl($data);
                            $response = json_decode($response);
                            if ($response->status != "ERROR"){
                                $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                $job->shop->save();
                            }
                        }elseif ($sms_setting->type == 3){
                            $data = (object) [
                                'apikey' => $sms_setting->bulksms_apikey,
                                'sender' => $sms_setting->bulksms_sendername,
                                'username' => $sms_setting->bulksms_username,
                                'sms_type' => $job->stat->sms_type,
                                'sms_peid' => $job->stat->sms_peid,
                                'sms_template_id' => $job->stat->sms_template_id,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughBulk($data);
                            $response = json_decode($response);

                            if ($response[0]->status == "success"){
                                $job->shop->number_of_sms = $job->shop->number_of_sms - 1;
                                $job->shop->save();
                            }
                        }

                    }



                }

                if ($whatsapp_setting){
                    if($job->shop->number_of_whatsapp > 0){
                        if ($whatsapp_setting->type == 1){
                            $data = (object) [
                                'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                'to' => str_replace("+","",$us->phone),
                                'msg' =>  $whatsapp_template,
                                'id' =>  $job->id,
                                'pdf' =>  $pdf,
                            ];
                            $this->sendThroughCloud($data);
                        }elseif ($whatsapp_setting->type == 2){
                            if($job->shop->number_of_whatsapp > 0){
                                $data = (object) [
                                    'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                    'to' => str_replace("+","",$us->phone),
                                    'msg' =>  $whatsapp_template,
                                ];
                                $this->sendThroughVonage($data);

                            }
                        }
                        $job->shop->number_of_whatsapp = $job->shop->number_of_whatsapp - 1;
                        $job->shop->save();
                    }



                }
            }

        }
        return response([
            'message' => "Job Status Updated Successfully",
            'error' => false
        ], 200);
    }

    public function updateDocument(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'document' => 'required|mimes:jpeg,png,jpg,doc,docx,pdf,pdfx',

        ]);
        try {
            DB::beginTransaction();

            $job_id = auth()->user()->id;
            $job = Job::where('id', $request->id)->first();

            if ($request->hasFile('document')) {
                if ($request->file('document')->isValid()) {
                    $this->validate($request, [
                        'document' => 'required|mimes:jpeg,png,jpg'
                    ]);
                    $file = $request->file('document');
                    $destinationPath = public_path('/uploads');

                    $imagePath = public_path('/uploads/' . $job->document);

                    if ($job->document != '') {
                        if (File::exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    //$extension = $file->getClientOriginalExtension('logo');
                    $thumbnail = $file->getClientOriginalName('document');
                    $thumbnail = rand() . $thumbnail;
                    $request->file('document')->move($destinationPath, $thumbnail);
                    $job->document = $thumbnail;
                }
            }

            $job->save();
            DB::commit();

            return response([
                'message' => "Document Updated Successfully",
                'image_path' => $thumbnail,
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update_CardImages(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image'=> 'required'
        ]);


        try {
            DB::beginTransaction();

            $id = $request->id;
            $jobcard = UserCard::find($id);
            if (!$jobcard) {
                return response([
                    'message' => "Record not Found",
                    'error' => false
                ], 200);
            }



            $imagePath = public_path('/uploads/' . $jobcard->image);


            if ($jobcard->image != '') {

                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }



            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $this->validate($request, [
                        'image' => 'required'
                    ]);
                    $file = $request->file('image');
                    $destinationPath = public_path('/uploads');
                    //$extension = $file->getClientOriginalExtension('logo');
                    $image = $file->getClientOriginalName('image');
                    $thumbnail = rand() . $image;
                    $request->file('image')->move($destinationPath, $thumbnail);

                    $jobcard->image = $thumbnail;
                }
            }
            $jobcard->save();

            DB::commit();
            return response([
                'image_path' => $thumbnail,
                'message' => "IdCard Image Updated Successfully",
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
                $job_id = auth()->user()->id;
            } elseif (auth()->user()->role == 3) {
                $job_id = auth()->user()->parent_id;
            } elseif (auth()->user()->role == 1) {
                $job_id = auth()->user()->id;
            }
            $data = Job::where("user_id",$job_id)->get();
            (new FastExcel($data))->export("api-jobs$job_id.csv", function ($pass) {
                if($pass->service_type == 1){
                    $service = 'Carry In';
                }elseif($pass->service_type == 2){
                    $service = 'Pick Up';
                }elseif($pass->service_type == 3){
                    $service = 'On Site';
                }elseif($pass->service_type == 4){
                    $service = 'Courier';
                }


                $br = Brand::findOrFail($pass->brand_id);
                if ($br){
                    $brand = $br->name;
                }else{
                    $brand ="";
                }
                $stat = Status::findOrFail($pass->status_id);
                if ($stat){
                    $status = $stat->name;
                }else{
                    $status ="";
                }
                $dev = Device::findOrFail($pass->device_id);
                if ($dev){
                    $model = $dev->name;
                    if($dev->type == 1){
                        $device = 'Mobile';
                    }else{
                        $device = 'Laptop';
                    }
                }else{
                    $model ="";
                    $device ="";
                }
                $us = User::findOrFail($pass->customer_id);
                if ($us){
                    $name = $us->name;
                    $email = $us->email;
                    $phone = $us->phone;
                }else{
                    $name = "";
                    $email = "";
                    $phone = "";
                }


                return [
                    'User' => $name,
                    'Contact Number' => $phone,
                    'Email Address' => $email,
                    'Service Type' => $service,
                    'Device' => $device,
                    'Brand' => $brand,
                    'Model' => $model,
                    'Job Sheet No' => $pass->job_sheet_number,
                    'Serial No' => $pass->serial_number,
                    'Password' => $pass->password,
                    'Pattern' => $pass->pattern,
                    'Product Configuration' => $pass->product_configuration,
                    'Problem Reported By The Customer' => $pass->problem_by_customer,
                    'Condition Of The Product' => $pass->condition_of_product,
                    'Comment' => $pass->comment,
                    'Estimate Cost' => $pass->cost,
                    'Status' => $status,
                    'Expected Delivery Date' => $pass->expected_delivery,
                    'Description' => $pass->description,
                ];

            });
            $url = url("public/api-jobs$job_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Jobs Export successfully", 'error' => false], 200);
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
                            $job_id = auth()->user()->id;
                        } elseif (auth()->user()->role == 3) {
                            $job_id = auth()->user()->parent_id;
                        } else {
                            $job_id = auth()->user()->id;
                        }
                        if ($line['Service Type']  == 'Courier'){
                            $service = 4;
                        }else if($line['Service Type']  == 'On Site'){
                            $service = 3;
                        }else if($line['Service Type']  == 'Pick Up'){
                            $service = 2;
                        }else{
                            $service = 1;
                        }

                        $job = User::where([["email",$line['Email Address']]])->first();
                        if (!$job){
                            $job = new User();
                            $job->password = bcrypt("12345607");
                        }
                        $job->active = 1;
                        $job->name = $line['User'];
                        $job->phone = $line['Contact Number'];
                        $job->email = $line['Email Address'];
                        $job->save();
                        $shop_user = ShopUser::where([["user_id",$job_id],["customer_id", $job->id]])->first();
                        if (!$shop_user){
                            $shop_user = new ShopUser();
                            $shop_user->customer_id = $job->id;
                            $shop_user->user_id = $job_id;
                            $shop_user->save();
                        }
                        $sh = User::find($job_id);

                        $job = new Job();
                        $job->serial_number = $line['Serial No'];
                        $job->password = $line['Password'];
                        $job->pattern = $line['Pattern'];
                        $job->product_configuration = $line['Product Configuration'];
                        $job->problem_by_customer = $line['Problem Reported By The Customer'];
                        $job->condition_of_product = $line['Condition Of The Product'];
                        $job->expected_delivery = $line['Expected Delivery Date'];
                        $job->comment = $line['Comment'];
                        $job->description = $line['Description'];
                        $job->cost = $line['Estimate Cost'];
                        $job->user_id = $job_id;
                        $job->customer_id = $job->id;
                        $job->service_type = $service;
                        $job_sheet_number = "FF-".date('YmdHis');
                        if ($sh->jobSetting){
                            if ($sh->jobSetting->jos_sheet_prefix){
                                $prefix = $sh->jobSetting->jos_sheet_prefix;
                                $job_sheet_number = "$prefix".date('YmdHis');
                            }
                        }
                        usleep(1000000);
//                    $check = Job::where("job_sheet_number",$job_sheet_number)->first();
//                    if ($check){
//                        $job_sheet_number = "FF-".(date('YmdHis')+1);
//                        if ($sh->jobSetting){
//                            if ($sh->jobSetting->jos_sheet_prefix){
//                                $prefix = $sh->jobSetting->jos_sheet_prefix;
//                                $job_sheet_number = "$prefix".(date('YmdHis')+1);
//                            }
//                        }
//                    }
                        $job->job_sheet_number = $job_sheet_number;
                        $brand = Brand::where([["name",$line['Brand']],["user_id",$job_id]])->first();
                        if (!$brand){
                            $brand = new Brand();
                            $brand->user_id = $job_id;
                            $brand->name = $line['Brand'];
                            $brand->save();
                        }
                        $status = Status::where([["name",$line['Status']],["user_id",$job_id]])->first();
                        if (!$status){
                            $status = new Status();
                            $status->user_id = $job_id;
                            $status->name = $line['Status'];
                            $status->save();
                        }
                        $job->brand_id = $brand->id;
                        $device = Device::where([["name",$line['Model']],["user_id",$job_id],["brand_id",$brand->id]])->first();
                        if ($line['Device'] == 'Mobile'){
                            $devices = 1;
                        }else{
                            $devices = 2;
                        }
                        if (!$device){
                            $device = new Device();
                            $device->user_id = $job_id;
                            $device->brand_id = $brand->id;
                            $device->name = $line['Model'];
                            $device->type = $devices;
                            $device->save();
                        }

                        $job->device_id = $device->id;
                        $job->status_id = $status->id;
                        return $job->save();

                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Jobs Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function deleteJob(Request $request)
    {
        $request->validate([
            'id' => 'required',

        ]);


        try {
            DB::beginTransaction();

            $id = $request->id;
            $job = Job::find($id);
            if (!$job) {
                return response([
                    'message' => "Job not Found",
                    'error' => true
                ], 200);
            }

            $imagePath = public_path('/uploads/' . $job->document);


            if ($job->document != '') {

                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $job_cards = UserCard::where('job_id', $job->id)->get();
            foreach ($job_cards as $job) {
                $imagePath = public_path('/uploads/' . $job['image']);
                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }


            $job->delete();
            DB::commit();
            return response([
                'message' => "Job Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteidCard(Request $request)
    {
        $request->validate([
            'id' => 'required',

        ]);


        try {
            DB::beginTransaction();

            $id = $request->id;
            $jobcard = UserCard::find($id);
            if (!$jobcard) {
                return response([
                    'message' => "Record not Found",
                    'error' => false
                ], 200);
            }



            $imagePath = public_path('/uploads/' . $jobcard->image);


            if ($jobcard->image != '') {

                if (File::exists($imagePath)) {
                    unlink($imagePath);
                }
            }




            $jobcard->delete();
            DB::commit();
            return response([
                'message' => "IdCard  Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    public function sendThroughVonage($data){
        $from = $data->from;
        $to = $data->to;
        $msg = $data->msg;
        $url = "https://messages-sandbox.nexmo.com/v1/messages";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Basic NDQyMTA0MmE6eUxYaVdqcFJ6cWhSMjkyUw==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
        {
            "from": $from,
            "to": $to,
            "message_type": "text",
            "text": "$msg",
            "channel": "whatsapp"
          }
DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
    }
    public function sendThroughCloud($data){
        $api_key = $data->api_key;
        $to = $data->to;

        $msg = rawurlencode("$data->msg");
        $pdf_add = $data->pdf;
        $job = Job::find($data->id);
        $img = $job->shop->image;
        $user = $job->customer;
        $logo = public_path("/uploads/$img");
//        $logo = "$url/public/uploads/".$img;
//        'debugPng' => true,
        $settings = JobSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $settings = JobSetting::where('user_id', Auth::user()->id)->first();
        }
        $basic = BasicSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $basic = BasicSetting::where('user_id', Auth::user()->id)->first();
        }
        if ($basic){
            $logo = url("/uploads/$basic->image");
        }
        $path = url("uploads/");
        $pdf = app('dompdf.wrapper');

        //############ Permitir ver imagenes si falla ################################
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif']);
        $pdf->getDomPDF()->setHttpContext($contxt);
        //#################################################################################
//        return view("admin.jobs.pdf",compact('user','logo','settings','path'));
        $path = public_path("/uploads/$job->job_sheet_number.pdf");
        $url = url("/uploads/$job->job_sheet_number.pdf");
        $pdf =  $pdf->loadView('admin.jobs.pdf', compact('user','logo','settings','path'))
            ->save("$path");
        $curl = curl_init();
        if($pdf_add == 1){
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey='.$api_key.'&mobile='.$to.'&msg='.$msg.'&pdf='.$url.'',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

        }else{
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey='.$api_key.'&mobile='.$to.'&msg='.$msg.'',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
        }

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function sendThroughTwilio($data){
        $receiverNumber = $data->phone;
        $message = $data->msg;
        try {
            $account_sid = $data->account_sid;
            $auth_token = $data->auth_token;
            $twilio_number = $data->twilio_number;



            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);
        } catch (Exception $e) {

            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }
    }
    public function sendThroughPearl($data){
        $curl = curl_init();
        $sender = ($data->sender)?$data->sender : 'PALLVl';
        $header = ($data->header)?$data->header : 'Dear,';
        $footer = ($data->footer)?$data->footer : '';
        $message = rawurlencode("$header  $data->msg $sender $footer");
        $phone = $data->phone;
        $apikey = ($data->apikey)?$data->apikey : '855e314b060d485d9b4a6952c9f52bec';
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.pearlsms.com/public/sms/send?sender='.$sender.'&smstype=TRANS&numbers='.$phone.'&apikey='.$apikey.'&message='.$message.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function sendThroughBulk($data){
        $sender = ($data->sender)?$data->sender : 'FONFIX';
        $jobname = ($data->username)?$data->username : 'thefonefix21';
        $message = rawurlencode("$data->msg");
        $phone = $data->phone;
        $sms_type = $data->sms_type;
        $sms_peid = $data->sms_peid;
        $sms_template_id = $data->sms_template_id;
        $apikey = ($data->apikey)?$data->apikey : '8721ed80-7591-41c4-a96c-76a9c1768fec';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.bulksmsind.in/v2/sendSMS?username='.$jobname.'&message='.$message.'&sendername='.$sender.'&smstype='.$sms_type.'&numbers='.$phone.'&apikey='.$apikey.'&peid='.$sms_peid.'&templateid='.$sms_template_id.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function sendThroughMailchimp($data){

        $mailchimp = new \MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey($data->apikey);
        $response = $mailchimp->messages->send(
            [
                "message" => [
                    "from_email" => $data->from_email,
                    "from_name" => $data->from_name,
                    "subject" => "Job Sheet ",
                    "text" => $data->msg,
                    "html" => "$data->msg",

                ],
                "to" => [
                    "email" => $data->to_email,
                    "name" => $data->from_name,
                    "type" => "to",
                ]
            ]
        );
    }

    public function approach3($job) {
        $mail_setting = Auth::user()->mailSetting;
        if (!$mail_setting){
            $mail_setting = User::findOrFail(1)->mailSetting;
        }
        if($mail_setting){
            $configuration = [
                'smtp_host'    => $mail_setting->smtp_host,
                'smtp_port'    => $mail_setting->smtp_port,
                'smtp_username'  => $mail_setting->smtp_username,
                'smtp_password'  => $mail_setting->smtp_password,
                'smtp_encryption'  => $mail_setting->smtp_encryption,
                'from_email'    => $mail_setting->from_email,
                'from_name'    => $mail_setting->from_name,
                'replyTo_email'    => $mail_setting->from_email,
                'replyTo_name'    => $mail_setting->from_name,
            ];
        }else{
            $configuration = [
                'smtp_host'    => 'mail.webexert.us',
                'smtp_port'    => '465',
                'smtp_username'  => 'noreply@webexert.us',
                'smtp_password'  => 'LiB3ds9^euRq',
                'smtp_encryption'  => 'ssl',
                'from_email'    => 'noreply@webexert.us',
                'from_name'    => 'FoneFix',
                'replyTo_email'    => 'noreply@webexert.us',
                'replyTo_name'    => 'FoneFix',
            ];
        }
        $backup = Config::get('mail.mailers.smtp');
        Config::set('mail.mailers.smtp.host', $configuration['smtp_host']);
        Config::set('mail.mailers.smtp.port', $configuration['smtp_port']);
        Config::set('mail.mailers.smtp.username', $configuration['smtp_username']);
        Config::set('mail.mailers.smtp.password', $configuration['smtp_password']);
        Config::set('mail.mailers.smtp.encryption', $configuration['smtp_encryption']);
        Config::set('mail.mailers.smtp.transport', 'smtp');
        $settings = Setting::pluck('value', 'name')->all();
        $data = array(
            'name' => $job->to_name,
            'user_email' => $job->to_email,
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "Job Sheet Status is Updated",

            'msg' => $job->msg,
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );
        Mail::send('emails.order', $data, function ($message) use ($data) {
            $message->to($data['user_email'])
                ->from($data['user_email'],$data['from_name'])
                ->subject($data['subject']);
        });

//        Mail::to(  $job->email )->send(new DynamicSMTPMail( $job->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']] ));
        Config::set('mail.mailers.smtp', $backup);
    }

}
