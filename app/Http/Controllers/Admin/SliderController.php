<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Sliders';
        return view('admin.slider.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getsliderImages(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'image',
            2 => 'action'
        );

        $totalData = Slider::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = Slider::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = Slider::count();
        }else{
            $search = $request->input('search.value');
            $users = Slider::where([
                ['title', 'like', "%{$search}%"],
            ])
                ->orWhere('created_at','like',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Slider::where([
                ['title', 'like', "%{$search}%"],
            ])

                ->orWhere('created_at','like',"%{$search}%")
                ->count();
        }


        $data = array();

        if($users){
            foreach($users as $r){
                $edit_url = route('sliderImages.edit',$r->id);
                // $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="category[]" value="'.$r->id.'"><span></span></label></td>';

                $nestedData['image'] = '<td><img src="'. asset("uploads/$r->image").'" width="100"></td>';

                $nestedData['action'] = '
                                <div>
                                <td>

                                    <a title="Edit Slider" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Slider" href="javascript:void(0)">
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
        $title = 'Add New Slider';
        return view('admin.slider.create',compact('title'));
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
            'image' =>'required'

        ]);
        try {
            DB::beginTransaction();

            $input = $request->all();
            $user = new Slider();


            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $thumbnail = $file->getClientOriginalName('image');
                $thumbnail = rand() . $thumbnail;
                $request->file('image')->move($destinationPath, $thumbnail);
                $user->image = $thumbnail;
            }


            $user->save();


            Session::flash('success_message', 'Great! Slider has been saved successfully!');
            DB::commit();
            return redirect()->back();
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return redirect()->back()->with('error_message',$th->getMessage());

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
        $user = Slider::find($id);
        return view('admin.slider.edit', ['title' => 'Edit categories details'])->withUser($user);
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


        try {
            DB::beginTransaction();

            $user = Slider::find($id);
            $input = $request->all();

            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $this->validate($request, [
                        'image' => 'required|mimes:jpeg,png,jpg'
                    ]);
                    $file = $request->file('image');
                    $destinationPath = public_path('/uploads');

                    $imagePath = public_path('/uploads/'.$user->image);

                    if($user->image != '') {
                        if (File::exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    //$extension = $file->getClientOriginalExtension('logo');
                    $thumbnail = $file->getClientOriginalName('image');
                    $thumbnail = rand() . $thumbnail;
                    $request->file('image')->move($destinationPath, $thumbnail);
                    $user->image = $thumbnail;
                }
            }
            $user->save();

            Session::flash('success_message', 'Great! Slider successfully updated!');
            DB::commit();
            return redirect()->back();
        }
        catch (\Throwable $th)
        {

            DB::rollBack();
            return redirect()->back()->with('error_message',$th->getMessage());

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
            $user = Slider::find($id);

            $user->delete();
            Session::flash('success_message', 'Slider successfully deleted!');
            DB::commit();
            return redirect()->route('sliderImages.index');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return redirect()->back()->with('error_message',$th->getMessage());

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
            foreach ($input['sliderImage'] as $index => $id) {

                $user = Slider::find($id);

                $user->delete();

            }
            Session::flash('success_message', 'Slider Images successfully deleted!');
            DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());

        }
    }

}
