<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Provinces;
use App\Models\User;
use App\Models\UserProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Provinces';
	    return view('admin.provinces.index',compact('title'));
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
			2 => 'created_at',
			6 => 'action'
		);

		$totalData = Provinces::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
			$users = Provinces::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Provinces::count();
		}else{
			$search = $request->input('search.value');
			$users = Provinces::where([
				['name', 'like', "%{$search}%"],
			])

				->orWhere([
                    ['created_at', 'like', "%{$search}%"],
                ])
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Provinces::where([
				['name', 'like', "%{$search}%"],
			])

				->orWhere([
                    ['created_at', 'like', "%{$search}%"],
                ])
				->count();
		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('provinces.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="provinces[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;


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
	    $title = 'Add New Province';
	    $countries = Auth::user()->countries;
           if (Auth::user()->role == 3){
               $countries = User::findOrFail(Auth::user()->parent_id)->countries;
           }
	    return view('admin.provinces.create',['title' => $title, 'countries' => $countries]);
    }

    public function getPro(Request $request)
    {
	    $title = 'Add New Province';
        $provinces = Provinces::where("country_id", $request->id)->get();
	    return view('admin.provinces.pro',['title' => $title, 'provinces' => $provinces]);
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
		    'country' => 'required',
	    ]);

	    $input = $request->all();
	    $provinces = Provinces::where("country_id",$request->country)->get();
	    foreach ($provinces as $province){
            $res = array_key_exists("province$province->id", $input);
            $old_province = UserProvince::where([["user_id",Auth::id()],['province_id',$province->id],['country_id',$request->country]])->first();

            if (Auth::user()->role == 3){
                $old_province = UserProvince::where([["user_id",Auth::user()->parent_id],['province_id',$province->id],['country_id',$request->country]])->first();

            }

            if ($res == false) {
                if ($old_province){
                    $old_province->delete();
                }
            } else {
                if (!$old_province){
                    $new_province = new UserProvince();
                    $new_province->user_id = Auth::id();
                    if (Auth::user()->role == 3){
                        $new_province->user_id = Auth::user()->parent_id;
                    }
                    $new_province->country_id = $request->country;
                    $new_province->province_id = $province->id;
                    $new_province->save();
                }
            }

        }
//	    $user = new Provinces();
//	    $user->name = $input['name'];
//	    $user->country_id = $input['country'];
//        $res = array_key_exists('active', $input);
//        if ($res == false) {
//            $user->active = 0;
//        } else {
//            $user->active = 1;
//
//        }
//	    $user->save();

	    Session::flash('success_message', 'Great! Provinces has been saved successfully!');
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
	    $user = Provinces::find($id);
	    return view('admin.provinces.single', ['title' => 'Province detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = Provinces::findOrFail($request->id);
		return view('admin.provinces.detail', ['title' => 'Province Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Provinces::find($id);
	    $countries = Country::orderBy('name', 'asc')->pluck('name','id')->toArray();
	    return view('admin.provinces.edit', ['title' => 'Edit Province details',"countries" => $countries])->withUser($user);
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
	    $user = Provinces::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
	    $user->country_id = $input['country'];
        $res = array_key_exists('active', $input);
        if ($res == false) {
            $user->active = 0;
        } else {
            $user->active = 1;

        }
	    $user->save();

	    Session::flash('success_message', 'Great! Province successfully updated!');
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
	    $user = Provinces::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Province successfully deleted!');
	    return redirect()->route('provinces.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'provinces' => 'required',

		]);
		foreach ($input['provinces'] as $index => $id) {

			$user = Provinces::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Provinces successfully deleted!');
		return redirect()->back();

	}
}
