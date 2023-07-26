<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Courier;
use App\Models\Job;
use App\Models\Product;
use App\Models\Status;
use App\Models\UsePart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $title = 'Reports ';
        if  (Auth::user()->role == 2){
            $products = Product::where("user_id",Auth::id())->get();
        }elseif (Auth::user()->role == 3){
            $products = Product::where("user_id",Auth::user()->parent_id)->get();
        }else{
            $products = Product::all();
        }
	    return view('admin.reports.index',['title'=>$title, 'products'=>$products, 'data'=>null]);
    }

    public function search(Request $request)
    {
	    $title = 'Reports ';
        if  (Auth::user()->role == 2){
            $products = Product::where("user_id",Auth::id())->get();
        }elseif (Auth::user()->role == 3){

            $products = Product::where("user_id",Auth::user()->parent_id)->get();
        }else{
            $products = Product::all();
        }
	    if($request->product){
	        $data = UsePart::whereBetween("created_at",[$request->from,$request->to])->where("product_id", $request->product)->get();
        }else{
            if  (Auth::user()->role == 2){
                $id = Auth::user()->id;
                $data = UsePart::whereBetween("use_parts.created_at",[$request->from,$request->to])
                    ->join('jobs', function ($join) use ($id) {
                        $join->on('jobs.id', '=', 'use_parts.job_id')
                            ->where('jobs.user_id', '=', $id);
                    })
                    ->select(
                        'use_parts.*'
                    )
                    ->get();
            }elseif (Auth::user()->role == 3){
                $id = Auth::user()->parent_id;
                $data = UsePart::whereBetween("use_parts.created_at",[$request->from,$request->to])
                    ->join('jobs', function ($join) use ($id) {
                        $join->on('jobs.id', '=', 'use_parts.job_id')
                            ->where('jobs.user_id', '=', $id);
                    })
                    ->select(
                        'use_parts.*'
                    )
                    ->get();
            }else{
                $data = UsePart::whereBetween("created_at",[$request->from,$request->to])->get();
            }
        }

	    return view('admin.reports.index',['title'=>$title, 'products'=>$products, 'data'=>$data]);
    }

    public function searchJobs(Request $request)
    {
	    $title = 'Reports Jobs';
        if ($request->from){
            if  (Auth::user()->role == 2){
                $id = Auth::user()->id;
                $data = Job::whereBetween("created_at",[$request->from,$request->to])
                    ->where("user_id",$id)
                    ->get();
            }elseif (Auth::user()->role == 3){
                $id = Auth::user()->parent_id;
                $data = Job::whereBetween("created_at",[$request->from,$request->to])
                    ->where("user_id",$id)
                    ->get();
            }else{
                $data = Job::whereBetween("created_at",[$request->from,$request->to])->get();
            }
        }else{
            $data = null;
        }
	    return view('admin.reports.jobs',['title'=>$title,  'data'=>$data]);
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
        if  (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Status::where([['user_id',$id]])->count();
        }else{
            $totalData = Status::count();
        }


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            if ($id){
                $users = Status::where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Status::where([['user_id',$id]])->count();
            }else{
                $users = Status::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Status::count();
            }

        }else{
            $search = $request->input('search.value');

            if ($id){
                $users = Status::where([
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
                $totalFiltered = Status::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])

                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
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
                    ->get();
                $totalFiltered = Status::where([
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
				$edit_url = route('statuses.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="statuses[]" value="'.$r->id.'"><span></span></label></td>';
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
	    $title = 'Add New Status';
	    return view('admin.statuses.create',compact('title'));
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
	    $user = new Status();
	    $user->name = $input['name'];
	    $user->color = $input['color'];
	    $user->email_subject = $input['email_subject'];
	    $user->sms_template = $input['sms_template'];
	    $user->email_body = $input['email_body'];
	    $user->sort_order = $input['sort_order'];
        if (Auth::user()->role == 2){
            $user->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
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
	    return view('admin.statuses.edit', ['title' => 'Edit Status details'])->withUser($user);
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
	    $user = Status::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
	    ]);
	    $input = $request->all();
	    $user->name = $input['name'];
        $user->color = $input['color'];
        $user->email_subject = $input['email_subject'];
        $user->sms_template = $input['sms_template'];
        $user->email_body = $input['email_body'];
        $user->sort_order = $input['sort_order'];
        if (Auth::user()->role == 2){
            $user->user_id =  Auth::id();
        }
        if (Auth::user()->role == 3){
            $user->user_id =  Auth::user()->parent_id;
        }
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
}
