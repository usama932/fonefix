<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
// use App\Models\EmailTemplate;
use File;
use Auth;


class MailSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = MailSetting::where('user_id', Auth::id())->first();
        // $templates = EmailTemplate::latest()->get();
        if (Auth::user()->role == 3){
            $settings = MailSetting::where('user_id', Auth::user()->parent_id)->first();
        }
        return view('admin.settings.mail', ['title' => 'Mail Setting','settings'=>$settings]);
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
        $setting = new MailSetting();
        if ($request->id != 0){
            $setting = MailSetting::findOrFail($request->id);
        }
        // $array = json_encode($request->template  ?? '', true);
        $setting->type = $request->type;
        $setting->mailchimp_apikey = $request->mailchimp_apikey;
        $setting->smtp_host = $request->smtp_host;
        $setting->smtp_port = $request->smtp_port;
        $setting->smtp_username = $request->smtp_username;
        $setting->smtp_password = $request->smtp_password;
        $setting->smtp_encryption = $request->smtp_encryption;
        $setting->from_email = $request->from_email;
        $setting->from_name = $request->from_name;
        // $setting->template_id =  $array;
        $setting->user_id = Auth::id();
        if (Auth::user()->role == 3){
            $setting->user_id = Auth::user()->parent_id;
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
