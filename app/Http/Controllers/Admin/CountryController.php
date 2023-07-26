<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Provinces;
use App\Models\UserProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\User;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Country';
	    return view('admin.countries.index',compact('title'));
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

		$totalData = Country::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
			$users = Country::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Country::count();
		}else{
			$search = $request->input('search.value');
			$users = Country::where([
				['name', 'like', "%{$search}%"],
			])

				->orWhere([
                    ['created_at', 'like', "%{$search}%"],
                ])
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Country::where([
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
				$edit_url = route('countries.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="countries[]" value="'.$r->id.'"><span></span></label></td>';
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
	    $title = 'Add New Country';
	    $countries =  Country::orderBy('name', 'asc')->pluck('name','id')->toArray();
	    return view('admin.countries.create',["countries" => $countries,"title" => $title]);
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
		    'countries' => 'required',
	    ]);
        $user =  Auth::user();

        if (Auth::user()->role == 3){
            $user =  User::findOrFail(Auth::user()->parent_id);
        }
        if(isset($request->countries)){
            $user->countries()->sync($request->countries);
            foreach ($user->countries as $country) {
                foreach ($country->provinces as $province) {
                    $pre_province  = UserProvince::where([['province_id', $province->id],["user_id",$user->id]])->first();
                    if(!$pre_province) {
                        $pre_province = new UserProvince();
                    }
                    $pre_province->user_id = $user->id;
                    $pre_province->country_id = $country->id;
                    $pre_province->province_id = $province->id;
                    $pre_province->save();
                }
            }
        }else {
            $user->countries()->sync(array());
        }
//	    $input = $request->all();
//	    $user = new Country();
//	    $user->name = $input['name'];
//
//	    $user->save();

	    Session::flash('success_message', 'Great! Countries has been saved successfully!');
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
	    $user = Country::find($id);
	    return view('admin.countries.single', ['title' => 'Country detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = Country::findOrFail($request->id);


		return view('admin.countries.detail', ['title' => 'Country Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Country::find($id);
	    return view('admin.countries.edit', ['title' => 'Edit Country details'])->withUser($user);
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
	    $user = Country::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];

	    $user->save();

	    Session::flash('success_message', 'Great! Country successfully updated!');
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
	    $user = Country::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Country successfully deleted!');
	    return redirect()->route('countries.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'countries' => 'required',

		]);
		foreach ($input['countries'] as $index => $id) {

			$user = Country::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Countries successfully deleted!');
		return redirect()->back();

	}
}
