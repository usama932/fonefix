<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Job;
use App\Models\User;
use App\Models\Status;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = User::where('is_admin',0)
                        ->where('parent_id',auth()->user()->id)->latest()->take(5)->get();
        $works   = Job::where('user_id',auth()->user()->id)->orWhere('customer_id',auth()->user()->id)->take(5)->latest()->get();

        $informations = User::where('id',auth()->user()->id)
                            ->where('role',2)
                            ->with(['images'=> function ($query) {
            $query->where('user_id', auth()->user()->id);
        }])->first();

        $title = 'Admin Dashboard';
        $statuses =  Status::whereHas('used', function ($query) {
            $query->where('used', '1');
           })->orwhere('user_id',Auth::id())->orderBy('name', 'asc')->latest()->get();

        return view('admin.dashboard.index',compact('title','clients','works','informations','statuses'));
    }

    public function create()
    {
        //
    }


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
    public function edit(){
        $user = Auth::user();
        return view('admin.settings.edit', ['title' => 'Edit Admin Profile','user'=>$user]);
    }


    public function update(Request $request)
    {
        $admin = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,'.$admin->id,
            'image' => 'required',
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
        Session::flash('success_message', 'Great! admin successfully updated!');
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
