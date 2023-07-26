<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Categories';
        return view('admin.categories.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getCategories(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'created_at',
            3 => 'action'
        );

        $totalData = Category::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = Category::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = Category::count();
        }else{
            $search = $request->input('search.value');
            $users = Category::where([
                ['title', 'like', "%{$search}%"],
            ])
                ->orWhere('created_at','like',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Category::where([
                ['title', 'like', "%{$search}%"],
            ])

                ->orWhere('created_at','like',"%{$search}%")
                ->count();
        }


        $data = array();
        if($users){
            foreach($users as $r){
                $edit_url = route('categories.edit',$r->id);
                $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="category[]" value="'.$r->id.'"><span></span></label></td>';
                $nestedData['title'] = $r->title;
                $nestedData['image'] = '<img src="'.asset('uploads/'.$r->image).'" >';
                $nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Category" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Customer" class="btn btn-sm btn-clean btn-icon"
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
        $title = 'Add New Category';
        return view('admin.categories.create',compact('title'));
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
            'title' => 'required',

        ]);
        try {
            DB::beginTransaction();

            $input = $request->all();
            $user = new Category();
            $user->title = $input['title'];
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $this->validate($request, [
                        'image' => 'required|mimes:jpeg,png,jpg'
                    ]);
                    $file = $request->file('image');
                    $destinationPath = public_path('/uploads');
                    //$extension = $file->getClientOriginalExtension('logo');
                    $thumbnail = $file->getClientOriginalName('image');
                    $thumbnail = rand() . $thumbnail;
                    $request->file('image')->move($destinationPath, $thumbnail);
                    $user->image = $thumbnail;
                }
            }
            $user->save();


            Session::flash('success_message', 'Great! Category has been saved successfully!');
            DB::commit();
            return redirect()->back();
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return redirect()->back()->with('error',$th->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('admin.categories.single', ['title' => 'Category detail', 'category' => $category]);
    }

    public function categoryDetail(Request $request)
    {

        $user = Category::findOrFail($request->id);


        return view('admin.categories.detail', ['title' => 'Category Detail', 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Category::find($id);
        return view('admin.categories.edit', ['title' => 'Edit categories details'])->withUser($user);
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

        $this->validate($request, [
            'title' => 'required',

        ]);
        try {
            DB::beginTransaction();

            $user = Category::find($id);
            $input = $request->all();
            $user->title = $input['title'];
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $this->validate($request, [
                        'image' => 'required|mimes:jpeg,png,jpg'
                    ]);
                    $file = $request->file('image');
                    $destinationPath = public_path('/uploads');
                    $imagePath = public_path('/uploads/'.$user->image);

                    if(File::exists($imagePath)){
                        unlink($imagePath);
                    }
                    //$extension = $file->getClientOriginalExtension('logo');
                    $thumbnail = $file->getClientOriginalName('image');
                    $thumbnail = rand() . $thumbnail;
                    $request->file('image')->move($destinationPath, $thumbnail);
                    $user->image = $thumbnail;
                }
            }
        $user->save();

        Session::flash('success_message', 'Great! Category successfully updated!');
            DB::commit();
            return redirect()->back();
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return redirect()->back()->with('error',$th->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = Category::find($id);

            $user->delete();
            Session::flash('success_message', 'Category successfully deleted!');
            DB::commit();
        return redirect()->route('categories.index');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return redirect()->back()->with('error',$th->getMessage());

        }
    }
    public function deleteSelectedCategories(Request $request)
    {

        $this->validate($request, [
            'category' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $input = $request->all();
            foreach ($input['category'] as $index => $id) {

                $user = Category::find($id);

                $user->delete();

            }
            Session::flash('success_message', 'category successfully deleted!');
            DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());

        }
    }
}
