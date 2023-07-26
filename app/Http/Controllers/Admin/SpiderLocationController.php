<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpiderLocation;
use Illuminate\Http\Request;

class SpiderLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Spider Locations';
        return view('admin.spider_locations.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getspiderLocations(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'zip_code',
            2 => 'store_name',
            3 => 'store_address',
            4 => 'run_count',
            5 => 'updated_at'
        );

        $totalData = SpiderLocation::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = SpiderLocation::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = SpiderLocation::count();
        }else{
            $search = $request->input('search.value');
            $users = SpiderLocation::where([
                ['zip_code', 'like', "%{$search}%"],
            ])
               ->orWhere('store_name', 'like', "%{$search}%")
                ->orWhere('store_address','like',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SpiderLocation::where([
                ['store_name', 'like', "%{$search}%"],
            ])

                ->orWhere('updated_at','like',"%{$search}%")
                ->count();
        }


        $data = array();
        if($users){
            foreach($users as $r){
                $edit_url = route('categories.edit',$r->id);
                $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="category[]" value="'.$r->id.'"><span></span></label></td>';
                $nestedData['zip_code'] = $r->zip_code;
                $nestedData['store_name'] = $r->store_name;
                $nestedData['store_address'] = $r->store_address;
                $nestedData['run_count'] = $r->run_count;
                $nestedData['updated_at'] = date('d-m-Y', strtotime($r->updated_at));

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
