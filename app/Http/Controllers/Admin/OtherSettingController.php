<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;


class OtherSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::where("type", 5)->get()->pluck('value','name');
        $all_columns =array(
            array(
                'name'=>'mail_host',
                'id'=>'mail_host',
                'type'=>'text',
                'label'=>'Mail Host',
                'place_holder'=>'Enter Mail Host',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px'
            ),
            array(
                'name'=>'mail_username',
                'id'=>'mail_username',
                'type'=>'text',
                'label'=>'Mail Username',
                'place_holder'=>'Enter Mail Username',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px'
            ),

            array(
                'name'=>'mail_password',
                'id'=>'mail_password',
                'type'=>'text',
                'label'=>'Mail Password',
                'place_holder'=>'Enter Mail Password',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px'
            ),
            array(
                'name'=>'mail_address',
                'id'=>'mail_address',
                'type'=>'text',
                'label'=>'Mail From Address',
                'place_holder'=>'Enter Mail From Address',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px'
            ),


        );
        return view('admin.settings.other', ['title' => 'Other Setting','settings'=>$settings,
            'all_columns'=>$all_columns]);
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
        $settings = Setting::where("type", 5)->get();

        $data = array();
        if(!empty($request->file())){

            foreach($request->file() as $name=>$file_data){


                if ($request->hasFile($name)) {

                    if ($request->file($name)->isValid()) {

                        $this->validate($request, [
                            $name => 'required|image|mimes:jpeg,png,jpg,svg'
                        ]);

                        $file = $request->file($name);

                        $fileName = $file->getClientOriginalName();

                        $newFileName = rand().$fileName;

                        $destinationPath = public_path('/uploads/');

                        $request->file($name)->move($destinationPath,$newFileName);

                        if (isset($settings[$name])) {
                            if (file_exists(public_path('/uploads/'.$settings[$name]))) {

                                $delete_old_file = public_path('/uploads/'.$settings[$name]);

                                File::delete($delete_old_file);
                            }
                        }


                        $data[$name] = $newFileName;

                    }


                }


            }

        }

//        DB::table('settings')->truncate();
        $delete = Setting::where("type",5)->get();
        foreach($delete as $key => $value){
            $value->delete();
        }

        unset($input['_token']);

        unset($input['_method']);

        if(count($data)>0){
            $input = array_merge($input,$data);
        }
        foreach ($input as $key => $value) {
            $setting = new Setting();

            $setting->name = $key;
            $setting->type = 5;

            $setting->value = $value;

            $setting->save();

        }
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
