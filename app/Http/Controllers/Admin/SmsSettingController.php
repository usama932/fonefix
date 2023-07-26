<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;
use Auth;
use App\Models\SmsTempalate;


class SmsSettingController extends Controller
{

    public function index()
    {
        $settings = SmsSetting::where('user_id', Auth::id())->first();
        $templates = SmsTempalate::latest()->get();
        if (Auth::user()->role == 3){
            $settings = SmsSetting::where('user_id', Auth::user()->parent_id)->first();
        }
        return view('admin.settings.sms', ['title' => 'Whatsapp Setting','settings'=>$settings,'templates'=>$templates]);
    }
    public function getsms(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'type',
			2 => 'template',
			4 => 'created_at',
			5 => 'action'
		);

		$totalData = SmsSetting::with('template')->where('user_id', auth()->user()->id)->count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
			$settings =SmsSetting::with('template')->where('user_id', auth()->user()->id)->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = SmsSetting::with('template')->where('user_id', auth()->user()->id)->count();
		}else{
			$search = $request->input('search.value');
			$settings = SmsSetting::with('template')->where('user_id', auth()->user()->id)
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered =SmsSetting::with('template')->where('user_id', auth()->user()->id)
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}


		$data = array();

		if($settings){
			foreach($settings as $r){
				$edit_url = route('sms-setting.edit',$r->id);

				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="settings[]" value="'.$r->id.'"><span></span></label></td>';
				if($r->type == 1){
                    $type ='Pearl SMS';
                }elseif($r->type == 2){
                    $type = 'Twillo';
                }elseif($r->type == 3){
                    $type = 'Bulk SMS';
                }
                else{
                    $type ='' ;
                }
                $nestedData['type'] = $type;
				$nestedData['template_id'] = $r->template->name ?? '';


				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>

                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                    </a>
                                </td>
                                </div>
                            ';
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
    public function smsDetail(Request $request)
    {
        $user = SmsSetting::with('template')->findOrFail($request->id);


		return view('admin.settings.show_sms', ['title' => 'Setting Detail', 'user' => $user]);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $setting = new SmsSetting();
        if ($request->id != 0){
            $setting = SmsSetting::findOrFail($request->id);
        }
        $array = json_encode($request->template  ?? '', true);

        $setting->type = $request->type;
        $setting->pearlsms_api_key = $request->pearlsms_api_key;
        $setting->pearlsms_sender = $request->pearlsms_sender;
        $setting->pearlsms_username = $request->pearlsms_username;
        $setting->pearlsms_header = $request->pearlsms_header;
        $setting->pearlsms_footer = $request->pearlsms_footer;
        $setting->twilio_number = $request->twilio_number;
        $setting->twilio_auth_token = $request->twilio_auth_token;
        $setting->twilio_account_sid = $request->twilio_account_sid;
        $setting->bulksms_apikey = $request->bulksms_apikey;
        $setting->bulksms_sendername = $request->bulksms_sendername;
        $setting->bulksms_username = $request->bulksms_username;
        $setting->template_id =  $array;
        $setting->user_id = Auth::id();
        if (Auth::user()->role == 3){
            $setting->user_id  = Auth::user()->parent_id;
        }
        $setting->save();
        Session::flash('success_message', 'Settings saved successfully!');

        return redirect()->back();
    }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $settings = SmsSetting::where('id',$id)->first();
        $templates = SmsTempalate::latest()->get();

        return view('admin.settings.edit_sms', ['title' => 'Sms Setting','settings'=>$settings,'templates'=>$templates]);
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


        $setting = SmsSetting::findOrFail($id);

        $setting->type = $request->type;
        $setting->pearlsms_api_key = $request->pearlsms_api_key;
        $setting->pearlsms_sender = $request->pearlsms_sender;
        $setting->pearlsms_username = $request->pearlsms_username;
        $setting->pearlsms_header = $request->pearlsms_header;
        $setting->pearlsms_footer = $request->pearlsms_footer;
        $setting->twilio_number = $request->twilio_number;
        $setting->twilio_auth_token = $request->twilio_auth_token;
        $setting->twilio_account_sid = $request->twilio_account_sid;
        $setting->bulksms_apikey = $request->bulksms_apikey;
        $setting->bulksms_sendername = $request->bulksms_sendername;
        $setting->bulksms_username = $request->bulksms_username;
        $setting->template_id = $request->template ?? " ";
        $setting->user_id = Auth::id();
        if (Auth::user()->role == 3){
            $setting->user_id  = Auth::user()->parent_id;
        }
        $setting->save();
        Session::flash('success_message', 'Settings saved successfully!');

        return redirect()->route('sms-setting.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = SmsSetting::find($id);
        $user->delete();
        Session::flash('success_message', 'SmsSetting successfully deleted!');
     return redirect()->back();
    }
}
