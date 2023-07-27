<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Job;
use App\Models\Provinces;
use App\Models\ShopUser;
use App\Models\Status;
use App\Models\User;
use App\Models\UserBrand;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\SmsTempalate;
use App\Models\Invoice;
use App\Models\WhatsappTemplate;
use App\Models\TemplateUse;
use App\Models\WTemplateUse;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Clients';
	    return view('admin.clients.index',compact('title'));
    }


	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'email',
			3 => 'active',
			4 => 'parent_id',
			5 => 'created_at',
			6 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = Auth::id();
        }
        elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = User::where([['is_admin',0],['parent_id',$id]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->count();
        }else{
            $totalData = User::where([['is_admin',0]])->count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
            if ($id){
                $users = User::where([['is_admin',0],['parent_id',$id]])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->select(
                        'users.*'
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = User::where([['is_admin',0],['parent_id',$id]])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->select(
                        'users.*'
                    )
                    ->count();

            }else{
                $users = User::where([['is_admin',0]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = User::where([['is_admin',0]])->count();

            }

		}else{
            $search = $request->input('search.value');

            if ($id){
                $users = User::where([
                    ['is_admin',0],
                    ['name', 'like', "%{$search}%"],
                    ['parent_id',$id]
                ])
                    ->orWhere([
                        ['is_admin',0],
                        ['email', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->orWhere([
                        ['is_admin',0],
                        ['created_at', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->select(
                        'users.*'
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = User::where([
                    ['is_admin',0],
                    ['name', 'like', "%{$search}%"],
                    ['parent_id',$id]
                ])
                    ->orWhere([
                        ['is_admin',0],
                        ['email', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->orWhere([
                        ['is_admin',0],
                        ['created_at', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->join('shop_users', function ($join) use ($id) {
                        $join->on('shop_users.customer_id', '=', 'users.id')
                            ->where('shop_users.user_id', '=', $id);
                    })
                    ->select(
                        'users.*'
                    )
                    ->count();

            }else{
                $users = User::where([
                    ['is_admin',0],
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['is_admin',0],
                        ['email', 'like', "%{$search}%"],
                    ])
                    ->orWhere([
                        ['is_admin',0],
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = User::where([
                    ['is_admin',0],
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['is_admin',0],
                        ['email', 'like', "%{$search}%"],
                    ])
                    ->orWhere([
                        ['is_admin',0],
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->count();

            }

		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('clients.edit',$r->id);
				$jobs_url = route('client.jobs',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="clients[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
				$nestedData['email'] = $r->email;
				if($r->active){
					$nestedData['active'] = '<span class="label label-success label-inline mr-2">Active</span>';
				}else{
					$nestedData['active'] = '<span class="label label-danger label-inline mr-2">Inactive</span>';
				}
				if(!$id){
                    if($r->parent_id){
                        $nestedData['shop'] = $r->shop->name ?? "Not Assign";
                    }else{
                        $nestedData['shop'] = 'Nil';
                    }
                }else{
                    $nestedData['shop'] = Auth::user()->name;
                }


				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
                if(auth()->user()->role == 2)
                {
                    $nestedData['pending'] = $r->pendingInvoices->sum('total').'<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();view_amount('.$r->id.');" title="Pay Amount" href="javascript:void(0)">
                    <i class="icon-1x text-dark-50 flaticon-edit"></i>
                    </a>';
                }

                else{
                    $nestedData['pending'] = $r->pendingInvoices->sum('total');
                }
                $user = Auth::user();
                if($user->role == 1){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                    $history = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                    $history = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->user_view;
                    $edit = $user->permission->user_edit;
                    $del = $user->permission->user_delete;
                    $history = $user->permission->user_history;
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
                $history_link = '<a title="Jobs" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$jobs_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-file"></i>
                                    </a>';
                if(!$history){$history_link = '';}
                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('.$r->id.');\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
				$nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
                                    $edit_link
                                    $history_link
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

	    $title = 'Add New Client';
        if (Auth::user()->role == 2){
            $brands = Brand::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        } else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $countries = Auth::user()->countries;

        $provinces = Provinces::orderBy('name', 'asc')->pluck('name','id')->toArray();
        return view('admin.clients.create',["title"=> $title, "countries"=>$countries, "provinces"=>$provinces, "brands"=>$brands]);
    }


    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            $input = $request->all();
            $user = User::where("email", $request->email)->first();
            if (!$user){
                $user = new User();
            }
            if (Auth::user()->role == 2) {
                $id = Auth::user()->id;
            }elseif (Auth::user()->role == 3){
                $id = Auth::user()->parent_id;
            }else{
                $id = Auth::user()->id;
            }
            $phone_check = User::where([
                ['is_admin',0],
                ['phone',$request->phone],
                ])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )->first();
            if ($phone_check){
                Session::flash('error_message', 'Sorry! This Mobile Number already registered with '.$phone_check->name.'');
                return redirect()->back();
            }
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->country_id = $input['country'];
            $user->province_id = $input['province'];
            $user->city = $input['city'];
            $user->postal_code = $input['postal_code'];
            $user->line1 = $input['line1'];
            $user->line2 = $input['line2'];
            $user->phone = $input['phone'];
            $user->alternative_phone = $input['alternative_phone'];
            $user->location = $input['location'];
            $res = array_key_exists('active', $input);
            if ($res == false) {
                $user->active = 0;
                $user->disable_reason = $request->disable_reason;
            } else {
                $user->active = 1;

            }
            $user->password = bcrypt($input['password']);
            if (Auth::user()->role == 2){
                $user->parent_id = Auth::id();
            }
            $user->save();

            if (Auth::user()->role == 2) {
                $shop_user = ShopUser::where([["user_id", Auth::user()->id],["customer_id", $user->id]])->first();
                if (!$shop_user){
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = Auth::id();
                    $shop_user->save();
                }
            }elseif (Auth::user()->role == 3){
                $shop_user = ShopUser::where([["user_id", Auth::user()->parent_id],["customer_id", $user->id]])->first();
                if (!$shop_user){
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = Auth::user()->parent_id;
                    $shop_user->save();
                }
            }

                for ($x = 0; $x < $request->dev_count ; $x++) {
                    if (array_key_exists("device$x", $input)){
                        $user_brand = new UserBrand();
                        $user_brand->user_id = $user->id;
                        $user_brand->brand_id = $input["brand$x"];
                        $user_brand->device = $input["device$x"];
                        $user_brand->save();
                        $res = array_key_exists("model$x", $input);
                        if ($res) {
                            foreach ($input["model$x"] as $item) {
                                $user_device = new UserDevice();
                                $user_device->user_id = $user->id;
                                $user_device->user_brand_id = $user_brand->id;
                                $user_device->brand_id = $user_brand->brand_id;
                                $user_device->device_id = $item;
                                $user_device->save();
                            }
                        }
                    }

                }


                $sms_template = SmsTempalate::where('type','2')->first();

                if(!empty($sms_template)){
                    $used = TemplateUse::where('user_id',auth()->user()->id)
                                        ->where('template_id',$sms_template->id)
                                        ->where('used',1)
                                        ->first();

                    $message = $sms_template->description;
                    $message = str_replace("{customer_name}",$input['name'],$message);
                    // $message = str_replace("{enquired_products}",$request->device0,$message);

                    $id = $sms_template->id;
                    $sms_setting ='';
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




                    if(!empty($sms_setting)) {
                        $sms = User::where('id', 1)->with('smsSetting', function($q) use ($id){
                            $q->where('template_id', 'LIKE', '%'. $id .'%');
                        })->first();
                        $sms_setting = $sms->smsSetting;
                    }




                    if ($sms_setting) {

                        if ($sms_setting->type == 2){

                            $data = (object) [
                                'phone' => $input['phone'],
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
                                'phone' =>  $input['phone'],
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
                                'phone' =>  $input['phone'],
                                'msg' =>  $message,
                            ];
                            $response =  $this->sendThroughBulk($data);
                            $response = json_decode($response);

                        }
                    }
                }

                // $whatsapp_template = WhatsappTemplate::where('type','2')->where('shared',1)->first();
                // if(!empty($whatsapp_template)){
                //     $message = $whatsapp_template->description;
                //     $message = str_replace("{customer_name}",$input['name'],$message);
                //     $id = $whatsapp_template->id;

                //     $whatsapp =  User::where('id',auth()->user()->id)->with('whatsappSetting', function($q) use ($id){
                //                     $q->where('template_id', 'LIKE', '%'. $id .'%');
                //                 })->first();
                //     $whatsapp_setting = $whatsapp->whatsappSetting;

                //     if (!$whatsapp_setting){
                //         $whatsapp =User::where('id',1)->with('whatsappSetting', function($q) use ($id){
                //             $q->where('template_id', 'LIKE', '%'. $id .'%');
                //         })->first();
                //         $whatsapp_setting = $whatsapp->whatsappSetting;
                //     }

                //     if ($whatsapp_setting){

                //         if ($whatsapp_setting->type == 1){
                //             $data = (object) [
                //                 'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                //                 'to' => str_replace("+","", $input['contact_number']),
                //                 'msg' =>  $message,
                //             ];
                //             $this->sendThroughCloud($data);
                //             }elseif ($whatsapp_setting->type == 2){

                //                 $data = (object) [
                //                     'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                //                     'to' => str_replace("+","", $input['contact_number']),
                //                     'msg' =>  $message,
                //                 ];
                //                 $this->sendThroughVonage($data);


                //         }

                // }


            Session::flash('success_message', 'Great! Client has been saved successfully!');
            return redirect()->back();

        }
        catch(Exception $e){
            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }

    }

    public function phoneCheck(Request $request){
        if (Auth::user()->role == 2) {
            $id = Auth::user()->id;
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = Auth::user()->id;
        }
        $phone_check = User::where([
            ['is_admin',0],
            ['phone',$request->phone],
        ])
            ->join('shop_users', function ($join) use ($id) {
                $join->on('shop_users.customer_id', '=', 'users.id')
                    ->where('shop_users.user_id', '=', $id);
            })
            ->select(
                'users.*'
            )->first();
        if ($phone_check){
            return 1;
        }else{
            return 0;
        }
    }
    public function popupAdd(Request $request)
    {
	    $this->validate($request, [
		    'name' => 'required|max:255',
		    'email' => 'required|email',
//		    'password' => 'required|min:6',
	    ]);

	    $input = $request->all();
        $user = User::where("email", $request->email)->first();
        if (!$user){
            $user = new User();
        }
        if (Auth::user()->role == 2) {
            $id = Auth::user()->id;
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = Auth::user()->id;
        }
        $phone_check = User::where([
            ['is_admin',0],
            ['phone',$request->phone],
        ])
            ->join('shop_users', function ($join) use ($id) {
                $join->on('shop_users.customer_id', '=', 'users.id')
                    ->where('shop_users.user_id', '=', $id);
            })
            ->select(
                'users.*'
            )->first();
        if ($phone_check){
            Session::flash('error_message', 'Sorry! This Mobile Number already registered with '.$phone_check->name.'');
            return redirect()->back();
        }
	    $user->name = $input['name'];
	    $user->email = $input['email'];
	    $user->phone = $input['phone'];
	    $user->password = bcrypt("12345607");
        $user->parent_id = Auth::id();
	    $user->save();
        if (Auth::user()->role == 2) {
            $shop_user = ShopUser::where([["user_id", Auth::user()->id],["customer_id", $user->id]])->first();
            if (!$shop_user){
                $shop_user = new ShopUser();
                $shop_user->customer_id = $user->id;
                $shop_user->user_id = Auth::id();
                $shop_user->save();
            }
        }elseif (Auth::user()->role == 3){
            $shop_user = ShopUser::where([["user_id", Auth::user()->parent_id],["customer_id", $user->id]])->first();
            if (!$shop_user){
                $shop_user = new ShopUser();
                $shop_user->customer_id = $user->id;
                $shop_user->user_id = Auth::user()->parent_id;
                $shop_user->save();
            }
        }
        $sms_template = SmsTempalate::where('type','2')->first();
        if(!empty($sms_template)){
            $used = TemplateUse::where('user_id',auth()->user()->id)
                                ->where('template_id',$sms_template->id)
                                ->where('used',1)
                                ->first();
            $message = $sms_template->description;
            $message = str_replace("{customer_name}",$input['name'],$message);
            // $message = str_replace("{enquired_products}",$request->device0,$message);

            $id = $sms_template->id;
            $sms_setting ='';
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



            if(!empty($sms_setting)) {
                $sms = User::where('id', 1)->with('smsSetting', function($q) use ($id){
                    $q->where('template_id', 'LIKE', '%'. $id .'%');
                })->first();
                $sms_setting = $sms->smsSetting;
            }




            if ($sms_setting) {

                if ($sms_setting->type == 2){

                    $data = (object) [
                        'phone' => $input['phone'],
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
                        'phone' =>  $input['phone'],
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
                        'phone' =>  $input['phone'],
                        'msg' =>  $message,
                    ];
                    $response =  $this->sendThroughBulk($data);
                    $response = json_decode($response);

                }
            }
        }

        // $whatsapp_template = WhatsappTemplate::where('type','2')->where('shared',1)->first();
        // if(!empty($whatsapp_template)){
        //     $message = $whatsapp_template->description;
        //     $message = str_replace("{customer_name}",$input['name'],$message);
        //     $id = $whatsapp_template->id;

        //     $whatsapp =  User::where('id',auth()->user()->id)->with('whatsappSetting', function($q) use ($id){
        //                     $q->where('template_id', 'LIKE', '%'. $id .'%');
        //                 })->first();
        //     $whatsapp_setting = $whatsapp->whatsappSetting;

        //     if (!$whatsapp_setting){
        //         $whatsapp =User::where('id',1)->with('whatsappSetting', function($q) use ($id){
        //             $q->where('template_id', 'LIKE', '%'. $id .'%');
        //         })->first();
        //         $whatsapp_setting = $whatsapp->whatsappSetting;
        //     }

        //     if ($whatsapp_setting){

        //         if ($whatsapp_setting->type == 1){
        //             $data = (object) [
        //                 'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
        //                 'to' => str_replace("+","", $input['contact_number']),
        //                 'msg' =>  $message,
        //             ];
        //             $this->sendThroughCloud($data);
        //             }elseif ($whatsapp_setting->type == 2){

        //                 $data = (object) [
        //                     'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
        //                     'to' => str_replace("+","", $input['contact_number']),
        //                     'msg' =>  $message,
        //                 ];
        //                 $this->sendThroughVonage($data);


        //         }

        // }

	    Session::flash('success_message', 'Great! Customer has been saved successfully!');

	    return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        return view('admin.clients.import', ['title' => 'Client Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/client.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'client-sample.xlsx', $headers);
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

                    $user = User::where([["email",$line['Email']]])->first();
                    if (!$user){
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
                    $shop_user = ShopUser::where([["user_id",$user_id],["customer_id", $user->id]])->first();
                    if (!$shop_user){
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
        $data = User::join('shop_users', function ($join) use ($user_id) {
                $join->on('shop_users.customer_id', '=', 'users.id')
                    ->where('shop_users.user_id', '=', $user_id);
            })
            ->select(
                'users.*'
            )
            ->get();
        return Response::download((new FastExcel($data))->export('clients.csv', function ($pass) {

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

        }));
    }
    public function show($id)
    {
	    $user = User::find($id);
	    return view('admin.clients.single', ['title' => 'Client detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = User::findOrFail($request->id);


		return view('admin.clients.detail', ['title' => 'Client Detail', 'user' => $user]);
	}
	public function getUserCards(Request $request)
	{
		$user = User::findOrFail($request->id);
		return view('admin.clients.cards', ['title' => 'Client Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = User::find($id);
        if (Auth::user()->role == 2){
            $brands = Brand::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $countries = Auth::user()->countries;
        $provinces = Provinces::orderBy('name', 'asc')->pluck('name','id')->toArray();
        return view('admin.clients.edit', ['title' => 'Edit Client details',"countries" => $countries,"brands" => $brands, 'provinces' => $provinces])->withUser($user);
    }
    public function clientJobs($id)
    {
        if (Auth::user()->role == 2){
            $jobs = Job::where([["customer_id",$id],["user_id",Auth::user()->id]])->get();
        }elseif (Auth::user()->role == 3){
            $jobs = Job::where([["customer_id",$id],["user_id",Auth::user()->parent_id]])->get();
        }else{
            $jobs = Job::where("customer_id",$id)->get();

        }
        return view('admin.clients.jobs', ['title' => 'Client Jobs',"jobs" => $jobs]);
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
        try{
            $user = User::find($id);
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|unique:users,email,'.$user->id,
            ]);
            $input = $request->all();

            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->country_id = $input['country'];
            $user->province_id = $input['province'];
            $user->city = $input['city'];
            $user->postal_code = $input['postal_code'];
            $user->line1 = $input['line1'];
            $user->line2 = $input['line2'];
            $user->phone = $input['phone'];
            $user->alternative_phone = $input['alternative_phone'];
            $user->location = $input['location'];
            $res = array_key_exists('active', $input);
            if ($res == false) {
                $user->active = 0;
                $user->disable_reason = $request->disable_reason;
            } else {
                $user->active = 1;

            }
            if(!empty($input['password'])) {
                $user->password = bcrypt($input['password']);
            }
            if (Auth::user()->role == 2){
                $user->parent_id = Auth::id();
            }
            $user->save();
            if (Auth::user()->role == 2) {
                $shop_user = ShopUser::where([["user_id", Auth::user()->id],["customer_id", $user->id]])->first();
                if (!$shop_user){
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = Auth::id();
                    $shop_user->save();
                }
            }elseif (Auth::user()->role == 3){
                $shop_user = ShopUser::where([["user_id", Auth::user()->parent_id],["customer_id", $user->id]])->first();
                if (!$shop_user){
                    $shop_user = new ShopUser();
                    $shop_user->customer_id = $user->id;
                    $shop_user->user_id = Auth::user()->parent_id;
                    $shop_user->save();
                }
            }
            for ($x = 0; $x < $request->dev_count ; $x++) {
                if (array_key_exists("device$x", $input)){
                    $user_brand = new UserBrand();
                    $user_brand->user_id = $user->id;
                    $user_brand->brand_id = $input["brand$x"];
                    $user_brand->device = $input["device$x"];
                    $user_brand->save();
                    $res = array_key_exists("model$x", $input);
                    if ($res) {
                        foreach ($input["model$x"] as $item) {
                            $user_device = new UserDevice();
                            $user_device->user_id = $user->id;
                            $user_device->user_brand_id = $user_brand->id;
                            $user_device->brand_id = $user_brand->brand_id;
                            $user_device->device_id = $item;
                            $user_device->save();
                        }
                    }
                }

            }
            Session::flash('success_message', 'Great! Client successfully updated!');
            return redirect()->back();
        }
            catch(Exception $e){
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
    public function destroy($id)
    {
	    $user = User::find($id);
        if (Auth::user()->role == 2){
            $user_shop = ShopUser::where([["customer_id", $user->id]], ["user_id", Auth::id()])->first();
            if ($user_shop){
                $user_shop->delete();
            }
        }else{
            $user->delete();

        }

        Session::flash('success_message', 'User successfully deleted!');
	    return redirect()->route('clients.index');

    }
    public function deleteBrand($id)
    {
	    $user = UserBrand::find($id);
        $user->delete();
        Session::flash('success_message', 'Brand successfully deleted!');
	    return redirect()->back();
    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'clients' => 'required',

		]);
		foreach ($input['clients'] as $index => $id) {

			$user = User::find($id);
			if($user->is_admin == 0){
				$user->delete();
			}

		}
		Session::flash('success_message', 'clietns successfully deleted!');
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
        dd($response);
        curl_close($curl);
        return $response;
    }
    public function clientamount(Request $request){
        $user = User::findOrFail($request->id);


		return view('admin.clients.pay_amount', ['title' => 'payment Detail', 'user' => $user]);
    }
    public function payamount(Request $request){
       // dd($request->all());
            $invoices =  Invoice::where('customer_id',$request->user_id)->where("payment_method",3)->get();
            $paid = $request->pay_amount;
            if($request->real_amount >= $request->pay_amount){
                foreach($invoices as $invoice){

                    if($paid >=  $invoice->total){

                        $inv = Invoice::where('id',$invoice->id)->update([
                            'payment_method' => $request->card,
                        ]);
                        $paid = $paid - $invoice->total;
                        $customer = User::find($request->user);

                        $input = $request->all();
                        $user = Auth::user();
                        $invoice = new Invoice();
                        $invoice->total = $invoice->total;
                        $invoice->discount = 0;
                        if (Auth::user()->role == 1){
                            $invoice->user_id =  Auth::id();
                        }
                        if (Auth::user()->role == 2){
                            $invoice->user_id =  Auth::id();
                        }
                        if (Auth::user()->role == 3){
                            $invoice->user_id =  Auth::user()->parent_id;
                        }
                        $invoice->discount_type = 0 ;
                        $invoice->payment_method = $request->card;
                        $invoice->customer_id = $request->user_id;
                        $invoice->number = date('YmdHis');
                        $invoice->save();
                    }
                    else{

                        $inv = Invoice::where('id',$invoice->id)->update([

                                'total' => $invoice->total - $paid,
                        ]);
                        $input = $request->all();
                        $user = Auth::user();
                        $invoice = new Invoice();
                        $invoice->total =  $paid - $invoice->total;
                        $invoice->discount = 0;
                        if (Auth::user()->role == 1){
                            $invoice->user_id =  Auth::id();
                        }
                        if (Auth::user()->role == 2){
                            $invoice->user_id =  Auth::id();
                        }
                        if (Auth::user()->role == 3){
                            $invoice->user_id =  Auth::user()->parent_id;
                        }
                        $invoice->discount_type = 0 ;
                        $invoice->payment_method = $request->card;
                        $invoice->customer_id = $request->user_id;
                        $invoice->number = date('YmdHis');
                        $invoice->save();
                    }
                }
                Session::flash('success_message', 'Great!Amount Paid!');
                return redirect()->back();
            }else{
                Session::flash('success_message', 'Enter VAlid AMount!');
                return redirect()->back();
            }



    }
}
