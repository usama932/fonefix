<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Courier;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Status;
use App\Models\UsePart;
use App\Models\User;
use App\Models\BasicSetting;
use App\Models\SmsSetting;
use Mail;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Models\SmsTempalate;
use App\Models\WhatsappTemplate;
use App\Models\TemplateUse;
use App\Models\WTemplateUse;
use Illuminate\Support\Facades\Session;
use Auth;

class InvoiceController extends Controller
{

    public function index()
    {
	    $title = 'Invoice ';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    return view('admin.invoices.index',compact('title','shops'));
    }



	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'id',
			2 => 'customer_id',
			3 => 'created_at',
			6 => 'action'
		);
        if  (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Invoice::where([['user_id',$id],['job_id',null]])->count();
        }else{
            $totalData = Invoice::where([['job_id',null]])->count();
        }


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            if ($id){
                $users = Invoice::where([['user_id',$id],['job_id',null]])->offset($start)
                    ->limit($limit)
                    ->latest()
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->get();
                $totalFiltered = Invoice::where([['user_id',$id],['job_id',null]])->when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('user_id', $request->shop_id);
                                        })->latest()->count();
            }else{
                $users = Invoice::where([['job_id',null]])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->offset($start)
                    ->limit($limit)
                    ->latest()
                    ->get();
                $totalFiltered = Invoice::where([['job_id',null]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                })->latest()->count();
            }

        }else{
            $search = $request->input('search.value');

            if ($id){
                $users = Invoice::when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                    ['job_id',null],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['job_id',null],
                        ['user_id',$id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->latest()
                    ->get();
                $totalFiltered = Invoice::when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                    ['job_id',null],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['job_id',null],
                        ['user_id',$id]
                    ])
                    ->latest()
                    ->count();

            }else{
                $users = Invoice::when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                    ['job_id',null]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['job_id',null]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->latest()
                    ->get();
                $totalFiltered = Invoice::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                    ['job_id',null]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['job_id',null]
                    ])
                    ->latest()
                    ->count();

            }



        }

		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('invoices.edit',$r->id);
				$show_url = route('invoices.show',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="statuses[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['invoice_id'] = $r->number;
                if(auth()->user()->role == '1'){
                    $nestedData['shop'] = $r->shop->name ?? 'Not Assign';
                }

                $nestedData['customer'] = $r->customer->name ?? '';
                $nestedData['amount'] = $r->total;

				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));

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
                    $view = $user->permission->invoice_view;
                    $edit = $user->permission->invoice_edit;
                    $del = $user->permission->invoice_delete;
                }
                $view_link = '<a class="btn btn-sm btn-clean btn-icon" href="'.$show_url.'">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $edit_link = '<a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del($r->id);\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link

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
	    $title = 'Add New invoice';
        $user = Auth::user();
        $id = Auth::id();
        if($user->role == 2){
            $products = Product::where([["user_id",$user->id]])->orderBy('name', 'asc')->get();
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->get();
        }
        elseif($user->role == 3){
            $products = Product::where([["user_id",$user->parent_id]])->orderBy('name', 'asc')->get();
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->get();
        }else{
            $products = Product::orderBy('name', 'asc')->get();
            $users = User::where([["is_admin",0]])->orderBy('name', 'asc')->get();

        }
        return view('admin.invoices.create', ['title' => $title,'products'=>$products,'users'=>$users]);
    }


    public function store(Request $request)
    {
   // dd($request->all());
       $customer = User::find($request->user);

        $input = $request->all();
	    $user = Auth::user();
        $invoice = new Invoice();
        $invoice->total = $request->total;
        $invoice->discount = $request->discount;
        if (Auth::user()->role == 1){
            $invoice->user_id =  Auth::id();
        }
        if (Auth::user()->role == 2){
            $invoice->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
            $invoice->user_id =  Auth::user()->parent_id;
        }
        $invoice->discount_type = $request->discount_type;
        $invoice->payment_method = $request->payment_type;
        $invoice->customer_id = $request->user;
        $invoice->number = date('YmdHis');
        $invoice->save();
       // dd($invoice->customer);
        foreach ($input["product_ids"] as $index => $product_id) {
            $product = Product::findOrFail($product_id);
            $user = new UsePart();
            $user->description = $product->name;
            $user->product_id = $product->id;
            $user->amount = $product->sale_price;
            $user->invoice_id = $invoice->id;
            $user->quantity = $input['qty'][$index];
            $user->save();
            if ($product->manage_stock)
            {
                $product->decrement("quantity",$input['qty'][$index]);
                $product->save();
            }
        }
        $us = $invoice->customer;

        if($request->sms == 1){
            $sms_template = SmsTempalate::where('type','1')->first();

            if(!empty($sms_template)){
                $used = TemplateUse::where('user_id',auth()->user()->id)
                                    ->where('template_id',$sms_template->id)
                                    ->where('used',1)
                                    ->first();
                $message = $sms_template->description;
                $message = str_replace("{customer_name}",$customer->name,$message);

                $id = $sms_template->id;
                $sms_setting ='';
                if(!empty($used)){
                $sms = User::where('id',auth()->user()->id)->with('smsSetting', function($q) use ($id){
                    $q->where('template_id', 'LIKE', '%'. $id .'%');
                })->first();

                $sms_setting = $sms->smsSetting;

                }   else{

                    $sms = User::where('id',1)->with('smsSetting', function($q) use ($id){
                        $q->where('template_id', 'LIKE', '%'. $id .'%');
                        })->first();
                        $sms_setting = $sms->smsSetting;

                }

                if(!empty($sms_setting)) {
                    $sms = User::where('id', 1)->with('smsSetting', function($q) use ($id){
                        $q->where('template_id', 'LIKE', '%'. $id .'%');
                    })->first();
                    $sms_setting = $sms->smsSetting;
                }




                if ($sms_setting) {

                    if ($sms_setting->type == 2){
                        $data = (object) [
                            'phone' => $customer->phone,
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
                            'phone' => $customer->phone,
                            'msg' =>  $message,
                        ];
                        $response =  $this->sendThroughPearl($data);
                        $response = json_decode($response);
                        if ($response->status != "ERROR"){
                            $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                            $user->shop->save();
                        }
                    }elseif ($sms_setting->type == 3){
                        $data = (object) [
                            'apikey' => $sms_setting->bulksms_apikey,
                            'sender' => $sms_setting->bulksms_sendername,
                            'username' => $sms_setting->bulksms_username,
                            'sms_type' => $sms_template->sms_type,
                            'sms_peid' => $sms_template->sms_peid,
                            'sms_template_id' => $sms_template->sms_template_id,
                            'phone' => $customer->phone,
                            'msg' =>  $message,
                        ];
                        $response =  $this->sendThroughBulk($data);
                        $response = json_decode($response);

                    }
                }

                $whatsapp_template = WhatsappTemplate::where('type','1')->first();
                if(!empty($whatsapp_template)){
                    $wused = WTemplateUse::where('user_id',auth()->user()->id)->where('template_id',$sms_template->id)->first();
                    $message = $whatsapp_template->description;
                    $message = str_replace("{customer_name}",$customer->name,$message);
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
                                'to' => str_replace("+","", $customer->whatsapp_number),
                                'msg' =>  $message,
                            ];
                            $this->sendThroughCloud($data);
                            }elseif ($whatsapp_setting->type == 2){

                                $data = (object) [
                                    'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                    'to' => str_replace("+","",  $customer->whatsapp_number),
                                    'msg' =>  $message,
                                ];
                                $this->sendThroughVonage($data);


                        }

                }
            }
        }


    }

        Session::flash('success_message', 'Great!Your message not successfully added. But Invoice Created!');
        return redirect()->route("invoices.show",$invoice->id);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $user = Invoice::find($id);
	    return view('admin.invoices.single', ['title' => 'Courier detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = Invoice::findOrFail($request->id);
		return view('admin.statuses.detail', ['title' => 'Status Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Invoice::find($id);
	    return view('admin.statuses.edit', ['title' => 'Edit Status details'])->withUser($user);
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
	    $user = Invoice::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();
	    $user->name = $input['name'];
        $user->color = $input['color'];
        $user->email_subject = $input['email_subject'];
        $user->sms_template = $input['sms_template'];
        $user->email_body = $input['email_body'];
        $user->sort_order = $input['sort_order'];
        if (Auth::user()->role == 2){
            $user->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id =  Auth::user()->parent_id;
        }
        $res = array_key_exists('complete', $input);
        if ($res == false) {
            $user->complete = 0;
        } else {
            $user->complete = 1;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Status successfully updated!');
	    return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $user = Invoice::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Invoice successfully deleted!');
	    return redirect()->route('invoices.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'invoices' => 'required',

		]);
		foreach ($input['invoices'] as $index => $id) {

			$user = Invoice::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Invoices successfully deleted!');
		return redirect()->back();

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
        Mail::send('emails.order', $data, function ($message) use ($data) {
            $message->to($data['user_email'])
                ->from($data['user_email'],$data['from_name'])
                ->subject($data['subject']);
        });

//        Mail::to(  $user->email )->send(new DynamicSMTPMail( $user->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']] ));
        Config::set('mail.mailers.smtp', $backup);
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
