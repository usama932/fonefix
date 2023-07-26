<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;

class BrandController extends Controller
{

    public function index()
    {
	    $title = 'Brands';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();

	    return view('admin.brands.index',compact('title','shops'));
    }


	public function getClients(Request $request){

		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'user_id',
			6 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = auth()->user()->id;
        }
        elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = Brand::where([['user_id',$id]])->count();
        }else{
            $totalData = Brand::count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if ($id){
                $users = Brand::with('shop')->where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->get();
                $totalFiltered = Brand::where([['user_id',$id]])->count();
            }else{
                $users = Brand::with('shop')->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->get();
                $totalFiltered = Brand::with('shop')->count();
            }

		}else{

			$search = $request->input('search.value');
			if ($id == null){

                $users = Brand::with('shop')->where([
                    ['name', 'like', "%{$search}%"],

                ])


                    ->offset($start)
                    ->limit($limit)
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                    })->get();

                $totalFiltered = Brand::with('shop')->where([
                    ['name', 'like', "%{$search}%"],

                ])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                })->count();
            }else{
                $users = Brand::with('shops')->where([
                    ['name', 'like', "%{$search}%"],
                ])


                    ->offset($start)
                    ->limit($limit);
                $totalFiltered = Brand::with('shops')->where([
                    ['name', 'like', "%{$search}%"],
                ])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                })->count();
            }

		}

		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('brands.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="brands[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
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
	    $title = 'Add New Client';
	    return view('admin.brands.create',compact('title'));
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
	    $user = new Brand();
	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Brand has been saved successfully!');
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
	    $user = Brand::find($id);
	    return view('admin.brands.single', ['title' => 'Brand detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = Brand::findOrFail($request->id);


		return view('admin.brands.detail', ['title' => 'Brand Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Brand::find($id);
	    return view('admin.brands.edit', ['title' => 'Edit Brand details'])->withUser($user);
    }

    public function import()
    {
        return view('admin.brands.import', ['title' => 'Client Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/brand.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'brand-sample.xlsx', $headers);
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
                    }
                    $user = Brand::where([["name",$line['Name']],["user_id",$user_id]])->first();
                    if (!$user){
                        $user = new Brand();
                    }
                    $user->name = $line['Name'];
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
        $brands = Brand::where("user_id",$user_id)->get();
        return Response::download((new FastExcel($brands))->export('brands.csv', function ($pass) {

            return [
                'Name' => $pass->name,
                'Created At' => $pass->created_at,
            ];

        }));
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
	    $user = Brand::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Brand successfully updated!');
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
	    $user = Brand::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Brand successfully deleted!');
	    return redirect()->route('brands.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'brands' => 'required',

		]);
		foreach ($input['brands'] as $index => $id) {

			$user = Brand::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Brand successfully deleted!');
		return redirect()->back();

	}
}
