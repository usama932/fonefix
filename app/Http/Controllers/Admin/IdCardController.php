<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class IdCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'IdCard';
	    return view('admin.id-cards.index',compact('title'));
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
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = IdCard::where([['user_id',$id]])->count();
        }else{
            $totalData = IdCard::count();
        }
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
		    if ($id){
                $users = IdCard::where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = IdCard::where([['user_id',$id]])->count();
            }else{
                $users = IdCard::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = IdCard::count();
            }

		}else{
			$search = $request->input('search.value');
			if($id){
                $users = IdCard::where([
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
                $totalFiltered = IdCard::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->count();
            }else{
                $users = IdCard::where([
                    ['name', 'like', "%{$search}%"],
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = IdCard::where([
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
				$edit_url = route('id-cards.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="id_cards[]" value="'.$r->id.'"><span></span></label></td>';
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
	    $title = 'Add New IDCard';
	    return view('admin.id-cards.create',compact('title'));
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
	    $user = new IdCard();
	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! IdCard has been saved successfully!');
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
	    $user = IdCard::find($id);
	    return view('admin.id-cards.single', ['title' => 'IdCard detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = IdCard::findOrFail($request->id);
		return view('admin.id-cards.detail', ['title' => 'IdCard Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = IdCard::find($id);
	    return view('admin.id-cards.edit', ['title' => 'Edit Brand details'])->withUser($user);
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
	    $user = IdCard::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! IDCard successfully updated!');
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
	    $user = IdCard::find($id);
		    $user->delete();
		    Session::flash('success_message', 'IdCard successfully deleted!');
	    return redirect()->route('id-cards.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'id_cards' => 'required',

		]);
		foreach ($input['id_cards'] as $index => $id) {

			$user = IdCard::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'IdCards successfully deleted!');
		return redirect()->back();

	}
}
