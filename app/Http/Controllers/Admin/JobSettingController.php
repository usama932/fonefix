<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobSetting;
use App\Models\Setting;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;
use Auth;


class JobSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = JobSetting::where('user_id', Auth::id())->first();
        $statuses = Status::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        if (Auth::user()->role == 3){
            $settings = JobSetting::where('user_id', Auth::user()->parent_id)->first();
            $statuses = Status::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }
        return view('admin.settings.job', ['title' => 'Job Sheet Setting','settings'=>$settings,'statuses'=>$statuses]);
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
        $setting = new JobSetting();
        if ($request->id != 0){
            $setting = JobSetting::findOrFail($request->id);
        }
        $setting->status_id = $request->status;
        $setting->jos_sheet_prefix = $request->jos_sheet_prefix;
        $setting->product_configuration = $request->product_configuration;
        $setting->problem_by_customer = $request->problem_by_customer;
        $setting->condition_of_product = $request->condition_of_product;
        $setting->terms_conditions = $request->terms_conditions;
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
