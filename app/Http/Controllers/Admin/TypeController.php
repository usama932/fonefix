<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Type ';
	    return view('admin.types.index',compact('title'));
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
        if (Auth::user()->role == 2){
            $id = Auth::id();
        }
        elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = Type::where([['user_id',$id]])->count();
        }else{
            $totalData = Type::count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if($id){
                $users = Type::where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Type::where([['user_id',$id]])->count();
            }else{
                $users = Type::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Type::count();
            }

		}else{
			$search = $request->input('search.value');
			if ($id){
                $users = Type::where([
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
                $totalFiltered = Type::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->count();
            }else{
                $users = Type::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Type::where([
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
				$edit_url = route('types.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="Types[]" value="'.$r->id.'"><span></span></label></td>';
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
	    $title = 'Add New Type';
	    return view('admin.types.create',compact('title'));
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
	    $user = new Type();
	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Type has been saved successfully!');
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
	    $user = Type::find($id);
	    return view('admin.types.single', ['title' => 'Type detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = Type::findOrFail($request->id);


		return view('admin.types.detail', ['title' => 'Type Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Type::find($id);
	    return view('admin.types.edit', ['title' => 'Edit Type details'])->withUser($user);
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
	    $user = Type::find($id);
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

	    Session::flash('success_message', 'Great! Type successfully updated!');
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
	    $user = Type::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Type successfully deleted!');
	    return redirect()->route('types.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'Types' => 'required',

		]);
		foreach ($input['Types'] as $index => $id) {

			$user = Type::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Types successfully deleted!');
		return redirect()->back();

	}
}
