<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'NewsWatch Client Dashboard';
	    return view('client.dashboard.index',compact('title'));

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
        //
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
    public function edit()
    {
	    $user = Auth::user();
	    return view('client.settings.edit', ['title' => 'Edit Profile','user'=>$user]);
    }


    public function update(Request $request)
    {
	    $admin = Auth::user();
	    $this->validate($request, [
		    'name' => 'required|max:255',
		    'email' => 'required|unique:users,email,'.$admin->id,
	    ]);
	    $input = $request->all();
	    if (empty($input['password'])) {
		    $input['password'] = $admin->password;
	    } else {
		    $input['password'] = bcrypt($input['password']);
	    }
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');

                $imagePath = public_path('/uploads/'.$admin->image);

                if($admin->image != '') {
                    if (File::exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                //$extension = $file->getClientOriginalExtension('logo');
                $thumbnail = $file->getClientOriginalName('image');
                $thumbnail = rand() . $thumbnail;
                $request->file('image')->move($destinationPath, $thumbnail);
                $admin->image = $thumbnail;
            }
        }
	    $admin->fill($input)->save();
	    Session::flash('success_message', 'Great! Client successfully updated!');
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
        //
    }
}
