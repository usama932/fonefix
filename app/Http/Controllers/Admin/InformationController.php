<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Session;

class InformationController extends Controller
{

    public function index()
    {
        $title = 'Manage Informations';
        return view('admin.informations.index',compact('title'));
    }

    public function getInformations(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'title',
			3 => 'created_at',
			4 => 'action'
		);

		$totalData = Image::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
			$Images = Image::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Image::count();
		}else{
			$search = $request->input('search.value');
			$Images = Image::where([
				['title', 'like', "%{$search}%"],
			])

				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Image::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")

				->orWhere('created_at','like',"%{$search}%")
				->count();
		}


		$data = array();

		if($Images){
			foreach($Images as $r){
				$edit_url = route('informations.edit',$r->id);
				$nestedData['id'] = '<td><div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="informations[]" value="'.$r->id.'"></div></td>';
				$nestedData['title'] = $r->title;
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View User" href="javascript:void(0)">
                                    <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit User" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete User" href="javascript:void(0)">
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

    public function informationDetail(Request $request){

        $information = Image::findOrFail($request->id);
		return view('admin.informations.detail', ['title' => 'information Detail', 'information' => $information]);
    }
    public function create()
    {
        $title = "Create Informations";
        $shops = User::where(['role'=> '2', 'active' => '1', 'is_admin' => '1'])->get();
        return view('admin.informations.create',compact('title','shops'));
     }


    public function store(Request $request)
    {
        $this->validate($request, [
		    'title' => 'required|max:255',
            'image_1' =>  'required|mimes:jpeg,png,jpg,gif',
            'image_2' =>  'required|mimes:jpeg,png,jpg,gif',
            'image_3' =>  'required|mimes:jpeg,png,jpg,gif',
            'image_4' =>  'required|mimes:jpeg,png,jpg,gif',
            'shops' => 'required',
	    ]);

        if ($request->hasFile('image_1')) {
            if ($request->file('image_1')->isValid()) {

                $file = $request->file('image_1');
                $destinationPath = public_path('/uploads');
                $thumbnail_1 = $file->getClientOriginalName('image_1');
                $thumbnail_1 = rand() . $thumbnail_1;
                $request->file('image_1')->move($destinationPath, $thumbnail_1);

            }
        }
        if ($request->hasFile('image_2')) {
            if ($request->file('image_2')->isValid()) {

                $file = $request->file('image_2');
                $destinationPath = public_path('/uploads');
                $thumbnail_2 = $file->getClientOriginalName('image_2');
                $thumbnail_2 = rand() . $thumbnail_2;
                $request->file('image_2')->move($destinationPath, $thumbnail_2);

            }
        }
        if ($request->hasFile('image_3')) {
            if ($request->file('image_3')->isValid()) {

                $file = $request->file('image_3');
                $destinationPath = public_path('/uploads');
                $thumbnail_3 = $file->getClientOriginalName('image_3');
                $thumbnail_3 = rand() . $thumbnail_3;
                $request->file('image_3')->move($destinationPath, $thumbnail_3);

            }
        }
        if ($request->hasFile('image_4')) {
            if ($request->file('image_4')->isValid()) {

                $file = $request->file('image_4');
                $destinationPath = public_path('/uploads');
                $thumbnail_4 = $file->getClientOriginalName('image_4');
                $thumbnail_4 = rand() . $thumbnail_4;
                $request->file('image_4')->move($destinationPath, $thumbnail_4);

            }
        }
        $information = Image::create([
            'title' => $request->title,
            'image_1' => $thumbnail_1,
            'image_2' => $thumbnail_2,
            'image_3' => $thumbnail_3,
            'image_4' => $thumbnail_4,
        ]);
        $shops = $request->shops;
        $information->users()->sync($shops);
        Session::flash('success_message', 'Information saved successfully!');
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $title = "Edit Informations";
        $shops = User::where(['role'=> '2', 'active' => '1', 'is_admin' => '1'])->get();
        $information = Image::where('id',$id)->with('users')->first();
        $users = $information->users->pluck('name')->toArray();
        return view('admin.informations.edit',compact('title','shops','information','users'));
    }


    public function update(Request $request, $id)
    {

        $this->validate($request, [
		    'title' => 'required|max:255',
            'image_1' =>  'mimes:jpeg,png,jpg,gif',
            'image_2' =>  'mimes:jpeg,png,jpg,gif',
            'image_3' =>  'mimes:jpeg,png,jpg,gif',
            'image_4' =>  'mimes:jpeg,png,jpg,gif',
            'shops' => 'required',
	    ]);

        if ($request->hasFile('image_1')) {
            if ($request->file('image_1')->isValid()) {

                $file = $request->file('image_1');
                $destinationPath = public_path('/uploads');
                $thumbnail_1 = $file->getClientOriginalName('image_1');
                $thumbnail_1 = rand() . $thumbnail_1;
                $request->file('image_1')->move($destinationPath, $thumbnail_1);

            }
        }
        if ($request->hasFile('image_2')) {
            if ($request->file('image_2')->isValid()) {

                $file = $request->file('image_2');
                $destinationPath = public_path('/uploads');
                $thumbnail_2 = $file->getClientOriginalName('image_2');
                $thumbnail_2 = rand() . $thumbnail_2;
                $request->file('image_2')->move($destinationPath, $thumbnail_2);

            }
        }
        if ($request->hasFile('image_3')) {
            if ($request->file('image_3')->isValid()) {

                $file = $request->file('image_3');
                $destinationPath = public_path('/uploads');
                $thumbnail_3 = $file->getClientOriginalName('image_3');
                $thumbnail_3 = rand() . $thumbnail_3;
                $request->file('image_3')->move($destinationPath, $thumbnail_3);

            }
        }
        if ($request->hasFile('image_4')) {
            if ($request->file('image_4')->isValid()) {

                $file = $request->file('image_4');
                $destinationPath = public_path('/uploads');
                $thumbnail_4 = $file->getClientOriginalName('image_4');
                $thumbnail_4 = rand() . $thumbnail_4;
                $request->file('image_4')->move($destinationPath, $thumbnail_4);

            }
        }
        $information = Image::where('id',$id)->first();
        $image = Image::where('id',$id)->update([
            'title'   => $request->title ??  $information->title ,
            'image_1' => $thumbnail_1 ?? $information->image_1 ,
            'image_2' => $thumbnail_2 ?? $information->image_2 ,
            'image_3' => $thumbnail_3 ?? $information->image_3 ,
            'image_4' => $thumbnail_4 ?? $information->image_4 ,
        ]);

        $shops = $request->shops;
        $information->users()->sync($shops);
        Session::flash('success_message', 'Information update successfully!');
        return redirect()->back();
    }


    public function destroy($id)
    {


        $information = Image::find($id);
        $information->users()->detach();
        $information->delete();

        Session::flash('success_message', 'Information delete successfully!');
        return redirect()->back();
    }
    public function deleteSelectedInformations(Request $request){

        $input = $request->all();
		$this->validate($request, [
			'informations' => 'required',

		]);
		foreach ($input['informations'] as $index => $id) {

            $information = Image::find($id);
            $information->users()->detach();
            $information->delete();


		}
		Session::flash('success_message', 'Information successfully deleted!');
		return redirect()->back();
    }
}
