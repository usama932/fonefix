<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Compatible;
use App\Models\Device;
use App\Models\DeviceCompatible;
use App\Models\PreRepair;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;
use function Doctrine\Common\Cache\Psr6\get;

class CompatibleController extends Controller
{

    public function index(Request $request)
    {
       // dd($request->all());
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    $title = 'Compatible List';
        if ($request->id){
            $id = $request->id;
        }else{
            $id = null;
        }
        if (Auth::user()->role == 2){
            $types = Type::where("user_id",Auth::user()->id)->get();
            if (!$id){
                $type = Type::where("user_id",Auth::user()->id)->first();
                if ($type){
                    $id = $type->id;
                }else{
                    $id = null;
                }
            }
        }elseif (Auth::user()->role == 3){
            $types = Type::where("user_id",Auth::user()->parent_id)->get();
            if (!$id){
                $type = Type::where("user_id",Auth::user()->id)->first();
                if ($type){
                    $id = $type->id;
                }else{
                    $id = null;
                }
            }
        }else{
            $types = Type::all();
            if (!$id){
                $type = Type::first();
                if ($type){
                    $id = $type->id;
                }else{
                    $id = null;
                }
            }
        }
        $compatibles = Compatible::where("type_id",$id)->get();
	    return view('admin.compatibles.index',compact('title','types','id','compatibles','shops'));
    }


	public function getClients(Request $request){
     // dd($request->all());
        $columns = array(
			0 => 'id',
			1 => 'type_id',
			2 => 'name',
            3 => 'shop_id',
			4 => 'action'
		);

        $id = $request->type ?? '';
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if (!empty($id)){
                $users = Compatible::where([['type_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Compatible::where([['type_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->count();
                $totalData = Compatible::where([['type_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->count();
            }else{
                $users = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->count();
                $totalData = Compatible::where([['type_id',$id]])->count();
            }

		}else{

			$search = $request->input('search.value');
			if ($id == null){

                $users = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],

                ])
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $totalFiltered = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],

                ])->count();
                $totalData = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->count();

            }else{
                $users = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                ])
                    ->offset($start)
                    ->limit($limit);
                $totalFiltered = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->where([
                    ['name', 'like', "%{$search}%"],
                ])->count();
                $totalData = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->where([['type_id',$id]])->count();
                $totalFiltered = Compatible::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('shop_id', $request->shop_id);
                    })->count();
            }

		}

		$data = array();

		if($users){
			foreach($users as $r){
                $compatible = [];
				$edit_url = route('compatibles.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="brands[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
                foreach($r->devices as $device){
                    $newCompete = $device->device->name.'/';
                    array_push($compatible, $newCompete);
                }
                $nestedData['compatitble'] = $compatible;

                $nestedData['type_id'] = $r->type->name;
                if(auth()->user()->role == '1'){
                    $nestedData['shop_id'] = $r->shop->name ?? 'Not Assign';
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
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->pluck('name','id');
        if (Auth::user()->role == 2){
            $devices = Device::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $devices = Device::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        return view('admin.compatibles.create',['shops'=> $shops,'title' => 'Add New Compatible ','devices'=>$devices,'types'=>$types]);
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
		    'type' => 'required',
		    'name' => 'required',
		    'compatible' => 'required',
            'shop'      => 'required',
	    ]);

	    $input = $request->all();
        $compatible = new Compatible();
        $compatible->type_id = $request->type;
        $compatible->name = $request->name;
        $compatible->shop_id = $request->shop;
        $compatible->save();

        foreach ($request->compatible as $item) {
            $new_compatible = new DeviceCompatible();
            $new_compatible->compatible_id = $compatible->id;
            $new_compatible->device_id = $item;
            $new_compatible->save();
        }

	    Session::flash('success_message', 'Great! Compatible has been saved successfully!');
	    return redirect()->back();
    }


    public function show($id)
    {
	    $user = Device::find($id);
	    return view('admin.compatibles.single', ['title' => 'Device detail', 'user' => $user]);
    }

    public function import()
    {
        return view('admin.compatibles.import', ['title' => 'Device Import']);
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
    public function export($id)
    {
        if (Auth::user()->role == 2){
            $user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user_id = Auth::user()->parent_id;
        }elseif (Auth::user()->role == 1){
            $user_id = Auth::id();
        }
        $brands = Compatible::where("type_id",$id)->get();
        return Response::download((new FastExcel($brands))->export('compatibles.csv', function ($pass) {
            $compatibles = DeviceCompatible::where("compatible_id",$pass->id)->get();
            $all = "";
            foreach($compatibles as $compatible) {
                $device = Device::findOrFail($compatible->device_id);
                if ($device){
                    $all = $all . $device->name ."/";
                }
            }

            return [
                'Name' => $pass->name,
                'Compatible With' => $all,
                'Created At' => $pass->created_at,
            ];

        }));
    }


    public function clientDetail(Request $request)
	{

		$user = Compatible::findOrFail($request->id);


		return view('admin.compatibles.detail', ['title' => 'Compatible Detail', 'user' => $user]);
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


    public function edit($id)
    {

	    $user = Compatible::find($id);
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->pluck('name','id');
        if (Auth::user()->role == 2){
            $devices = Device::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::where("user_id",Auth::user()->id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $devices = Device::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::where("user_id",Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $types = Type::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $compatibles = DeviceCompatible::where("compatible_id",$user->id)->pluck('device_id')->toArray();
	    return view('admin.compatibles.edit', ['shops'=> $shops,'title' => 'Edit Compatible details','devices' => $devices,'types' => $types,'compatibles' => $compatibles])->withUser($user);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'name' => 'required',
            'compatible' => 'required',
        ]);

        $input = $request->all();
        $compatible = Compatible::findOrFail($id);
        $compatible->type_id = $request->type;
        $compatible->name = $request->name;
        $compatible->shop_id = $request->shop;

        $compatible->save();
        $olds = DeviceCompatible::where("compatible_id", $compatible->id)->get();
        foreach($olds as $old){
            $old->delete();
        }
        foreach ($request->compatible as $item) {
            $new_compatible = new DeviceCompatible();
            $new_compatible->compatible_id = $compatible->id;
            $new_compatible->device_id = $item;
            $new_compatible->save();
        }
	    Session::flash('success_message', 'Great! Compatible successfully updated!');
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
        $compatible = Compatible::find($id);

        $compatible->delete();
        Session::flash('success_message', 'Compatible successfully deleted!');
	    return redirect()->route('compatibles.index');

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
}
