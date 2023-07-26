<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Staffs';
	    return view('admin.staffs.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'email',
			3 => 'active',
			4 => 'role_id',
			5 => 'created_at',
			6 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = Auth::id();
        } elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = User::where([['is_admin',1],['role',3],['parent_id',$id]])->count();

        }else{
            $totalData = User::where([['is_admin',1],['role',3]])->count();

        }
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if ($id){
                $users = User::where([['is_admin',1],['role',3],['parent_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = User::where([['is_admin',1],['role',3],['parent_id',$id]])->count();
            }else{
                $users = User::where([['is_admin',1],['role',3]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = User::where([['is_admin',1],['role',3]])->count();
            }

		}else{
			$search = $request->input('search.value');
			if ($id){
                $users = User::where([
                    ['is_admin',1],
                    ['role',3],
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],

                        ['email', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['created_at', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = User::where([
                    ['is_admin',1],
                    ['role',3],
                    ['name', 'like', "%{$search}%"],
                    ['parent_id',$id]
                ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['email', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['created_at', 'like', "%{$search}%"],
                        ['parent_id',$id]
                    ])
                    ->count();
            }else{
                $users = User::where([
                    ['is_admin',1],
                    ['role',3],
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['email', 'like', "%{$search}%"],
                    ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = User::where([
                    ['is_admin',1],
                    ['role',3],
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['email', 'like', "%{$search}%"],
                    ])
                    ->orWhere([
                        ['is_admin',1],
                        ['role',3],
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->count();
            }

		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('staffs.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="clients[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
				$nestedData['email'] = $r->email;
				if($r->active){
					$nestedData['active'] = '<span class="label label-success label-inline mr-2">Active</span>';
				}else{
					$nestedData['active'] = '<span class="label label-danger label-inline mr-2">Inactive</span>';
				}
				if($r->role_id){
					$nestedData['role'] = '<span class="label label-success label-inline mr-2">'. $r->permission->name .'</span>';
				}else{
					$nestedData['role'] = '<span class="label label-danger label-inline mr-2">No Role</span>';
				}

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
    public function create()
    {
	    $title = 'Add New Staff';
	    if (Auth::user()->role == 2){
            $roles = Role::where("user_id", Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $roles = Role::where("user_id", Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $roles = Role::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
	    return view('admin.staffs.create',["title" => $title, "roles" => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
	    $this->validate($request, [
		    'name' => 'required|max:255',
		    'email' => 'required|unique:users,email',
		    'password' => 'required|min:6',
	    ]);

	    $input = $request->all();
	    $user = new User();
	    $user->name = $input['name'];
	    $user->email = $input['email'];
	    $user->role = 3;
	    $user->role_id = $input['role'];
	    $user->is_admin = 1;
	    $res = array_key_exists('active', $input);
	    if ($res == false) {
		    $user->active = 0;
	    } else {
		    $user->active = 1;

	    }
        if (Auth::user()->role == 2){
            $user->parent_id = Auth::user()->id;
        }
        if (Auth::user()->role == 3){
            $user->parent_id = Auth::user()->parent_id;
        }
	    $user->password = bcrypt($input['password']);
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('image');
                $image = rand().$image;
                $request->file('image')->move($destinationPath, $image);
                $user->image = $image;

            }
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Staff has been saved successfully!');
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
	    $user = User::find($id);
	    return view('admin.staffs.single', ['title' => 'Staff detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = User::findOrFail($request->id);


		return view('admin.staffs.detail', ['title' => 'Staff Detail', 'user' => $user]);
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
            $roles = Role::where("user_id", Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $roles = Role::where("user_id", Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $roles = Role::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
	    return view('admin.staffs.edit', ['title' => 'Edit Staff details','roles' => $roles])->withUser($user);
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
	    $user = User::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
		    'email' => 'required|unique:users,email,'.$user->id,
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
	    $user->email = $input['email'];
        $user->role = 3;
        $user->role_id = $input['role'];
        $user->is_admin = 1;

        $res = array_key_exists('active', $input);
	    if ($res == false) {
		    $user->active = 0;
	    } else {
		    $user->active = 1;

	    }
        if (Auth::user()->role == 2){
            $user->parent_id = Auth::user()->id;
        }
        if (Auth::user()->role == 3){
            $user->parent_id = Auth::user()->parent_id;
        }
	    if(!empty($input['password'])) {
		    $user->password = bcrypt($input['password']);
	    }

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('image');
                $image = rand().$image;
                $request->file('image')->move($destinationPath, $image);
                $user->image = $image;

            }
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Staff successfully updated!');
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
	    $user = User::find($id);

		    $user->delete();
		    Session::flash('success_message', 'Staff successfully deleted!');

	    return redirect()->route('staffs.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'staffs' => 'required',

		]);
		foreach ($input['staffs'] as $staffindex => $id) {

			$user = User::find($id);
            $user->delete();

		}
		Session::flash('success_message', 'staffs successfully deleted!');
		return redirect()->back();

	}
}
