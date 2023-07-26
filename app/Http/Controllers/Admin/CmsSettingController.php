<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\CmsSetting;
use App\Models\Job;
use App\Models\JobSetting;
use App\Models\Setting;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;
use Auth;
use App\Models\Slider;


class CmsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = CmsSetting::where('user_id', Auth::id())->first();
        $sliders = Slider::where('user_id',auth()->user()->id)->take(5)->latest()->get();
        if (Auth::user()->role == 3){
            $settings = CmsSetting::where('user_id', Auth::user()->parent_id)->first();
        }
        return view('admin.settings.cms', ['title' => 'Cms Setting','settings'=>$settings,'sliders'=>$sliders]);
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
        $setting = new CmsSetting();
        if ($request->id != 0){
            $setting = CmsSetting::findOrFail($request->id);
        }
        $setting->slider_text = $request->slider_text;
        $setting->feature_text1 = $request->feature_text1;
        $setting->feature_text2 = $request->feature_text2;
        $setting->feature_text3 = $request->feature_text3;
        $setting->feature_text4 = $request->feature_text4;
        $setting->about_video = $request->about_video;
        $setting->about_title = $request->about_title;
        $setting->description = $request->description;
        $setting->guarantee = $request->guarantee;
        $setting->quality = $request->quality;
        $setting->repairs = $request->repairs;
        $setting->service_text1 = $request->service_text1;
        $setting->service_text2 = $request->service_text2;
        $setting->service_text3 = $request->service_text3;
        $setting->project_text1 = $request->project_text1;
        $setting->project_text2 = $request->project_text2;
        $setting->project_text3 = $request->project_text3;
        if ($request->hasFile('slider_image')) {
            if ($request->file('slider_image')->isValid()) {
                $this->validate($request, [
                    'slider_image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('slider_image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('slider_image');
                $image = rand().$image;
                $request->file('slider_image')->move($destinationPath, $image);
                $setting->slider_image = $image;

            }
        }
        if ($request->hasFile('feature_image1')) {
            if ($request->file('feature_image1')->isValid()) {
                $this->validate($request, [
                    'feature_image1' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('feature_image1');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('feature_image1');
                $image = rand().$image;
                $request->file('feature_image1')->move($destinationPath, $image);
                $setting->feature_image1 = $image;

            }
        }
        if ($request->hasFile('feature_image2')) {
            if ($request->file('feature_image2')->isValid()) {
                $this->validate($request, [
                    'feature_image2' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('feature_image2');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('feature_image2');
                $image = rand().$image;
                $request->file('feature_image2')->move($destinationPath, $image);
                $setting->feature_image2 = $image;

            }
        }
        if ($request->hasFile('feature_image3')) {
            if ($request->file('feature_image3')->isValid()) {
                $this->validate($request, [
                    'feature_image3' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('feature_image3');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('feature_image3');
                $image = rand().$image;
                $request->file('feature_image3')->move($destinationPath, $image);
                $setting->feature_image3 = $image;

            }
        }
        if ($request->hasFile('feature_image4')) {
            if ($request->file('feature_image4')->isValid()) {
                $this->validate($request, [
                    'feature_image4' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('feature_image4');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('feature_image4');
                $image = rand().$image;
                $request->file('feature_image4')->move($destinationPath, $image);
                $setting->feature_image4 = $image;

            }
        }
        if ($request->hasFile('project_image1')) {
            if ($request->file('project_image1')->isValid()) {
                $this->validate($request, [
                    'project_image1' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('project_image1');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('project_image1');
                $image = rand().$image;
                $request->file('project_image1')->move($destinationPath, $image);
                $setting->project_image1 = $image;

            }
        }
        if ($request->hasFile('project_image2')) {
            if ($request->file('project_image2')->isValid()) {
                $this->validate($request, [
                    'project_image2' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('project_image2');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('project_image2');
                $image = rand().$image;
                $request->file('project_image2')->move($destinationPath, $image);
                $setting->project_image2 = $image;

            }
        }
        if ($request->hasFile('project_image3')) {
            if ($request->file('project_image3')->isValid()) {
                $this->validate($request, [
                    'project_image3' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('project_image3');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('project_image3');
                $image = rand().$image;
                $request->file('project_image3')->move($destinationPath, $image);
                $setting->project_image3 = $image;

            }
        }
        if ($request->hasFile('service_image1')) {
            if ($request->file('service_image1')->isValid()) {
                $this->validate($request, [
                    'service_image1' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('service_image1');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('service_image1');
                $image = rand().$image;
                $request->file('service_image1')->move($destinationPath, $image);
                $setting->service_image1 = $image;

            }
        }
        if ($request->hasFile('service_image2')) {
            if ($request->file('service_image2')->isValid()) {
                $this->validate($request, [
                    'service_image2' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('service_image2');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('service_image2');
                $image = rand().$image;
                $request->file('service_image2')->move($destinationPath, $image);
                $setting->service_image2 = $image;

            }
        }
        if ($request->hasFile('service_image3')) {
            if ($request->file('service_image3')->isValid()) {
                $this->validate($request, [
                    'service_image3' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('service_image3');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('service_image3');
                $image = rand().$image;
                $request->file('service_image3')->move($destinationPath, $image);
                $setting->service_image3 = $image;

            }
        }
        if ($request->hasFile('about_image')) {
            if ($request->file('about_image')->isValid()) {
                $this->validate($request, [
                    'about_image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('about_image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('about_image');
                $image = rand().$image;
                $request->file('about_image')->move($destinationPath, $image);
                $setting->about_image = $image;

            }
        }

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
