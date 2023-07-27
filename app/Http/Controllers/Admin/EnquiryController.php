<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Device;
use App\Models\Enquiry;
use App\Models\EnquiryBrand;
use App\Models\PreRepair;
use App\Models\Setting;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\SmsTempalate;
use App\Models\TemplateUse;
use App\Models\WTemplateUse;
use App\Models\SmsSetting;
use App\Models\Sms;
use Twilio\Rest\Client;
use Response;
use Auth;
use Illuminate\Support\Facades\Config;
use App\Mail\ManualEmail;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Enquiry';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();

	    return view('admin.enquiries.index',compact('title','shops'));
    }

	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'contact_number',
			4 => 'status',
			5 => 'created_at',
			6 => 'completed_at',
			7 => 'user_id',
			8 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Enquiry::where([['user_id',$id]])->count();
        }else{
            $totalData = Enquiry::count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
            if ($id){

                $users = Enquiry::where([['user_id',$id]])->offset($start)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Enquiry::where([['user_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('user_id', $request->shop_id);
                                        })->count();
            }else{
                $users = Enquiry::when($request->has('shop_id'), function ($query) use ($request) {
                                $query->where('user_id', $request->shop_id);
                                })->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                $totalFiltered = Enquiry::when($request->has('shop_id'), function ($query) use ($request) {
                                                $query->where('user_id', $request->shop_id);
                                            })->count();
            }

		}else{
			$search = $request->input('search.value');
            if ($id){


                $users = Enquiry::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->get();
                $totalFiltered = Enquiry::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->count();
            }else{
                $users = Enquiry::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->get();
                $totalFiltered = Enquiry::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->count();
            }

		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('enquiries.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="Enquiry[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
				$nestedData['phone'] = $r->contact_number;
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				if($r->completed_at) {
                    $nestedData['completed_at'] = date('d-m-Y',strtotime($r->completed_at));
                }else{
                    $nestedData['completed_at'] = 'Nil';
                }
                if($r->status){
                    $nestedData['status'] = '<a class="label label-success label-inline mr-2" href="#" onclick="event.preventDefault();viewInfo('.$r->id.');" >Completed</a>';
                }else{
                    $nestedData['status'] = '<a class="label label-danger label-inline mr-2" href="#" onclick="event.preventDefault();viewInfo('.$r->id.');" >Incomplete</span>';
                }
                if($r->brand){
                    $nestedData['enquiry'] = $r->brand->enquiry;
                }else{
                    $nestedData['enquiry'] = "Nil";
                }
                if($r->shop){
                    $nestedData['shop'] = $r->shop->name;
                }else{
                    $nestedData['shop'] = "Nil";
                }
                $user = Auth::user();
                if($user->role == 1){
                    $edit = 1;
                    $del = 1;
                    $view = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->enquiries_view;
                    $edit = $user->permission->enquiries_edit;
                    $del = $user->permission->enquiries_delete;
                }
                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $edit_link = '<a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
                                    $edit_link
                                    $delete_link
                                </td>
                                </div>
                            ";
				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);

		echo json_encode($json_data);

	}
    public function create()
    {
        if(Auth::user()->role == 2){
            $brands = Brand::where("user_id", Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id", Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $shops = User::where('is_admin',1)->where('role',2)->get();
        return view('admin.enquiries.create',['title' => 'Add New Enquiry ',"brands"=>$brands,'shops'=>$shops]);
    }


    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'name' => 'required|max:255',
//                'email_address' => 'required',
                'contact_number' => 'required',
            ]);

            $input = $request->all();
            $user = new Enquiry();
            $user->name = $input['name'];
            $user->email_address = $input['email_address'];
            $user->contact_number = $input['contact_number'];
            $user->estimate_date = $input['estimate_date'];
            $res = array_key_exists('status', $input);
            if ($res == false) {
                $user->status = 0;
            } else {
                $user->status = 1;

            }
            $user->message = $request->message;

            $res = array_key_exists('email', $input);
            if ($res == false) {
                $user->email = 0;
            } else {
                $user->email = 1;
            }
            $res = array_key_exists('sms', $input);
            if ($res == false) {
                $user->sms = 0;
            } else {
                $user->sms = 1;
            }
            if (Auth::user()->role == 2){
                $user->user_id = Auth::id();
            }
            if (Auth::user()->role == 3){
                $user->user_id = Auth::user()->parent_id;
            }
            $user->save();


            for ($x = 0; $x < $request->dev_count ; $x++) {
                $res = array_key_exists("device$x", $input);
                if ($res) {
                    $user_brand = new EnquiryBrand();
                    $user_brand->brand_id = $input["brand$x"];
                    $user_brand->device = $input["device$x"];
                    $user_brand->enquiry = $input["enquiry$x"];
                    $user_brand->enquiry_id = $user->id;

                    $res = array_key_exists("model$x", $input);
                    if ($res) {
                        $user_brand->device_id = $input["model$x"];
                    }
                    $user_brand->save();
                }
            }

            $message = $user->message;

            $us = Auth::user();
            if($us->role == 1){
                $send = 1;
            }elseif($us->role == 2){
                $send = 1;
            }elseif($us->role == 3){
                $send = $us->permission->enquiries_send;
            }
            if($send){
                //Send Mail
                if ($user->email and $user->email_address){

                    $mail_setting = Auth::user()->mailSetting;
                    if (!$mail_setting){
                        $mail_setting = User::findOrFail(1)->mailSetting;
                    }
                    if ($mail_setting){

                            if ($mail_setting->type == 2){

                                $data = (object) [

                                    'to_name' => $user->name,
                                    'to_email' =>  $user->email_address,
                                    'msg' =>  $message,
                                    'apikey' =>  $mail_setting->mailchimp_apikey,
                                ];
                                $this->sendThroughMailchimp($data);
                            }elseif ($mail_setting->type == 1){

                                $data = (object) [
                                    'from_name' => Auth::user()->email,
                                    'from_email' => Auth::user()->email,
                                    'to_name' => $user->name,
                                    'to_email' =>  $input['email_address'],
                                    'msg' =>  $message,
                                ];
                                $this->approach3($data);
                            }
                            // $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
                            // $user->shop->save();

                    }
                }


                //Send SMS
                if ($user->sms) {

                    $sms_template = SmsTempalate::where('type','3')->first();
                    if(!empty($sms_template)){
                        $used = TemplateUse::where('user_id',auth()->user()->id)
                                            ->where('template_id',$sms_template->id)
                                            ->where('used',1)
                                            ->first();
                        $message = $sms_template->description;
                        $message = str_replace("{customer_name}",$input['name'],$message);
                        // $message = str_replace("{enquired_products}",$request->device0,$message);

                        $id = $sms_template->id;
                        if(!empty($used)){
                            $sms = User::where('id',auth()->user()->id)->with('smsSetting', function($q) use ($id){
                                $q->where('template_id', 'LIKE', '%'. $id .'%');
                            })->first();

                            $sms_setting = $sms->smsSetting;
                        }
                        else{

                            $sms = User::where('id',1)->with('smsSetting', function($q) use ($id){
                                $q->where('template_id', 'LIKE', '%'. $id .'%');
                                })->first();
                                $sms_setting = $sms->smsSetting;

                        }
                        if(!$sms_setting) {
                            $sms = User::where('id', 1)->with('smsSetting', function($q) use ($id){
                                $q->where('template_id', 'LIKE', '%'. $id .'%');
                            })->first();
                            $sms_setting = $sms->smsSetting;
                        }




                        if ($sms_setting) {

                            if ($sms_setting->type == 2){
                                $data = (object) [
                                    'phone' => $input['contact_number'],
                                    'account_sid' => $sms_setting->twilio_account_sid,
                                    'auth_token' => $sms_setting->twilio_auth_token,
                                    'twilio_number' =>  $sms_setting->twilio_number,
                                    'msg' =>  $message,
                                ];
                                $this->sendThroughTwilio($data);
                            }elseif ($sms_setting->type == 1){
                                $data = (object) [
                                    'apikey' => $sms_setting->pearlsms_api_key,
                                    'sender' => $sms_setting->pearlsms_sender,
                                    'header' => $sms_setting->pearlsms_header,
                                    'footer' => $sms_setting->pearlsms_footer,
                                    'username' => $sms_setting->pearlsms_username,
                                    'phone' => $input['contact_number'],
                                    'msg' =>  $message,
                                ];
                                $response =  $this->sendThroughPearl($data);
                                $response = json_decode($response);

                            }elseif ($sms_setting->type == 3){
                                $data = (object) [
                                    'apikey' => $sms_setting->bulksms_apikey,
                                    'sender' => $sms_setting->bulksms_sendername,
                                    'username' => $sms_setting->bulksms_username,
                                    'sms_type' => $sms_template->sms_type,
                                    'sms_peid' => $sms_template->sms_peid,
                                    'sms_template_id' => $sms_template->sms_template_id,
                                    'phone' => $input['contact_number'],
                                    'msg' =>  $message,
                                ];
                                $response =  $this->sendThroughBulk($data);
                                $response = json_decode($response);

                            }
                        }
                    }

                    $whatsapp_template = WhatsappTemplate::where('type','3')->first();
                    if(!empty($whatsapp_template)){
                        $wused = WTemplateUse::where('user_id',auth()->user()->id)->where('template_id',$whatsapp_template->id)->first();
                        $message = $whatsapp_template->description;
                        $message = str_replace("{customer_name}",$input['name'],$message);
                        $id = $whatsapp_template->id;
                        if(!empty($wused)){
                        $whatsapp =  User::where('id',auth()->user()->id)->with('whatsappSetting', function($q) use ($id){
                                        $q->where('template_id', 'LIKE', '%'. $id .'%');
                                    })->first();
                        $whatsapp_setting = $whatsapp->whatsappSetting;
                                }
                        if (!$whatsapp_setting){
                            $whatsapp =User::where('id',1)->with('whatsappSetting', function($q) use ($id){
                                $q->where('template_id', 'LIKE', '%'. $id .'%');
                            })->first();
                            $whatsapp_setting = $whatsapp->whatsappSetting;
                        }

                        if ($whatsapp_setting){

                            if ($whatsapp_setting->type == 1){
                                $data = (object) [
                                    'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                    'to' => str_replace("+","", $input['contact_number']),
                                    'msg' =>  $message,
                                ];
                                $this->sendThroughCloud($data);
                                }elseif ($whatsapp_setting->type == 2){

                                    $data = (object) [
                                        'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                        'to' => str_replace("+","", $input['contact_number']),
                                        'msg' =>  $message,
                                    ];
                                    $this->sendThroughVonage($data);


                            }

                    }

                }
            }
        }
            Session::flash('success_message', 'Great! Enquiry has been saved successfully!');
            $user->save();
            return redirect()->back();

        }catch(Exception $e){
            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }

    }
    public function sendThroughCloud($data){
        $api_key = $data->api_key;
        $to = $data->to;
        $msg = $data->msg;

        $curl = curl_init();

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

        $response = curl_exec($curl);

        curl_close($curl);
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
        $message = rawurlencode("Dear,  $data->msg PALLVI");
        $phone = $data->phone;
        $sender = ($data->sender)?$data->sender : 'PALLVl';
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

    public function approach3($user) {
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
            'name' => $user->to_name,
            'user_email' => $user->to_email,
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "Job Sheet Status is Updated",

            'msg' => $user->msg,
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );

        // Mail::send('emails.order', $data, function($message) use ($data) {
        //     $message->to($data['user_email'])
        //     ->subject('Enquiry update');
        //     $message->from($data['from_email']);
        //     });
        $email = new ManualEmail();

        Mail::to($user->to_email)->send($email);

         return "Email sent successfully!";
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function import()
    {
        return view('admin.enquiries.import', ['title' => 'Enquiry Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/enquiry.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'enquiry-sample.xlsx', $headers);
    }



    public function importSave(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('file');
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $destinationPath = "uploads/users/";
                $extension = $file->getClientOriginalExtension('file');
                $fileName = $file->getClientOriginalName('file'); // renameing image
                $request->file('file')->move($destinationPath, $fileName);
                $readFile = $destinationPath . $fileName;
//                $organization = Auth::user()->id;
//                $request->session()->put('organization', $organization);
                $wfts = (new FastExcel)->import($readFile, function ($line) {
                    if (Auth::user()->role == 2){
                        $user_id = Auth::id();
                    }elseif (Auth::user()->role == 3){
                        $user_id = Auth::user()->parent_id;
                    }else{
                        $user_id = Auth::id();
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

        Session::flash('success_message', 'Success! File Imported successfully!');
        return redirect()->back();

    }
    public function export()
    {
        if (Auth::user()->role == 2){
            $user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user_id = Auth::user()->parent_id;
        }elseif (Auth::user()->role == 1){
            $user_id = Auth::id();
        }
        $data = Enquiry::where("user_id",$user_id)->get();
        return Response::download((new FastExcel($data))->export('enquiries.csv', function ($pass) {
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

        }));
    }
    public function show($id)
    {
	    $user = Enquiry::find($id);
	    return view('admin.enquiries.single', ['title' => 'Enquiry detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = Enquiry::findOrFail($request->id);
		return view('admin.enquiries.detail', ['title' => 'Enquiry Detail', 'user' => $user]);
	}
	public function getEnquirys(Request $request)
	{
		$Enquiry_models = Enquiry::where([["type",$request->Enquiry],["brand_id",$request->brand]])->get();
        return view('admin.jobs.Enquiry-models', ['title' => 'Enquiry Detail', 'Enquiry_models' => $Enquiry_models]);
	}
	public function getPreRepair(Request $request)
	{
		$Enquiry = Enquiry::find($request->id);
        return view('admin.jobs.pre-repair', ['title' => 'Enquiry Detail', 'Enquiry' => $Enquiry]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Enquiry::find($id);
	    if(Auth::user()->role == 2){
            $brands = Brand::where("user_id", Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id", Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $shops = User::where('is_admin',1)->where('role',2)->get();
	    return view('admin.enquiries.edit', ['title' => 'Edit Enquiry details','brands' => $brands ,'shops'=>$shops])->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
	    $user = Enquiry::find($id);
        try{
            $this->validate($request, [
                'name' => 'required|max:255',
//                'email_address' => 'required',
                'contact_number' => 'required',
            ]);
            $old_status = $user->status;
            $input = $request->all();
            $user->name = $input['name'];
            $user->email_address = $input['email_address'];
            $user->contact_number = $input['contact_number'];
            $user->estimate_date = $input['estimate_date'];
            $res = array_key_exists('status', $input);
            if ($res == false) {
                $user->status = 0;
            } else {
                $user->status = 1;
                if ($old_status != 1){
                    $user->completed_at = Carbon::now();
                }
            }
            $user->message = $request->message;

            $res = array_key_exists('email', $input);
            if ($res == false) {
                $user->email = 0;
            } else {
                $user->email = 1;
            }
            $res = array_key_exists('sms', $input);
            if ($res == false) {
                $user->sms = 0;
            } else {
                $user->sms = 1;
            }
            if (Auth::user()->role == 2){
                $user->user_id = Auth::id();
            }
            if (Auth::user()->role == 3){
                $user->user_id = Auth::user()->parent_id;
            }
            $user->save();
            for ($x = 0; $x < $request->dev_count ; $x++) {
                $res = array_key_exists("device$x", $input);
                if ($res) {
                    $user_brand = new EnquiryBrand();
                    $user_brand->brand_id = $input["brand$x"];
                    $user_brand->device = $input["device$x"];
                    $user_brand->enquiry = $input["enquiry$x"];
                    $user_brand->enquiry_id = $user->id;

                    $res = array_key_exists("model$x", $input);
                    if ($res) {
                        $user_brand->device_id = $input["model$x"];
                    }
                    $user_brand->save();
                }
            }

            $message = $user->message;


            $us = Auth::user();
            if($us->role == 1){
                $send = 1;
            }elseif($us->role == 2){
                $send = 1;
            }elseif($us->role == 3){
                $send = $us->permission->enquiries_send;
            }
            // if($send){
            //     //Send Mail

            //     //Send Mail
            //     if ($user->email and $user->email_address){
            //         $mail_setting = Auth::user()->mailSetting;
            //         if (!$mail_setting){
            //             $mail_setting = User::findOrFail(1)->mailSetting;
            //         }
            //         if ($mail_setting){
            //             if($user->shop->number_of_emails > 0){
            //                 if ($mail_setting->type == 2){
            //                     $data = (object) [
            //                         'from_name' => Auth::user()->email,
            //                         'from_email' => Auth::user()->email,
            //                         'to_name' => $user->name,
            //                         'to_email' =>  $user->email_address,
            //                         'msg' =>  $message,
            //                         'apikey' =>  $mail_setting->mailchimp_apikey,
            //                     ];
            //                     $this->sendThroughMailchimp($data);
            //                 }elseif ($mail_setting->type == 1){
            //                     $data = (object) [
            //                         'from_name' => Auth::user()->email,
            //                         'from_email' => Auth::user()->email,
            //                         'to_name' => $user->name,
            //                         'to_email' =>  $user->email_address,
            //                         'msg' =>  $message,
            //                     ];
            //                     $this->approach3($data);
            //                 }
            //                 $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
            //                 $user->shop->save();
            //             }
            //         }
            //     }


            //     //Send SMS
            //     if ($user->sms) {
            //         $sms_template = SmsTempalate::where('type','1')->first();

            //         if(!empty($sms_template)){

            //             $message = $sms_template->description;
            //             $message = str_replace("{customer_name}",$input['name'],$message);


            //             $id = $sms_template->id;
            //             $sms_setting ='';
            //             $sms = User::where('id',auth()->user()->id)->with('smsSetting', function($q) use ($id){
            //                 $q->where('template_id', 'LIKE', '%'. $id .'%');
            //             })->first();

            //             $sms_setting = $sms->smsSetting;

            //             if(!empty($sms_setting)) {
            //                 $sms = User::where('id', 1)->with('smsSetting', function($q) use ($id){
            //                     $q->where('template_id', 'LIKE', '%'. $id .'%');
            //                 })->first();
            //                 $sms_setting = $sms->smsSetting;
            //             }

            //             $whatsapp_setting = Auth::user()->whatsappSetting;
            //             if (!$whatsapp_setting){
            //                 $whatsapp_setting = User::findorFail('1')->whatsappSetting;
            //             }


            //             if ($sms_setting) {

            //                 if ($sms_setting->type == 2){
            //                     $data = (object) [
            //                         'phone' => $input['contact_number'],
            //                         'account_sid' => $sms_setting->twilio_account_sid,
            //                         'auth_token' => $sms_setting->twilio_auth_token,
            //                         'twilio_number' =>  $sms_setting->twilio_number,
            //                         'msg' =>  $message,
            //                     ];
            //                     $this->sendThroughTwilio($data);
            //                 }elseif ($sms_setting->type == 1){
            //                     $data = (object) [
            //                         'apikey' => $sms_setting->pearlsms_api_key,
            //                         'sender' => $sms_setting->pearlsms_sender,
            //                         'header' => $sms_setting->pearlsms_header,
            //                         'footer' => $sms_setting->pearlsms_footer,
            //                         'username' => $sms_setting->pearlsms_username,
            //                         'phone' => $input['contact_number'],
            //                         'msg' =>  $message,
            //                     ];
            //                     $response =  $this->sendThroughPearl($data);
            //                     $response = json_decode($response);
            //                     if ($response->status != "ERROR"){
            //                         $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
            //                         $user->shop->save();
            //                     }
            //                 }elseif ($sms_setting->type == 3){
            //                     $data = (object) [
            //                         'apikey' => $sms_setting->bulksms_apikey,
            //                         'sender' => $sms_setting->bulksms_sendername,
            //                         'username' => $sms_setting->bulksms_username,
            //                         'sms_type' => $sms_template->sms_type,
            //                         'sms_peid' => $sms_template->sms_peid,
            //                         'sms_template_id' => $sms_template->sms_template_id,
            //                         'phone' =>$input['contact_number'],
            //                         'msg' =>  $message,
            //                     ];
            //                     $response =  $this->sendThroughBulk($data);
            //                     $response = json_decode($response);

            //                 }
            //             }

            //             if ($whatsapp_setting){

            //                     if ($whatsapp_setting->type == 1){
            //                         $data = (object) [
            //                             'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
            //                             'to' => str_replace("+","",$input['contact_number']),
            //                             'msg' =>  $message,
            //                         ];
            //                         $this->sendThroughCloud($data);
            //                         }elseif ($whatsapp_setting->type == 2){

            //                             $data = (object) [
            //                                 'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
            //                                 'to' => str_replace("+","",$input['contact_number']),
            //                                 'msg' =>  $message,
            //                             ];
            //                             $this->sendThroughVonage($data);


            //                     }

            //             }

            //         }
            //     }
            // }
            Session::flash('success_message', 'Great! Enquiry has been saved successfully!');
            $user->save();
            return redirect()->back();

        }catch(Exception $e){
            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }
    }
    public function updateStatus(Request $request)
    {
        $id = $request->id;
	    $user = Enquiry::find($id);
        try{

            $old_status = $user->status;
            $input = $request->all();
            $user->estimate_date = $input['estimate_date'];
            $res = array_key_exists('status', $input);
            if ($res == false) {
                $user->status = 0;
            } else {
                $user->status = 1;
                if ($old_status != 1){
                    $user->completed_at = Carbon::now();
                }
            }
            $user->message = $request->message;

            $res = array_key_exists('email', $input);
            if ($res == false) {
                $user->email = 0;
            } else {
                $user->email = 1;
            }
            $res = array_key_exists('sms', $input);
            if ($res == false) {
                $user->sms = 0;
            } else {
                $user->sms = 1;
            }
            if (Auth::user()->role == 2){
                $user->user_id = Auth::id();
            }
            if (Auth::user()->role == 3){
                $user->user_id = Auth::user()->parent_id;
            }
            $user->save();


            $message = $user->message;


            $us = Auth::user();
            if($us->role == 1){
                $send = 1;
            }elseif($us->role == 2){
                $send = 1;
            }elseif($us->role == 3){
                $send = $us->permission->enquiries_send;
            }
            if($send){
                //Send Mail

                //Send Mail
                if ($user->email and $user->email_address){
                    $mail_setting = Auth::user()->mailSetting;
                    if (!$mail_setting){
                        $mail_setting = User::findOrFail(1)->mailSetting;
                    }
                    if ($mail_setting){
                        if($user->shop->number_of_emails >= 0){
                            if ($mail_setting->type == 2){
                                $data = (object) [
                                    'from_name' => Auth::user()->email,
                                    'from_email' => Auth::user()->email,
                                    'to_name' => $user->name,
                                    'to_email' =>  $user->email_address,
                                    'msg' =>  $message,
                                    'apikey' =>  $mail_setting->mailchimp_apikey,
                                ];
                                $this->sendThroughMailchimp($data);
                            }elseif ($mail_setting->type == 1){
                                $data = (object) [
                                    'from_name' => Auth::user()->email,
                                    'from_email' => Auth::user()->email,
                                    'to_name' => $user->name,
                                    'to_email' =>  $user->email_address,
                                    'msg' =>  $message,
                                ];
                                $this->approach3($data);
                            }
                            $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
                            $user->shop->save();
                        }
                    }
                }


                //Send SMS
                if ($user->sms) {
                    $sms_setting = Auth::user()->smsSetting;
                    if (!$sms_setting) {
                        $sms_setting = User::findOrFail(1)->smsSetting;
                    }
                    $whatsapp_setting = Auth::user()->whatsappSetting;
                    if (!$whatsapp_setting){
                        $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                    }
                    if ($sms_setting) {
                        if($user->shop->number_of_sms >= 0){
                            if ($sms_setting->type == 3) {
                                $data = (object)[
                                    'phone' => $user->contact_number,
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
                                    'phone' => $user->contact_number,
                                    'msg' => $message,
                                ];
                                $response =  $this->sendThroughPearl($data);
                                $response = json_decode($response);
                                if ($response->status != "ERROR"){
                                    $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                                    $user->shop->save();
                                }
                            }

                        }

                    }


                    if ($whatsapp_setting){
                        if($user->shop->number_of_whatsapp > 0){
                            if ($whatsapp_setting->type == 1){
                                $data = (object) [
                                    'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                    'to' => str_replace("+","",$user->contact_number),
                                    'msg' =>  $message,
                                ];
                                $this->sendThroughCloud($data);
                            }elseif ($whatsapp_setting->type == 2){
                                if($user->shop->number_of_whatsapp > 0){
                                    $data = (object) [
                                        'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                        'to' => str_replace("+","",$user->contact_number),
                                        'msg' =>  $message,
                                    ];
                                    $this->sendThroughVonage($data);

                                }
                            }
                            $user->shop->number_of_whatsapp = $user->shop->number_of_whatsapp - 1;
                            $user->shop->save();
                        }



                    }
                }
            }
            Session::flash('success_message', 'Great! Enquiry has been saved successfully!');
            $user->save();
            return redirect()->back();

        }catch(Exception $e){
            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBrand($id)
    {
        $user = EnquiryBrand::find($id);
        $user->delete();
        Session::flash('success_message', 'Brand successfully deleted!');
        return redirect()->back();
    }
    public function destroy($id)
    {
	    $user = Enquiry::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Enquiry successfully deleted!');
	    return redirect()->route('enquiries.index');

    }
    public function preRepairDelete($id)
    {
	    $user = PreRepair::find($id);
        $user->delete();
        Session::flash('success_message', 'Pre Repair successfully deleted!');
	    return redirect()->back();

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'Enquiry' => 'required',

		]);
		foreach ($input['Enquiry'] as $index => $id) {

			$user = Enquiry::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Enquiry successfully deleted!');
		return redirect()->back();

	}
    public function sendThroughBulk($data){
        $sender = ($data->sender)?$data->sender : 'FONFIX';
        $username = ($data->username)?$data->username : 'thefonefix21';
        $message = rawurlencode("$data->msg");
        $phone = $data->phone;
        $sms_type = $data->sms_type;
        $sms_peid = $data->sms_peid;
        $sms_template_id = $data->sms_template_id;
        $apikey = ($data->apikey)?$data->apikey : '8721ed80-7591-41c4-a96c-76a9c1768fec';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.bulksmsind.in/v2/sendSMS?username='.$username.'&message='.$message.'&sendername='.$sender.'&smstype='.$sms_type.'&numbers='.$phone.'&apikey='.$apikey.'&peid='.$sms_peid.'&templateid='.$sms_template_id.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        return $response;
    }
}
