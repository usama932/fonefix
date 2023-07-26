<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsSetting;
use App\Models\WhatsappSetting;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;
use Auth;


class WhatsappSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = WhatsappSetting::where('user_id', Auth::id())->first();
        $templates = WhatsappTemplate::latest()->get();
        if (Auth::user()->role == 3){
            $settings = WhatsappSetting::where('user_id', Auth::user()->parent_id)->first();
        }
        return view('admin.settings.whatsapp', ['title' => 'Sms Setting','settings'=>$settings,'templates'=> $templates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $setting = new WhatsappSetting();
        if ($request->id != 0){
            $setting = WhatsappSetting::findOrFail($request->id);
        }
        $array = json_encode($request->template  ?? '', true);
        $setting->type = $request->type;
        $setting->cloudwhatsapp_api_key = $request->cloudwhatsapp_api_key;
        $setting->whatsapp_vonage_from = $request->whatsapp_vonage_from;
        $setting->user_id = Auth::id();
        $setting->template_id =  $array;
        if (Auth::user()->role == 3){
            $setting->user_id  = Auth::user()->parent_id;
        }
        $setting->save();
        Session::flash('success_message', 'Settings saved successfully!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
