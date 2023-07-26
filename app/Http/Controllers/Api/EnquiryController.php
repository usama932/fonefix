<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Device;
use App\Models\Setting;
use App\Models\ShopUser;
use Carbon\Carbon;
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

class EnquiryController extends ApiController
{
    use ApiResponser;

    public function addEnquiry(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'contact_number' => 'required',
                    'email_address' => 'required',
                    'estimate_date' => 'required',
                    'message' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $enquiry = new Enquiry();
            $enquiry->name = $input['name'];
            $enquiry->email_address = $input['email_address'];
            $enquiry->contact_number = $input['contact_number'];
            $enquiry->estimate_date = $input['estimate_date'];
            $enquiry->status = $input['status'];
            if ($request->status == 1){
                $enquiry->completed_at = Carbon::now();
            }
            // $res = array_key_exists('status', $input);
            // if ($res == false) {
            //     $enquiry->status = 0;
            // } else {
            //     $enquiry->status = 1;

            // }
            $enquiry->message = $request->message;
            $enquiry->email = $request->email;
            $enquiry->sms = $request->sms;
            // $res = array_key_exists('email', $input);
            // if ($res == false) {
            //     $enquiry->email = 0;
            // } else {
            //     $enquiry->email = 1;
            // }
            // $res = array_key_exists('sms', $input);
            // if ($res == false) {
            //     $enquiry->sms = 0;
            // } else {
            //     $enquiry->sms = 1;
            // }
            if (auth()->user()->role == 2) {
                $enquiry->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $enquiry->user_id = auth()->user()->parent_id;
            }
            $enquiry->save();
            if($request->devices) {
                $devices = json_decode($request->devices);
                foreach ($devices as $device) {
                    $user_brand = new EnquiryBrand();
                    $user_brand->brand_id = $device->brandId;
                    $user_brand->device = $device->deviceTypeID;
                    $user_brand->enquiry = $device->enquiry;
                    $user_brand->enquiry_id = $enquiry->id;
                    $user_brand->device_id = $device->deviceID;
                    $user_brand->save();
                }
            }

            $message = $enquiry->message;

            $us = auth()->user();
            if ($us->role == 1) {
                $send = 1;
            } elseif ($us->role == 2) {
                $send = 1;
            } elseif ($us->role == 3) {
                $send = $us->permission->enquiries_send;
            }
            if ($send) {
                //Send Mail
                if ($enquiry->email and $enquiry->email_address) {
                    $mail_setting = auth()->user()->mailSetting;
                    if (!$mail_setting) {
                        $mail_setting = User::findOrFail(1)->mailSetting;
                    }
                    if ($mail_setting) {
                        if ($enquiry->shop->number_of_emails > 0) {
                            if ($mail_setting->type == 2) {
                                $data = (object)[
                                    'from_name' => auth()->user()->email,
                                    'from_email' => auth()->user()->email,
                                    'to_name' => $enquiry->name,
                                    'to_email' => $enquiry->email,
                                    'msg' => $message,
                                    'apikey' => $mail_setting->mailchimp_apikey,
                                ];
                                $this->sendThroughMailchimp($data);
                            } elseif ($mail_setting->type == 1) {
                                $data = (object)[
                                    'from_name' => auth()->user()->email,
                                    'from_email' => auth()->user()->email,
                                    'to_name' => $enquiry->name,
                                    'to_email' => $enquiry->email,
                                    'msg' => $message,
                                ];
                                $this->approach3($data);
                            }
                            $enquiry->shop->number_of_emails = $enquiry->shop->number_of_emails - 1;
                            $enquiry->shop->save();
                        }
                    }
                }


                //Send SMS
                if ($enquiry->sms) {
                    $sms_setting = auth()->user()->smsSetting;
                    if (!$sms_setting) {
                        $sms_setting = User::findOrFail(1)->smsSetting;
                    }

                    $whatsapp_setting = auth()->user()->whatsappSetting;
                    if (!$whatsapp_setting) {
                        $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                    }
                    if ($sms_setting) {
                        if ($enquiry->shop->number_of_sms > 0) {
                            if ($sms_setting->type == 3) {
                                $data = (object)[
                                    'phone' => $enquiry->contact_number,
                                    'account_sid' => $sms_setting->twilio_account_sid,
                                    'auth_token' => $sms_setting->twilio_auth_token,
                                    'twilio_number' => $sms_setting->twilio_number,
                                    'msg' => $message,
                                ];
                                $this->sendThroughTwilio($data);
                            } elseif ($sms_setting->type == 1) {
                                $data = (object)[
                                    'apikey' => $sms_setting->pearlsms_api_key,
                                    'sender' => $sms_setting->pearlsms_sender,
                                    'phone' => $enquiry->contact_number,
                                    'msg' => $message,
                                ];
                                $response = $this->sendThroughPearl($data);
                                $response = json_decode($response);
                                if ($response->status != "ERROR") {
                                    $enquiry->shop->number_of_sms = $enquiry->shop->number_of_sms - 1;
                                    $enquiry->shop->save();
                                }
                            }

                        }

                    }

                    if ($whatsapp_setting) {
                        if ($enquiry->shop->number_of_whatsapp > 0) {
                            if ($whatsapp_setting->type == 1) {
                                $data = (object)[
                                    'api_key' => str_replace("+", "", $whatsapp_setting->cloudwhatsapp_api_key),
                                    'to' => str_replace("+", "", $enquiry->contact_number),
                                    'msg' => $message,
                                ];
                                $this->sendThroughCloud($data);
                            } elseif ($whatsapp_setting->type == 2) {
                                if ($enquiry->shop->number_of_whatsapp > 0) {
                                    $data = (object)[
                                        'from' => str_replace("+", "", $whatsapp_setting->whatsapp_vonage_from),
                                        'to' => str_replace("+", "", $enquiry->contact_number),
                                        'msg' => $message,
                                    ];
                                    $this->sendThroughVonage($data);

                                }
                            }
                            $enquiry->shop->number_of_whatsapp = $enquiry->shop->number_of_whatsapp - 1;
                            $enquiry->shop->save();
                        }


                    }
                }
            }

            DB::commit();
            return response([
                "data" => $this->getAddedEnquiry($enquiry->id),
                'message' => "Great! Enquiry has been saved successfully!",
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

    public function updateEnquiry(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'contact_number' => 'required',
                    'email_address' => 'required',
                    'estimate_date' => 'required',
                    'message' => 'required',
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            DB::beginTransaction();
            $enquiry = Enquiry::findOrFail($request->id);
            $old_status = $enquiry->status;
            $enquiry->name = $input['name'];
            $enquiry->email_address = $input['email_address'];
            $enquiry->contact_number = $input['contact_number'];
            $enquiry->estimate_date = $input['estimate_date'];
            $enquiry->status = $input['status'];
            if ($request->status == 1 && $old_status != 1){
                $enquiry->completed_at = Carbon::now();
            }
            $enquiry->message = $request->message;
            $enquiry->email = $request->email;
            $enquiry->sms = $request->sms;
            // $res = array_key_exists('email', $input);
            // if ($res == false) {
            //     $enquiry->email = 0;
            // } else {
            //     $enquiry->email = 1;
            // }
            // $res = array_key_exists('sms', $input);
            // if ($res == false) {
            //     $enquiry->sms = 0;
            // } else {
            //     $enquiry->sms = 1;
            // }
            if (auth()->user()->role == 2) {
                $enquiry->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $enquiry->user_id = auth()->user()->parent_id;
            }
            $enquiry->save();
            $old_devices = EnquiryBrand::where('enquiry_id', $enquiry->id)->get();
            foreach ($old_devices as $device) {
                $device->delete();
            }
            if($request->devices) {
                $devices = json_decode($request->devices);
                foreach ($devices as $device) {
                    $user_brand = new EnquiryBrand();
                    $user_brand->brand_id = $device->brandId;
                    $user_brand->device = $device->deviceTypeID;
                    $user_brand->enquiry = $device->enquiry;
                    $user_brand->enquiry_id = $enquiry->id;
                    $user_brand->device_id = $device->deviceID;
                    $user_brand->save();
                }
            }

            $message = $enquiry->message;

            $us = auth()->user();
            if ($us->role == 1) {
                $send = 1;
            } elseif ($us->role == 2) {
                $send = 1;
            } elseif ($us->role == 3) {
                $send = $us->permission->enquiries_send;
            }
            if ($send) {
                //Send Mail
                if ($old_status != $enquiry->status) {

                    if ($enquiry->email and $enquiry->email_address) {
                        $mail_setting = auth()->user()->mailSetting;
                        if (!$mail_setting) {
                            $mail_setting = User::findOrFail(1)->mailSetting;
                        }
                        if ($mail_setting) {
                            if ($enquiry->shop->number_of_emails > 0) {
                                if ($mail_setting->type == 2) {
                                    $data = (object)[
                                        'from_name' => auth()->user()->email,
                                        'from_email' => auth()->user()->email,
                                        'to_name' => $enquiry->name,
                                        'to_email' => $enquiry->email,
                                        'msg' => $message,
                                        'apikey' => $mail_setting->mailchimp_apikey,
                                    ];
                                    $this->sendThroughMailchimp($data);
                                } elseif ($mail_setting->type == 1) {
                                    $data = (object)[
                                        'from_name' => auth()->user()->email,
                                        'from_email' => auth()->user()->email,
                                        'to_name' => $enquiry->name,
                                        'to_email' => $enquiry->email,
                                        'msg' => $message,
                                    ];
                                    $this->approach3($data);
                                }
                                $enquiry->shop->number_of_emails = $enquiry->shop->number_of_emails - 1;
                                $enquiry->shop->save();
                            }
                        }
                    }


                    //Send SMS
                    if ($enquiry->sms) {
                        $sms_setting = auth()->user()->smsSetting;
                        if (!$sms_setting) {
                            $sms_setting = User::findOrFail(1)->smsSetting;
                        }

                        $whatsapp_setting = auth()->user()->whatsappSetting;
                        if (!$whatsapp_setting) {
                            $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                        }
                        if ($sms_setting) {
                            if ($enquiry->shop->number_of_sms > 0) {
                                if ($sms_setting->type == 3) {
                                    $data = (object)[
                                        'phone' => $enquiry->contact_number,
                                        'account_sid' => $sms_setting->twilio_account_sid,
                                        'auth_token' => $sms_setting->twilio_auth_token,
                                        'twilio_number' => $sms_setting->twilio_number,
                                        'msg' => $message,
                                    ];
                                    $this->sendThroughTwilio($data);
                                } elseif ($sms_setting->type == 1) {
                                    $data = (object)[
                                        'apikey' => $sms_setting->pearlsms_api_key,
                                        'sender' => $sms_setting->pearlsms_sender,
                                        'phone' => $enquiry->contact_number,
                                        'msg' => $message,
                                    ];
                                    $response = $this->sendThroughPearl($data);
                                    $response = json_decode($response);
                                    if ($response->status != "ERROR") {
                                        $enquiry->shop->number_of_sms = $enquiry->shop->number_of_sms - 1;
                                        $enquiry->shop->save();
                                    }
                                }

                            }

                        }

                        if ($whatsapp_setting) {
                            if ($enquiry->shop->number_of_whatsapp > 0) {
                                if ($whatsapp_setting->type == 1) {
                                    $data = (object)[
                                        'api_key' => str_replace("+", "", $whatsapp_setting->cloudwhatsapp_api_key),
                                        'to' => str_replace("+", "", $enquiry->contact_number),
                                        'msg' => $message,
                                    ];
                                    $this->sendThroughCloud($data);
                                } elseif ($whatsapp_setting->type == 2) {
                                    if ($enquiry->shop->number_of_whatsapp > 0) {
                                        $data = (object)[
                                            'from' => str_replace("+", "", $whatsapp_setting->whatsapp_vonage_from),
                                            'to' => str_replace("+", "", $enquiry->contact_number),
                                            'msg' => $message,
                                        ];
                                        $this->sendThroughVonage($data);

                                    }
                                }
                                $enquiry->shop->number_of_whatsapp = $enquiry->shop->number_of_whatsapp - 1;
                                $enquiry->shop->save();
                            }


                        }
                    }
                }

            }

            DB::commit();
            return response([
                "data" => $this->getEnquiryDetail($enquiry->id),
                'message' => "Great! Enquiry has been saved successfully!",
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

    public function deleteEnquiry(Request $request)
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
            $enquiry = Enquiry::find($id);
            if (!$enquiry) {
                return response([
                    'message' => "Enquiry not Found",
                    'error' => true
                ], 200);
            }

            $enquiry->delete();
            DB::commit();
            return response([
                'message' => "Enquiry Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function updateStatus(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'estimate_date' => 'required',
                    'message' => 'required',
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            DB::beginTransaction();
            $enquiry = Enquiry::findOrFail($request->id);
            $old_status = $enquiry->status;
            $enquiry->estimate_date = $input['estimate_date'];
            $enquiry->status = $input['status'];
            if ($request->status == 1 && $old_status != 1){
                $enquiry->completed_at = Carbon::now();
            }
            $enquiry->message = $request->message;
            $enquiry->email = $request->email;
            $enquiry->sms = $request->sms;

            $enquiry->save();


            $message = $enquiry->message;

            $us = auth()->user();
            if ($us->role == 1) {
                $send = 1;
            } elseif ($us->role == 2) {
                $send = 1;
            } elseif ($us->role == 3) {
                $send = $us->permission->enquiries_send;
            }
            if ($send) {
                //Send Mail
                if ($old_status != $enquiry->status) {

                    if ($enquiry->email and $enquiry->email_address) {
                        $mail_setting = auth()->user()->mailSetting;
                        if (!$mail_setting) {
                            $mail_setting = User::findOrFail(1)->mailSetting;
                        }
                        if ($mail_setting) {
                            if ($enquiry->shop->number_of_emails > 0) {
                                if ($mail_setting->type == 2) {
                                    $data = (object)[
                                        'from_name' => auth()->user()->email,
                                        'from_email' => auth()->user()->email,
                                        'to_name' => $enquiry->name,
                                        'to_email' => $enquiry->email,
                                        'msg' => $message,
                                        'apikey' => $mail_setting->mailchimp_apikey,
                                    ];
                                    $this->sendThroughMailchimp($data);
                                } elseif ($mail_setting->type == 1) {
                                    $data = (object)[
                                        'from_name' => auth()->user()->email,
                                        'from_email' => auth()->user()->email,
                                        'to_name' => $enquiry->name,
                                        'to_email' => $enquiry->email,
                                        'msg' => $message,
                                    ];
                                    $this->approach3($data);
                                }
                                $enquiry->shop->number_of_emails = $enquiry->shop->number_of_emails - 1;
                                $enquiry->shop->save();
                            }
                        }
                    }


                    //Send SMS
                    if ($enquiry->sms) {
                        $sms_setting = auth()->user()->smsSetting;
                        if (!$sms_setting) {
                            $sms_setting = User::findOrFail(1)->smsSetting;
                        }

                        $whatsapp_setting = auth()->user()->whatsappSetting;
                        if (!$whatsapp_setting) {
                            $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                        }
                        if ($sms_setting) {
                            if ($enquiry->shop->number_of_sms > 0) {
                                if ($sms_setting->type == 3) {
                                    $data = (object)[
                                        'phone' => $enquiry->contact_number,
                                        'account_sid' => $sms_setting->twilio_account_sid,
                                        'auth_token' => $sms_setting->twilio_auth_token,
                                        'twilio_number' => $sms_setting->twilio_number,
                                        'msg' => $message,
                                    ];
                                    $this->sendThroughTwilio($data);
                                } elseif ($sms_setting->type == 1) {
                                    $data = (object)[
                                        'apikey' => $sms_setting->pearlsms_api_key,
                                        'sender' => $sms_setting->pearlsms_sender,
                                        'phone' => $enquiry->contact_number,
                                        'msg' => $message,
                                    ];
                                    $response = $this->sendThroughPearl($data);
                                    $response = json_decode($response);
                                    if ($response->status != "ERROR") {
                                        $enquiry->shop->number_of_sms = $enquiry->shop->number_of_sms - 1;
                                        $enquiry->shop->save();
                                    }
                                }

                            }

                        }

                        if ($whatsapp_setting) {
                            if ($enquiry->shop->number_of_whatsapp > 0) {
                                if ($whatsapp_setting->type == 1) {
                                    $data = (object)[
                                        'api_key' => str_replace("+", "", $whatsapp_setting->cloudwhatsapp_api_key),
                                        'to' => str_replace("+", "", $enquiry->contact_number),
                                        'msg' => $message,
                                    ];
                                    $this->sendThroughCloud($data);
                                } elseif ($whatsapp_setting->type == 2) {
                                    if ($enquiry->shop->number_of_whatsapp > 0) {
                                        $data = (object)[
                                            'from' => str_replace("+", "", $whatsapp_setting->whatsapp_vonage_from),
                                            'to' => str_replace("+", "", $enquiry->contact_number),
                                            'msg' => $message,
                                        ];
                                        $this->sendThroughVonage($data);

                                    }
                                }
                                $enquiry->shop->number_of_whatsapp = $enquiry->shop->number_of_whatsapp - 1;
                                $enquiry->shop->save();
                            }


                        }
                    }
                }

            }

            DB::commit();
            return response([
                "data" => $this->getEnquiryDetail($enquiry->id),
                'message' => "Great! Enquiry has been saved successfully!",
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
            $data = Enquiry::where("user_id",$user_id)->get();
            (new FastExcel($data))->export("api-enquiries$user_id.csv", function ($pass) {
                if($pass->completed_at){
                    $complete = 'Yes';
                }else{
                    $complete = 'No';
                }
                $enquiry = EnquiryBrand::where("enquiry_id",$pass->id)->first();
                if ($enquiry){
                    if($enquiry->device == 1){
                        $device = 'Mobile';
                    }else{
                        $device = 'Laptop';
                    }
                    $br = Brand::findOrFail($enquiry->brand_id);
                    if ($br){
                        $brand = $br->name;
                    }else{
                        $brand ="";
                    }
                    $dev = Device::findOrFail($enquiry->device_id);
                    if ($dev){
                        $model = $dev->name;
                    }else{
                        $model ="";
                    }
                    $enq = $enquiry->enquiry;
                }else{
                    $device = "";
                    $brand = "";
                    $model = "";
                    $enq = "";
                }
                return [
                    'Name' => $pass->name,
                    'Contact Number' => $pass->contact_number,
                    'Complete' => $complete,
                    'Email Address' => $pass->email_address,
                    'Estimate Date' => $pass->estimate_date,
                    'Device' => $device,
                    'Brand' => $brand,
                    'Model' => $model,
                    'Enquiry' => $enq,
                    'Message' => $pass->message,
                ];

            });
            $url = url("public/api-enquiries$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Enquiries Export successfully", 'error' => false], 200);
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
                        $user = Enquiry::where([["name",$line['Name']],["user_id",$user_id]])->first();
                        if (!$user){
                            $user = new Enquiry();
                            if ($line['Complete'] == 'Yes'){
                                $user->completed_at = Carbon::now();
                                $user->status = 1;
                            }else{
                                $user->status = 0;
                            }
                        }else{
                            if ($line['Complete'] == 'Yes'){
                                if ($user->status == 0){
                                    $user->completed_at = Carbon::now();
                                }
                                $user->status = 1;
                            }else{
                                $user->status = 0;
                            }
                        }

                        $user->name = $line['Name'];
                        $user->email_address = $line['Email Address'];
                        $user->contact_number = $line['Contact Number'];
                        $user->estimate_date = $line['Estimate Date'];
                        $user->message = $line['Message'];
                        $user->user_id = $user_id;
                        $user->save();
                        $branq_enquiry = new EnquiryBrand();
                        $branq_enquiry->enquiry_id = $user->id;
                        $branq_enquiry->enquiry = $line['Enquiry'];
                        $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();
                        if (!$brand){
                            $brand = new Brand();
                            $brand->user_id = $user_id;
                            $brand->name = $line['Brand'];
                            $brand->save();
                        }
                        $branq_enquiry->brand_id = $brand->id;
                        $device = Device::where([["name",$line['Model']],["user_id",$user_id],["brand_id",$brand->id]])->first();
                        if ($line['Devices'] == 'Mobile'){
                            $devices = 1;
                        }else{
                            $devices = 2;
                        }
                        if (!$device){
                            $device = new Device();
                            $device->user_id = $user_id;
                            $device->brand_id = $brand->id;
                            $device->name = $line['Model'];
                            $device->type = $devices;
                            $device->save();
                        }

                        $branq_enquiry->device = $devices;
                        $branq_enquiry->device_id = $device->id;
                        return $branq_enquiry->save();

                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Enquiries Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
    public function getAddedEnquiry($id)
    {
        return $data = Enquiry::where('enquiries.id', $id)
            ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
            ->select(
                'enquiry_brands.*',
                'enquiries.*'
//                        'brands.name as brand_name'
            )
            ->first();
    }

    public function getEnquiry(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Enquiry::orderBy('enquiries.id', "Desc")
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'
//                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Enquiry::orderBy('id', "Desc")
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Enquiry::orderBy('enquiries.id', "Desc")->where("enquiries.user_id", $user->id)
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'
//                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Enquiry::orderBy('id', "Desc")->where("user_id", $user->id)
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Enquiry::orderBy('enquiries.id', "Desc")->where("enquiries.user_id", $user->parent_id)
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'

//                        'brands.name as brand_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Enquiry::orderBy('id', "Desc")->where("user_id", $user->parent_id)
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

    public function searchEnquiry(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Enquiry::orderBy('enquiries.id', "Desc")
                    ->where([['enquiries.name', 'like', "%{$request->keyword}%"]])
                    ->orWhere([['enquiries.email_address', 'like', "%{$request->keyword}%"]])
                    ->orWhere([['enquiries.contact_number', 'like', "%{$request->keyword}%"]])
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'
//                        'brands.name as brand_name'
                    )
                    ->get();

            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Enquiry::orderBy('enquiries.id', "Desc")->where("enquiries.user_id", $user->id)
                    ->where([['enquiries.name', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->id]])
                    ->orWhere([['enquiries.email_address', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->id]])
                    ->orWhere([['enquiries.contact_number', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->id]])
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'
//                        'brands.name as brand_name'
                    )
                    ->get();


            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Enquiry::orderBy('enquiries.id', "Desc")->where("enquiries.user_id", $user->parent_id)
                    ->where([['enquiries.name', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->parent_id]])
                    ->orWhere([['enquiries.email_address', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->parent_id]])
                    ->orWhere([['enquiries.contact_number', 'like', "%{$request->keyword}%"], ["enquiries.user_id", $user->parent_id]])
                    ->leftJoin('enquiry_brands', 'enquiries.id', '=', 'enquiry_brands.enquiry_id')
                    ->select(
                        'enquiry_brands.*',
                        'enquiries.*'

//                        'brands.name as brand_name'
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
    public function getEnquiryDetail($id){
        $brands = EnquiryBrand::select('enquiry_brands.*',\DB::raw('(case when (device = 1) then "Mobile Phone" else "Computer" end) as model_name'),'devices.name as device_name','brands.name as brand_name')
            ->leftJoin('devices','devices.id','=','enquiry_brands.device_id')
            ->leftJoin('brands','brands.id','=','enquiry_brands.brand_id')
            ->where('enquiry_brands.enquiry_id',$id)
            ->get();
        $enquiry = Enquiry::where('id',$id)->first();
        $enquiry['devices'] = $brands;
        return $enquiry;
    }
    public function getEnquiryDetailInfo(Request $request)
    {
        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'enquiry_id' => 'required'
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            return response([
                "data" => $this->getEnquiryDetail($request->enquiry_id),
                'message' => "shop enquires",
                'error' => false
            ], 200);

        } catch (Exception $e) {

        }
    }

    public function sendThroughCloud($data)
    {
        $api_key = $data->api_key;
        $to = $data->to;
        $msg = $data->msg;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey=' . $api_key . '&mobile=' . $to . '&msg=' . $msg . '',
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
    }

    public function sendThroughVonage($data)
    {
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

    public function sendThroughTwilio($data)
    {
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

    public function sendThroughPearl($data)
    {
        $curl = curl_init();
        $message = rawurlencode("Dear,  $data->msg PALLVI");
        $phone = $data->phone;
        $sender = ($data->sender) ? $data->sender : 'PALLVl';
        $apikey = ($data->apikey) ? $data->apikey : '855e314b060d485d9b4a6952c9f52bec';
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.pearlsms.com/public/sms/send?sender=' . $sender . '&smstype=TRANS&numbers=' . $phone . '&apikey=' . $apikey . '&message=' . $message . '',
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

    public function sendThroughMailchimp($data)
    {

        $mailchimp = new \MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey($data->apikey);
        $response = $mailchimp->messages->send(
            [
                "message" => [
                    "from_email" => $data->from_email,
                    "from_name" => $data->from_name,
                    "subject" => "Enquiry Sheet ",
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

    public function approach3($user)
    {
        $mail_setting = auth()->user()->mailSetting;
        if (!$mail_setting) {
            $mail_setting = User::findOrFail(1)->mailSetting;
        }
        if ($mail_setting) {
            $configuration = [
                'smtp_host' => $mail_setting->smtp_host,
                'smtp_port' => $mail_setting->smtp_port,
                'smtp_username' => $mail_setting->smtp_username,
                'smtp_password' => $mail_setting->smtp_password,
                'smtp_encryption' => $mail_setting->smtp_encryption,
                'from_email' => $mail_setting->from_email,
                'from_name' => $mail_setting->from_name,
                'replyTo_email' => $mail_setting->from_email,
                'replyTo_name' => $mail_setting->from_name,
            ];
        } else {
            $configuration = [
                'smtp_host' => 'mail.webexert.us',
                'smtp_port' => '465',
                'smtp_username' => 'noreply@webexert.us',
                'smtp_password' => 'LiB3ds9^euRq',
                'smtp_encryption' => 'ssl',
                'from_email' => 'noreply@webexert.us',
                'from_name' => 'FoneFix',
                'replyTo_email' => 'noreply@webexert.us',
                'replyTo_name' => 'FoneFix',
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
            'name' => $user->to_name,
            'user_email' => $user->to_email,
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "Enquiry Sheet Status is Updated",

            'msg' => $user->msg,
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo'] : '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title'] : 'Libby Kitchen',
        );
        Mail::send('emails.order', $data, function ($message) use ($data) {
            $message->to($data['user_email'])
                ->from($data['user_email'], $data['from_name'])
                ->subject($data['subject']);
        });

//        Mail::to(  $user->email )->send(new DynamicSMTPMail( $user->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']] ));
        Config::set('mail.mailers.smtp', $backup);
    }


}
