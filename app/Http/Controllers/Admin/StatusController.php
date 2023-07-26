<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Courier;
use App\Models\Device;
use App\Models\Status;
use App\Models\Statususe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;

class StatusController extends Controller
{

    public function index()
    {

	    $title = 'Status ';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    return view('admin.statuses.index',compact('title','shops'));
    }


	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'created_at',
			6 => 'action'
		);
        if  (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Status::where([['user_id',$id]])
                                ->when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('user_id', $request->shop_id);
                                })->where('shared',1)->count();
        }else{
            $totalData = Status::when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('user_id', $request->shop_id);
                                })->count();
        }


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            if ($id){
                $users = Status::where('shared','1')
                    ->orwhere('user_id',$id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = Status::where('shared',1)->count();
            }else{
                $users = Status::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->get();
                $totalFiltered = Status::when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('user_id', $request->shop_id);
                                        })
                                        ->count();
            }

        }else{
            $search = $request->input('search.value');

            if ($id){
                $users = Status::where([
                    ['name', 'like', "%{$search}%"],
                ])

                ->where('shared',1)
                ->orwhere('user_id',$id)
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Status::where([
                    ['name', 'like', "%{$search}%"],

                ])
                ->where('shared',1)
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->count();

            }else{
                $users = Status::where([
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->get();
                $totalFiltered = Status::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->count();
            }

        }

		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('statuses.edit',$r->id);
                $used = '';
                if(auth()->user()->role == 2){
                    $used = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();view_used('.$r->id.');" title="Useds" href="javascript:void(0)">
                        <i class="icon-1x text-dark-50 flaticon-edit"></i>
                        </a>';
                }
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="statuses[]" value="'.$r->id.'" ><span></span></label></td>';
				$nestedData['name'] = $r->name;
                if(auth()->user()->role == 1){
                    $nestedData['shop'] = $r->shop->name ?? '';
                }

				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
                $user = Auth::user();
                if($user->role == 1){
                    $edit = 1;
                    $del = 1;
                    $view = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->brand_view;
                    $edit = $user->permission->brand_edit;
                    $del = $user->permission->brand_delete;
                }


                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" name="View Template" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>'.$used;
                if(!$view){$view_link = '';}
                $edit_link = '';
                $delete_link = '';
                if($r->user_id == auth()->user()->id ){
                    $edit_link = '<a name="Edit Template" class="btn btn-sm btn-clean btn-icon"
                    href="'.$edit_url.'">
                    <i class="icon-1x text-dark-50 flaticon-edit"></i>
                    </a>';
                    if(!$edit){$edit_link = '';}

                    $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" name=\"Delete Template\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                    if(!$del){$delete_link = '';}
                }elseif(auth()->user()->id == 1){
                    $edit_link = '<a name="Edit Template" class="btn btn-sm btn-clean btn-icon"
                    href="'.$edit_url.'">
                    <i class="icon-1x text-dark-50 flaticon-edit"></i>
                    </a>';
                    if(!$edit){$edit_link = '';}

                    $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" name=\"Delete Template\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                    if(!$del){$delete_link = '';}
                }

                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
                                    $edit_link
                                    $delete_link
                                </td>
                                </div>
                            ";
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
	    $title = 'Add New Status';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    return view('admin.statuses.create',compact('title','shops'));
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
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
        if(auth()->user()->role = 1){
            $array = json_encode($request->shops  ?? '', true);
        }
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }
	    $input = $request->all();
	    $user = new Status();
	    $user->name = $input['name'];
	    $user->color = $input['color'];
	    $user->email_subject = $input['email_subject'];
	    $user->sms_template = $input['sms_template'];
	    $user->sms_type = $input['sms_type'];
	    $user->sms_peid = $input['sms_peid'];
	    $user->sms_template_id = $input['sms_template_id'];
	    $user->whatsapp_template = $input['whatsapp_template'];
	    $user->email_body = $input['email_body'];
        $user->shared = $shared;
        if(auth()->user()->role = 2){
            $user->shop_ids = $array;
        }

	    $user->sort_order = $input['sort_order'];
        if (auth()->user()->id == 1){
            $user->user_id =  auth()->user()->id;
        }
        if (auth()->user()->id == 3){
            $user->user_id =  Auth::user()->parent_id;
        }
        $res = array_key_exists('complete', $input);
        if ($res == false) {
            $user->complete = 0;
        } else {
            $user->complete = 1;

        }
	    $user->save();

	    Session::flash('success_message', 'Great! Status has been saved successfully!');
	    $user->save();
	    return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        return view('admin.statuses.import', ['title' => 'Client Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/status.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'status-sample.xlsx', $headers);
    }


    public function importSave(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('file');
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $destinationPath = "uploads/users/";
                $extension = $file->getClientOriginalExtension('file');
                $fileName = $file->getClientOriginalName('file'); // renameing image
                $request->file('file')->move($destinationPath, $fileName);
                $readFile = $destinationPath . $fileName;
        //      $organization = Auth::user()->id;
        //      $request->session()->put('organization', $organization);
                $wfts = (new FastExcel)->import($readFile, function ($line) {
                    if (Auth::user()->role == 2){
                        $user_id = Auth::id();
                    }elseif (Auth::user()->role == 3){
                        $user_id = Auth::user()->parent_id;
                    }else{
                        $user_id = Auth::id();
                    }

                    $user = Status::where([["name",$line['Name']],["user_id",$user_id]])->first();
                    if (!$user){
                        $user = new Status();
                    }
                    if ($line['Complete'] == 'Yes'){
                        $user->complete = 1;
                    }else{
                        $user->complete = 0;
                    }
                    $user->name = $line['Name'];
                    $user->color = $line['Color'];
                    $user->sms_type = $line['SMS Type'];
                    $user->sms_peid = $line['SMS PEID'];
                    $user->sms_template_id = $line['SMS Template ID'];
                    $user->sms_template = $line['SMS Template'];
                    $user->whatsapp_template = $line['Whatsapp Template'];
                    $user->email_subject = $line['Email Subject'];
                    $user->email_body = $line['Email Body'];
                    $user->user_id = $user_id;
                    return $user->save();

                });

//                Excel::import(new WftsImport, $readFile);
            }
        }

        Session::flash('success_message', 'Success! File Imported successfully!');
        return redirect()->back();

    }
    public function export()
    {
        if (Auth::user()->role == 2){
            $user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user_id = Auth::user()->parent_id;
        }elseif (Auth::user()->role == 1){
            $user_id = Auth::id();
        }
        $data = Status::where("user_id",$user_id)->get();
        return Response::download((new FastExcel($data))->export('statuses.csv', function ($pass) {
            if($pass->complete == 1){
                $complete = 'Yes';
            }else{
                $complete = 'No';
            }
            return [
                'Name' => $pass->name,
                'Color' => $pass->color,
                'Complete' => $complete,
                'SMS Type' => $pass->sms_type,
                'SMS PEID' => $pass->sms_peid,
                'SMS Template ID' => $pass->sms_template_id,
                'SMS Template' => $pass->sms_template,
                'Whatsapp Template' => $pass->whatsapp_template,
                'Email Subject' => $pass->email_subject,
                'Email Body' => $pass->email_body,
            ];

        }));
    }
    public function show($id)
    {
	    $user = Status::find($id);
	    return view('admin.statuses.single', ['title' => 'Courier detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = Status::findOrFail($request->id);


		return view('admin.statuses.detail', ['title' => 'Status Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Status::find($id);
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    return view('admin.statuses.edit', ['title' => 'Edit Status details','shops' =>$shops])->withUser($user);
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
        $input = $request->all();
	    $user = Status::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
        if(auth()->user()->role = 1){
            $array = json_encode($request->shops  ?? '', true);
        }
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }
	    $input = $request->all();
	    $user->name = $input['name'];
        $user->color = $input['color'];
        $user->email_subject = $input['email_subject'];
        $user->sms_template = $input['sms_template'];
        $user->sms_type = $input['sms_type'];
        $user->sms_peid = $input['sms_peid'];
        $user->sms_template_id = $input['sms_template_id'];
        $user->whatsapp_template = $input['whatsapp_template'];
        $user->email_body = $input['email_body'];
        $user->sort_order = $input['sort_order'];
        $user->shared = $shared;
        // if(auth()->user()->role = 2){
        //     $user->shop_ids = $array;
        // }
        // if (auth()->user()->id == 1){
        //     $user->user_id =  auth()->user()->id;
        // }
        // if (auth()->user()->id == 3){
        //     $user->user_id =  Auth::user()->parent_id;
        // }
        $res = array_key_exists('complete', $input);
        if ($res == false) {
            $user->complete = 0;
        } else {
            $user->complete = 1;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Status successfully updated!');
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
	    $user = Status::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Status successfully deleted!');
	    return redirect()->route('statuses.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'statuses' => 'required',

		]);
		foreach ($input['statuses'] as $index => $id) {

			$user = Status::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Status successfully deleted!');
		return redirect()->back();

	}
    public function getused(Request $request)
    {
        $template = Status::with('used')->findOrFail($request->id);


		return view('admin.statuses.assign_used', ['title' => 'template Use', 'template' => $template]);
    }
    public function assign_used(Request $request)
    {
        $input = $request->all();
        $used = array_key_exists('used', $input);

        if ($used == false) {
            $used = 0;

        } else {
            $used = 1;

        }
        $invoice = Statususe::where('status_id',$request->id)->where('user_id',auth()->user()->id)->first();

        if(!empty($invoice)){

            $template = Statususe::where('status_id',$request->id)->where('user_id',auth()->user()->id)->update([
                'user_id' => auth()->user()->id,
                'status_id'=> $request->id,
                'used' => $used
            ]);
        }
        else{
            $template = Statususe::create([
                'user_id' => auth()->user()->id,
                'status_id'=> $request->id,
                'used' => $used
            ]);

        }
        Session::flash('success_message', 'Great! Status has been Update successfully!');

        return redirect()->route('statuses.index');
    }
}
