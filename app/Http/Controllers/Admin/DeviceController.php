<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Device;
use App\Models\PreRepair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;

class DeviceController extends Controller
{

    public function index()
    {
	    $title = 'Devices';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
        $brands = Brand::where('user_id', '!=','null')->latest()->get();
	    return view('admin.devices.index',compact('title','shops','brands'));
    }



	public function getClients(Request $request){

		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'brand_id',
			3 => 'user_id',
			6 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = Device::where([['user_id',$id]])->count();
        }else{
            $totalData = Device::count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if ($id){
                $users = Device::where([['user_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->
                    when($request->has('brand_id'), function ($query) use ($request) {
                        $query->where('brand_id', $request->brand_id);
                        })->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Device::where([['user_id',$id]])
                                        ->when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('user_id', $request->shop_id);
                                        })->when($request->has('brand_id'), function ($query) use ($request) {
                                        $query->where('brand_id', $request->brand_id);
                                        })->count();
            }else{
                $users = Device::when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('user_id', $request->shop_id);
                                    })->when($request->has('brand_id'), function ($query) use ($request) {
                                    $query->where('brand_id', $request->brand_id);
                                    })->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                $totalFiltered = Device::when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('user_id', $request->shop_id);
                                        })->when($request->has('brand_id'), function ($query) use ($request) {
                                        $query->where('brand_id', $request->brand_id);
                                        })->count();

            }

		}else{
			$search = $request->input('search.value');
			if($id){
                $users = Device::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Device::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })
                    ->count();
            }else{
                $users = Device::where([
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
                $totalFiltered = Device::where([
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
				$edit_url = route('devices.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="Devices[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
				$nestedData['brand'] = $r->brand->name;

                if ($r->shop){
                    $nestedData['shop_name'] = $r->shop->name;
                }else{
                    $nestedData['shop_name'] = 'Nil';
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
                    $view = $user->permission->device_view;
                    $edit = $user->permission->device_edit;
                    $del = $user->permission->device_delete;
                }
                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $edit_link = '<a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
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
        if (Auth::user()->role == 2){
            $brands = Brand::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        return view('admin.devices.create',['title' => 'Add New Devices ','brands'=>$brands]);
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
	    $user = new Device();
	    $user->name = $input['name'];
	    $user->brand_id = $input['brand'];
	    $user->type = $input['type'];
        $user->pre_repair = $input['pre_repair'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();
//        foreach ($input["pre_repair"] as $key => $q){
//            $pre = new PreRepair();
//            $pre->name = $q;
//            $pre->device_id = $user->id;
//            $pre->save();
//        }
	    Session::flash('success_message', 'Great! Device has been saved successfully!');
	    $user->save();
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
	    $user = Device::find($id);
	    return view('admin.devices.single', ['title' => 'Device detail', 'user' => $user]);
    }

    public function import()
    {
        return view('admin.devices.import', ['title' => 'Device Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/device.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'device-sample.xlsx', $headers);
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
//                $organization = Auth::user()->id;
//                $request->session()->put('organization', $organization);
                $wfts = (new FastExcel)->import($readFile, function ($line) {
                    if (Auth::user()->role == 2){
                        $user_id = Auth::id();
                    }elseif (Auth::user()->role == 3){
                        $user_id = Auth::user()->parent_id;
                    }else{
                        $user_id = Auth::id();
                    }
                    $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();

                    if (!$brand){
                        $brand = new Brand();
                        $brand->name = $line['Brand'];
                        $brand->user_id = $user_id;
                        $brand->save();
                    }
                    $user = Device::where([["name",$line['Name']],["user_id",$user_id]])->first();
                    if (!$user){
                        $user = new Device();
                    }
                    if ($line['Type'] == 'Mobile'){
                        $user->type = 1;
                    }else{
                        $user->type = 2;
                    }
                    $user->name = $line['Name'];
                    $user->pre_repair = $line['Pre Repair'];
                    $user->brand_id = $brand->id;
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
        $brands = Device::where("user_id",$user_id)->get();
        return Response::download((new FastExcel($brands))->export('devices.csv', function ($pass) {
            $brand = Brand::findOrFail($pass->brand_id);
            if($pass->type == 1){
                $type = 'Mobile';
            }else{
                $type = 'Laptop';
            }
            return [
                'Name' => $pass->name,
                'Brand' => $brand->name,
                'Type' => $type,
                'Pre Repair' => $pass->pre_repair,
                'Created At' => $pass->created_at,
            ];

        }));
    }


    public function clientDetail(Request $request)
	{

		$user = Device::findOrFail($request->id);


		return view('admin.devices.detail', ['title' => 'Device Detail', 'user' => $user]);
	}
	public function getDevices(Request $request)
	{
		$device_models = Device::where([["type",$request->device],["brand_id",$request->brand]])->get();
        return view('admin.jobs.device-models', ['title' => 'Device Detail', 'device_models' => $device_models]);
	}
	public function productDevices(Request $request)
	{
		$device_models = Device::where([["brand_id",$request->brand]])->get();
        return view('admin.jobs.device-models', ['title' => 'Device Detail', 'device_models' => $device_models]);
	}
	public function getPreRepair(Request $request)
	{
		$device = Device::find($request->id);
        return view('admin.jobs.pre-repair', ['title' => 'Device Detail', 'device' => $device]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Device::find($id);
        if (Auth::user()->role == 2){
            $brands = Brand::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $brands = Brand::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
	    return view('admin.devices.edit', ['title' => 'Edit Device details','brands' => $brands])->withUser($user);
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
	    $user = Device::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
	    $user->brand_id = $input['brand'];
	    $user->type = $input['type'];
	    $user->pre_repair = $input['pre_repair'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();
//        foreach ($input["pre_repair"] as $key => $q){
//            $q_id = $input["pre_id"][$key];
//            if ($q_id != 0){
//                $pre = PreRepair::find($q_id);
//            }else{
//                $pre = new PreRepair();
//            }
//            $pre->name = $q;
//            $pre->device_id = $user->id;
//            $pre->save();
//
//        }
	    Session::flash('success_message', 'Great! Device successfully updated!');
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

	    $user = Device::find($id);

		    $user->delete();
		    Session::flash('success_message', 'Device successfully deleted!');
	    return redirect()->route('devices.index');

    }
    public function preRepairDelete($id)
    {
	    $user = PreRepair::find($id);
        $user->delete();
        Session::flash('success_message', 'Pre Repair successfully deleted!');
	    return redirect()->back();

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'Devices' => 'required',

		]);
		foreach ($input['Devices'] as $index => $id) {

			$user = Device::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Device successfully deleted!');
		return redirect()->back();

	}
    public function fetchBrands(Request $request)
    {

        $data['brands'] = Brand::where('user_id', '!=','null')
                                    ->where('user_id',$request->shop_id)->latest()->get(["name", "id"]);

        return response()->json($data);
    }
}
