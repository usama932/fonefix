<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\Provinces;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

	    $title = 'Categories';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();


	    return view('admin.categories.index',["title"=>$title,'shops'=>$shops]);
    }



	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'created_at',
			6 => 'action'
		);

		$totalData = Category::with('shop')->count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
            if(Auth::user()->role == 2){

                $users = Category::where('user_id',Auth::id())->with('shop')->when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('user_id', $request->shop_id);
                                    })->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                $totalFiltered = Category::with('shop')->when($request->has('shop_id'), function ($query) use ($request) {
                                                $query->where('shop_id', $request->shop_id);
                                            })->count();
            }
            elseif(Auth::user()->role == 3){

                $users = Category::with('shop')->when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('shop_id', $request->shop_id);
                                    })->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                $totalFiltered = Category::where('shop_id',Auth::user()->parent_id)->when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('shop_id', $request->shop_id);
                                        })->with('shop')->count();
            }
            else{

                $users = Category::with('shop')->when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('shop_id', $request->shop_id);
                                    })->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                $totalFiltered = Category::with('shop')->when($request->has('shop_id'), function ($query) use ($request) {
                                            $query->where('shop_id', $request->shop_id);
                                        })->count();
            }
		}
        else{
                $search = $request->input('search.value');
                $users = Category::with('shop')
                                ->when($request->has('shop_id'), function ($query) use ($request) {
                                    $query->where('shop_id', $request->shop_id);
                                })->where([
                                    ['name', 'like', "%{$search}%"],
                                ])

                                ->orWhere([
                                    ['created_at', 'like', "%{$search}%"],
                                ])
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();
                $totalFiltered = Category::with('shop')
                                            ->when($request->has('shop_id'), function ($query) use ($request) {
                                                $query->where('shop_id', $request->shop_id);
                                            })->where([
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
				$edit_url = route('categories.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="categories[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name ?? '';
                if(Auth::user()->role == 1){
                    $nestedData['shop'] = $r->shop->name ?? "Not Assign";
                }


				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Category" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Category" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Category" href="javascript:void(0)">
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
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->pluck('name','id');

        if (Auth::user()->role == 2){
            $categories = Category::where([["parent_id","=", null],['user_id', Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $categories = Category::where([["parent_id","=", null],['user_id', Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $categories = Category::where("parent_id","=", null)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }
	    return view('admin.categories.create',['title' => $title, 'categories' => $categories,'shops'=>$shops]);
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
            'shop_id' => 'required',
	    ]);
       // dd($request->all());

	    $input = $request->all();
	    $user = new Category();
	    $user->name = $input['name'];
        if (Auth::user()->role == 1){
            $user->shop_id = $input['shop_id'];

        }
        else{
            $user->user_id = Auth::id();
        }

	    if ($request->category){
            $user->parent_id = $input['category'];

        }
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Category has been saved successfully!');
	    $user->save();
	    return redirect()->route('categories.index');
    }


    public function show($id)
    {
	    $user = Category::find($id);
	    return view('admin.categories.single', ['title' => 'Province detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = Category::findOrFail($request->id);
		return view('admin.categories.detail', ['title' => 'Province Detail', 'user' => $user]);
	}

    public function edit($id)
    {
	    $user = Category::find($id);
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->pluck('name','id');
        if (Auth::user()->role == 2){
            $categories = Category::where([["parent_id","=", null],['user_id', Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $categories = Category::where([["parent_id","=", null],['user_id', Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $categories = Category::where("parent_id","=", null)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }
	    return view('admin.categories.edit', ['title' => 'Edit Province details',"categories" => $categories,"shops" => $shops])->withUser($user);
    }


    public function update(Request $request, $id)
    {
	    $user = Category::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
            'shop_id' => 'required',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
        if (Auth::user()->role == 1){
            $user->shop_id = $input['shop_id'];

        }
        else{
            $user->user_id = Auth::id();
        }
        if ($request->category){
            $user->parent_id = $input['category'];

        }
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Category successfully updated!');
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
	    $user = Category::find($id);
		    $user->delete();
		    Session::flash('success_message', 'Province successfully deleted!');
	    return redirect()->route('categories.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'categories' => 'required',

		]);
		foreach ($input['categories'] as $index => $id) {

			$user = Category::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Category successfully deleted!');
		return redirect()->back();

	}
}
