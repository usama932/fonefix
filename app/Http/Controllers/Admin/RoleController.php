<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Roles';
	    return view('admin.roles.index',compact('title'));
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
			3 => 'created_at',
			4 => 'action'
		);
        if  (Auth::user()->role == 2){
            $id = Auth::id();
        } elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Role::where([['user_id',$id]])->count();
        }else{
            $totalData = Role::count();
        }


		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
            if ($id){
                $users = Role::where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Role::where([['user_id',$id]])->count();
            }else{
                $users = Role::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Role::count();
            }

		}else{
            $search = $request->input('search.value');

            if ($id){
                $users = Role::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Role::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->count();

            }else{
                $users = Role::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Role::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->count();

            }



		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('roles.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="clients[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;

				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>

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
	    $title = 'Add New Role';
	    return view('admin.roles.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);

	    $input = $request->all();
	    $user = new Role();
        $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id =  Auth::user()->id;
        }
	    $res = array_key_exists('user_all', $input);
	    if ($res == false) {
		    $user->user_all = 0;
	    } else {
		    $user->user_all = 1;
	    }
	    $res = array_key_exists('user_add', $input);
	    if ($res == false) {
		    $user->user_add = 0;
	    } else {
		    $user->user_add = 1;
	    }
	    $res = array_key_exists('user_edit', $input);
	    if ($res == false) {
		    $user->user_edit = 0;
	    } else {
		    $user->user_edit = 1;
	    }

	    $res = array_key_exists('user_view', $input);
	    if ($res == false) {
		    $user->user_view = 0;
	    } else {
		    $user->user_view = 1;
	    }
	    $res = array_key_exists('user_delete', $input);
	    if ($res == false) {
		    $user->user_delete = 0;
	    } else {
		    $user->user_delete = 1;
	    }
	    $res = array_key_exists('user_view_full', $input);
	    if ($res == false) {
		    $user->user_view_full = 0;
	    } else {
		    $user->user_view_full = 1;
	    }
	    $res = array_key_exists('user_history', $input);
	    if ($res == false) {
		    $user->user_history = 0;
	    } else {
		    $user->user_history = 1;
	    }
	    $res = array_key_exists('user_enable', $input);
	    if ($res == false) {
		    $user->user_enable = 0;
	    } else {
		    $user->user_enable = 1;
	    }
	    $res = array_key_exists('brand_all', $input);
	    if ($res == false) {
		    $user->brand_all = 0;
	    } else {
		    $user->brand_all = 1;
	    }
	    $res = array_key_exists('brand_add', $input);
	    if ($res == false) {
		    $user->brand_add = 0;
	    } else {
		    $user->brand_add = 1;
	    }
	    $res = array_key_exists('brand_edit', $input);
	    if ($res == false) {
		    $user->brand_edit = 0;
	    } else {
		    $user->brand_edit = 1;
	    }
	    $res = array_key_exists('brand_view', $input);
	    if ($res == false) {
		    $user->brand_view = 0;
	    } else {
		    $user->brand_view = 1;
	    }
	    $res = array_key_exists('brand_delete', $input);
	    if ($res == false) {
		    $user->brand_delete = 0;
	    } else {
		    $user->brand_delete = 1;
	    }
	    $res = array_key_exists('device_all', $input);
	    if ($res == false) {
		    $user->device_all = 0;
	    } else {
		    $user->device_all = 1;
	    }
	    $res = array_key_exists('device_add', $input);
	    if ($res == false) {
		    $user->device_add = 0;
	    } else {
		    $user->device_add = 1;
	    }
	    $res = array_key_exists('device_edit', $input);
	    if ($res == false) {
		    $user->device_edit = 0;
	    } else {
		    $user->device_edit = 1;
	    }
	    $res = array_key_exists('device_view', $input);
	    if ($res == false) {
		    $user->device_view = 0;
	    } else {
		    $user->device_view = 1;
	    }
	    $res = array_key_exists('device_delete', $input);
	    if ($res == false) {
		    $user->device_delete = 0;
	    } else {
		    $user->device_delete = 1;
	    }
	    $res = array_key_exists('product_all', $input);
	    if ($res == false) {
		    $user->product_all = 0;
	    } else {
		    $user->product_all = 1;
	    }
	    $res = array_key_exists('product_add', $input);
	    if ($res == false) {
		    $user->product_add = 0;
	    } else {
		    $user->product_add = 1;
	    }
	    $res = array_key_exists('product_edit', $input);
	    if ($res == false) {
		    $user->product_edit = 0;
	    } else {
		    $user->product_edit = 1;
	    }
	    $res = array_key_exists('product_view', $input);
	    if ($res == false) {
		    $user->product_view = 0;
	    } else {
		    $user->product_view = 1;
	    }
	    $res = array_key_exists('product_delete', $input);
	    if ($res == false) {
		    $user->product_delete = 0;
	    } else {
		    $user->product_delete = 1;
	    }
	    $res = array_key_exists('product_manage_stock', $input);
	    if ($res == false) {
		    $user->product_manage_stock = 0;
	    } else {
		    $user->product_manage_stock = 1;
	    }
	    $res = array_key_exists('product_purchase_price', $input);
	    if ($res == false) {
		    $user->product_purchase_price = 0;
	    } else {
		    $user->product_purchase_price = 1;
	    }
	    $res = array_key_exists('product_sell_price', $input);
	    if ($res == false) {
		    $user->product_sell_price = 0;
	    } else {
		    $user->product_sell_price = 1;
	    }
	    $res = array_key_exists('product_discount', $input);
	    if ($res == false) {
		    $user->product_discount = 0;
	    } else {
		    $user->product_discount = 1;
	    }
	    $res = array_key_exists('job_all', $input);
	    if ($res == false) {
		    $user->job_all = 0;
	    } else {
		    $user->job_all = 1;
	    }
	    $res = array_key_exists('job_add', $input);
	    if ($res == false) {
		    $user->job_add = 0;
	    } else {
		    $user->job_add = 1;
	    }
	    $res = array_key_exists('job_edit', $input);
	    if ($res == false) {
		    $user->job_edit = 0;
	    } else {
		    $user->job_edit = 1;
	    }
	    $res = array_key_exists('job_view', $input);
	    if ($res == false) {
		    $user->job_view = 0;
	    } else {
		    $user->job_view = 1;
	    }
	    $res = array_key_exists('job_delete', $input);
	    if ($res == false) {
		    $user->job_delete = 0;
	    } else {
		    $user->job_delete = 1;
	    }
	    $res = array_key_exists('job_change_status', $input);
	    if ($res == false) {
		    $user->job_change_status = 0;
	    } else {
		    $user->job_change_status = 1;
	    }
	    $res = array_key_exists('job_add_parts', $input);
	    if ($res == false) {
		    $user->job_add_parts = 0;
	    } else {
		    $user->job_add_parts = 1;
	    }
	    $res = array_key_exists('job_assigned', $input);
	    if ($res == false) {
		    $user->job_assigned = 0;
	    } else {
		    $user->job_assigned = 1;
	    }
	    $res = array_key_exists('invoice_all', $input);
	    if ($res == false) {
		    $user->invoice_all = 0;
	    } else {
		    $user->invoice_all = 1;
	    }
	    $res = array_key_exists('invoice_add', $input);
	    if ($res == false) {
		    $user->invoice_add = 0;
	    } else {
		    $user->invoice_add = 1;
	    }
	    $res = array_key_exists('invoice_edit', $input);
	    if ($res == false) {
		    $user->invoice_edit = 0;
	    } else {
		    $user->invoice_edit = 1;
	    }
	    $res = array_key_exists('invoice_view', $input);
	    if ($res == false) {
		    $user->invoice_view = 0;
	    } else {
		    $user->invoice_view = 1;
	    }
	    $res = array_key_exists('invoice_delete', $input);
	    if ($res == false) {
		    $user->invoice_delete = 0;
	    } else {
		    $user->invoice_delete = 1;
	    }
	    $res = array_key_exists('invoice_change_status', $input);
	    if ($res == false) {
		    $user->invoice_change_status = 0;
	    } else {
		    $user->invoice_change_status = 1;
	    }
	    $res = array_key_exists('enquiries_all', $input);
	    if ($res == false) {
		    $user->enquiries_all = 0;
	    } else {
		    $user->enquiries_all = 1;
	    }
	    $res = array_key_exists('enquiries_add', $input);
	    if ($res == false) {
		    $user->enquiries_add = 0;
	    } else {
		    $user->enquiries_add = 1;
	    }
	    $res = array_key_exists('enquiries_edit', $input);
	    if ($res == false) {
		    $user->enquiries_edit = 0;
	    } else {
		    $user->enquiries_edit = 1;
	    }
	    $res = array_key_exists('enquiries_view', $input);
	    if ($res == false) {
		    $user->enquiries_view = 0;
	    } else {
		    $user->enquiries_view = 1;
	    }
	    $res = array_key_exists('enquiries_delete', $input);
	    if ($res == false) {
		    $user->enquiries_delete = 0;
	    } else {
		    $user->enquiries_delete = 1;
	    }
	    $res = array_key_exists('enquiries_send', $input);
	    if ($res == false) {
		    $user->enquiries_send = 0;
	    } else {
		    $user->enquiries_send = 1;
	    }
	    $res = array_key_exists('setting_all', $input);
	    if ($res == false) {
		    $user->setting_all = 0;
	    } else {
		    $user->setting_all = 1;
	    }
	    $res = array_key_exists('setting_view_all', $input);
	    if ($res == false) {
		    $user->setting_view_all = 0;
	    } else {
		    $user->setting_view_all = 1;
	    }
	    $res = array_key_exists('setting_basic_view', $input);
	    if ($res == false) {
		    $user->setting_basic_view = 0;
	    } else {
		    $user->setting_basic_view = 1;
	    }
	    $res = array_key_exists('setting_basic_edit', $input);
	    if ($res == false) {
		    $user->setting_basic_edit = 0;
	    } else {
		    $user->setting_basic_edit = 1;
	    }
	    $res = array_key_exists('setting_sms_view', $input);
	    if ($res == false) {
		    $user->setting_sms_view = 0;
	    } else {
		    $user->setting_sms_view = 1;
	    }
	    $res = array_key_exists('setting_sms_edit', $input);
	    if ($res == false) {
		    $user->setting_sms_edit = 0;
	    } else {
		    $user->setting_sms_edit = 1;
	    }
	    $res = array_key_exists('setting_job_view', $input);
	    if ($res == false) {
		    $user->setting_job_view = 0;
	    } else {
		    $user->setting_job_view = 1;
	    }
	    $res = array_key_exists('setting_job_edit', $input);
	    if ($res == false) {
		    $user->setting_job_edit = 0;
	    } else {
		    $user->setting_job_edit = 1;
	    }
	    $res = array_key_exists('setting_email_view', $input);
	    if ($res == false) {
		    $user->setting_email_view = 0;
	    } else {
		    $user->setting_email_view = 1;
	    }
	    $res = array_key_exists('setting_email_edit', $input);
	    if ($res == false) {
		    $user->setting_email_edit = 0;
	    } else {
		    $user->setting_email_edit = 1;
	    }
	    $res = array_key_exists('setting_other_view', $input);
	    if ($res == false) {
		    $user->setting_other_view = 0;
	    } else {
		    $user->setting_other_view = 1;
	    }
	    $res = array_key_exists('setting_other_edit', $input);
	    if ($res == false) {
		    $user->setting_other_edit = 0;
	    } else {
		    $user->setting_other_edit = 1;
	    }
	    $res = array_key_exists('setting_cms_view', $input);
	    if ($res == false) {
		    $user->setting_cms_view = 0;
	    } else {
		    $user->setting_cms_view = 1;
	    }
	    $res = array_key_exists('setting_cms_edit', $input);
	    if ($res == false) {
		    $user->setting_cms_edit = 0;
	    } else {
		    $user->setting_cms_edit = 1;
	    }


	    $user->save();

	    Session::flash('success_message', 'Great! Role has been saved successfully!');

	    return redirect()->back();
    }

    public function popupAdd(Request $request)
    {
	    $this->validate($request, [
		    'name' => 'required|max:255',

	    ]);

	    $input = $request->all();
	    $user = new User();
	    $user->name = $input['name'];
	    $user->email = $input['email'];
	    $user->phone = $input['phone'];
	    $user->password = bcrypt("12345607");
        $user->parent_id = Auth::id();
	    $user->save();

	    Session::flash('success_message', 'Great! Customer has been saved successfully!');

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
	    $user = Role::find($id);
	    return view('admin.roles.single', ['title' => 'Client detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = Role::findOrFail($request->id);


		return view('admin.roles.detail', ['title' => 'Role Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Role::find($id);
	    return view('admin.roles.edit', ['title' => 'Edit Role details'])->withUser($user);
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
	    $user = Role::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id =  Auth::user()->parent_id;
        }
        $res = array_key_exists('user_all', $input);
        if ($res == false) {
            $user->user_all = 0;
        } else {
            $user->user_all = 1;
        }
        $res = array_key_exists('user_add', $input);
        if ($res == false) {
            $user->user_add = 0;
        } else {
            $user->user_add = 1;
        }
        $res = array_key_exists('user_edit', $input);
        if ($res == false) {
            $user->user_edit = 0;
        } else {
            $user->user_edit = 1;
        }

        $res = array_key_exists('user_view', $input);
        if ($res == false) {
            $user->user_view = 0;
        } else {
            $user->user_view = 1;
        }
        $res = array_key_exists('user_delete', $input);
        if ($res == false) {
            $user->user_delete = 0;
        } else {
            $user->user_delete = 1;
        }
        $res = array_key_exists('user_view_full', $input);
        if ($res == false) {
            $user->user_view_full = 0;
        } else {
            $user->user_view_full = 1;
        }
        $res = array_key_exists('user_history', $input);
        if ($res == false) {
            $user->user_history = 0;
        } else {
            $user->user_history = 1;
        }
        $res = array_key_exists('user_enable', $input);
        if ($res == false) {
            $user->user_enable = 0;
        } else {
            $user->user_enable = 1;
        }
        $res = array_key_exists('brand_all', $input);
        if ($res == false) {
            $user->brand_all = 0;
        } else {
            $user->brand_all = 1;
        }
        $res = array_key_exists('brand_add', $input);
        if ($res == false) {
            $user->brand_add = 0;
        } else {
            $user->brand_add = 1;
        }
        $res = array_key_exists('brand_edit', $input);
        if ($res == false) {
            $user->brand_edit = 0;
        } else {
            $user->brand_edit = 1;
        }
        $res = array_key_exists('brand_view', $input);
        if ($res == false) {
            $user->brand_view = 0;
        } else {
            $user->brand_view = 1;
        }
        $res = array_key_exists('brand_delete', $input);
        if ($res == false) {
            $user->brand_delete = 0;
        } else {
            $user->brand_delete = 1;
        }
        $res = array_key_exists('device_all', $input);
        if ($res == false) {
            $user->device_all = 0;
        } else {
            $user->device_all = 1;
        }
        $res = array_key_exists('device_add', $input);
        if ($res == false) {
            $user->device_add = 0;
        } else {
            $user->device_add = 1;
        }
        $res = array_key_exists('device_edit', $input);
        if ($res == false) {
            $user->device_edit = 0;
        } else {
            $user->device_edit = 1;
        }
        $res = array_key_exists('device_view', $input);
        if ($res == false) {
            $user->device_view = 0;
        } else {
            $user->device_view = 1;
        }
        $res = array_key_exists('device_delete', $input);
        if ($res == false) {
            $user->device_delete = 0;
        } else {
            $user->device_delete = 1;
        }
        $res = array_key_exists('product_all', $input);
        if ($res == false) {
            $user->product_all = 0;
        } else {
            $user->product_all = 1;
        }
        $res = array_key_exists('product_add', $input);
        if ($res == false) {
            $user->product_add = 0;
        } else {
            $user->product_add = 1;
        }
        $res = array_key_exists('product_edit', $input);
        if ($res == false) {
            $user->product_edit = 0;
        } else {
            $user->product_edit = 1;
        }
        $res = array_key_exists('product_view', $input);
        if ($res == false) {
            $user->product_view = 0;
        } else {
            $user->product_view = 1;
        }
        $res = array_key_exists('product_delete', $input);
        if ($res == false) {
            $user->product_delete = 0;
        } else {
            $user->product_delete = 1;
        }
        $res = array_key_exists('product_manage_stock', $input);
        if ($res == false) {
            $user->product_manage_stock = 0;
        } else {
            $user->product_manage_stock = 1;
        }
        $res = array_key_exists('product_purchase_price', $input);
        if ($res == false) {
            $user->product_purchase_price = 0;
        } else {
            $user->product_purchase_price = 1;
        }
        $res = array_key_exists('product_sell_price', $input);
        if ($res == false) {
            $user->product_sell_price = 0;
        } else {
            $user->product_sell_price = 1;
        }
        $res = array_key_exists('product_discount', $input);
        if ($res == false) {
            $user->product_discount = 0;
        } else {
            $user->product_discount = 1;
        }
        $res = array_key_exists('job_all', $input);
        if ($res == false) {
            $user->job_all = 0;
        } else {
            $user->job_all = 1;
        }
        $res = array_key_exists('job_add', $input);
        if ($res == false) {
            $user->job_add = 0;
        } else {
            $user->job_add = 1;
        }
        $res = array_key_exists('job_edit', $input);
        if ($res == false) {
            $user->job_edit = 0;
        } else {
            $user->job_edit = 1;
        }
        $res = array_key_exists('job_view', $input);
        if ($res == false) {
            $user->job_view = 0;
        } else {
            $user->job_view = 1;
        }
        $res = array_key_exists('job_delete', $input);
        if ($res == false) {
            $user->job_delete = 0;
        } else {
            $user->job_delete = 1;
        }
        $res = array_key_exists('job_change_status', $input);
        if ($res == false) {
            $user->job_change_status = 0;
        } else {
            $user->job_change_status = 1;
        }
        $res = array_key_exists('job_add_parts', $input);
        if ($res == false) {
            $user->job_add_parts = 0;
        } else {
            $user->job_add_parts = 1;
        }
        $res = array_key_exists('job_assigned', $input);
        if ($res == false) {
            $user->job_assigned = 0;
        } else {
            $user->job_assigned = 1;
        }
        $res = array_key_exists('invoice_all', $input);
        if ($res == false) {
            $user->invoice_all = 0;
        } else {
            $user->invoice_all = 1;
        }
        $res = array_key_exists('invoice_add', $input);
        if ($res == false) {
            $user->invoice_add = 0;
        } else {
            $user->invoice_add = 1;
        }
        $res = array_key_exists('invoice_edit', $input);
        if ($res == false) {
            $user->invoice_edit = 0;
        } else {
            $user->invoice_edit = 1;
        }
        $res = array_key_exists('invoice_view', $input);
        if ($res == false) {
            $user->invoice_view = 0;
        } else {
            $user->invoice_view = 1;
        }
        $res = array_key_exists('invoice_delete', $input);
        if ($res == false) {
            $user->invoice_delete = 0;
        } else {
            $user->invoice_delete = 1;
        }
        $res = array_key_exists('invoice_change_status', $input);
        if ($res == false) {
            $user->invoice_change_status = 0;
        } else {
            $user->invoice_change_status = 1;
        }
        $res = array_key_exists('enquiries_all', $input);
        if ($res == false) {
            $user->enquiries_all = 0;
        } else {
            $user->enquiries_all = 1;
        }
        $res = array_key_exists('enquiries_add', $input);
        if ($res == false) {
            $user->enquiries_add = 0;
        } else {
            $user->enquiries_add = 1;
        }
        $res = array_key_exists('enquiries_edit', $input);
        if ($res == false) {
            $user->enquiries_edit = 0;
        } else {
            $user->enquiries_edit = 1;
        }
        $res = array_key_exists('enquiries_view', $input);
        if ($res == false) {
            $user->enquiries_view = 0;
        } else {
            $user->enquiries_view = 1;
        }
        $res = array_key_exists('enquiries_delete', $input);
        if ($res == false) {
            $user->enquiries_delete = 0;
        } else {
            $user->enquiries_delete = 1;
        }
        $res = array_key_exists('enquiries_send', $input);
        if ($res == false) {
            $user->enquiries_send = 0;
        } else {
            $user->enquiries_send = 1;
        }
        $res = array_key_exists('setting_all', $input);
        if ($res == false) {
            $user->setting_all = 0;
        } else {
            $user->setting_all = 1;
        }
        $res = array_key_exists('setting_view_all', $input);
        if ($res == false) {
            $user->setting_view_all = 0;
        } else {
            $user->setting_view_all = 1;
        }
        $res = array_key_exists('setting_basic_view', $input);
        if ($res == false) {
            $user->setting_basic_view = 0;
        } else {
            $user->setting_basic_view = 1;
        }
        $res = array_key_exists('setting_basic_edit', $input);
        if ($res == false) {
            $user->setting_basic_edit = 0;
        } else {
            $user->setting_basic_edit = 1;
        }
        $res = array_key_exists('setting_sms_view', $input);
        if ($res == false) {
            $user->setting_sms_view = 0;
        } else {
            $user->setting_sms_view = 1;
        }
        $res = array_key_exists('setting_sms_edit', $input);
        if ($res == false) {
            $user->setting_sms_edit = 0;
        } else {
            $user->setting_sms_edit = 1;
        }
        $res = array_key_exists('setting_job_view', $input);
        if ($res == false) {
            $user->setting_job_view = 0;
        } else {
            $user->setting_job_view = 1;
        }
        $res = array_key_exists('setting_job_edit', $input);
        if ($res == false) {
            $user->setting_job_edit = 0;
        } else {
            $user->setting_job_edit = 1;
        }
        $res = array_key_exists('setting_email_view', $input);
        if ($res == false) {
            $user->setting_email_view = 0;
        } else {
            $user->setting_email_view = 1;
        }
        $res = array_key_exists('setting_email_edit', $input);
        if ($res == false) {
            $user->setting_email_edit = 0;
        } else {
            $user->setting_email_edit = 1;
        }
        $res = array_key_exists('setting_other_view', $input);
        if ($res == false) {
            $user->setting_other_view = 0;
        } else {
            $user->setting_other_view = 1;
        }
        $res = array_key_exists('setting_other_edit', $input);
        if ($res == false) {
            $user->setting_other_edit = 0;
        } else {
            $user->setting_other_edit = 1;
        }
        $res = array_key_exists('setting_cms_view', $input);
        if ($res == false) {
            $user->setting_cms_view = 0;
        } else {
            $user->setting_cms_view = 1;
        }
        $res = array_key_exists('setting_cms_edit', $input);
        if ($res == false) {
            $user->setting_cms_edit = 0;
        } else {
            $user->setting_cms_edit = 1;
        }

	    $user->save();

	    Session::flash('success_message', 'Great! Role successfully updated!');
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
	    $user = Role::find($id);
        $user->delete();
        Session::flash('success_message', 'User successfully deleted!');
	    return redirect()->route('roles.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'roles' => 'required',
		]);
		foreach ($input['roles'] as $index => $id) {

			$user = Role::find($id);

            $user->delete();

		}
		Session::flash('success_message', 'Roles successfully deleted!');
		return redirect()->back();

	}
}
