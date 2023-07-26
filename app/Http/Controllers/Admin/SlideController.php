<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Slider;

class SlideController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

	    $this->validate($request, [
		    'title' => 'required|max:255',
            'image' => 'required'
	    ]);
        $slider = new Slider();

        $slider->title = $request->title;
        $slider->button_text = $request->button_text ?? '';
        $slider->button_url = $request->button_url ?? '';
        $slider->user_id = auth()->user()->id;
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $destinationPath = public_path('/uploads');
            //$extension = $file->getClientOriginalExtension('logo');
            $thumbnail = $file->getClientOriginalName('image');
            $thumbnail = rand() . $thumbnail;
            $request->file('image')->move($destinationPath, $thumbnail);
            $slider->image = $thumbnail;
        }

        $slider->save();
        Session::flash('success_message', 'Great! Slider has been saved successfully!');
        return redirect()->back();
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
		    'title' => 'required|max:255',
            'image' => 'required'
	    ]);
        $slider =  Slider::find($id);

        $slider->title = $request->title;
        $slider->button_text = $request->button_text ?? '';
        $slider->button_url = $request->button_url ?? '';
        $slider->user_id = auth()->user()->id;
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $destinationPath = public_path('/uploads');
            //$extension = $file->getClientOriginalExtension('logo');
            $thumbnail = $file->getClientOriginalName('image');
            $thumbnail = rand() . $thumbnail;
            $request->file('image')->move($destinationPath, $thumbnail);
            $slider->image = $thumbnail;
        }
        else{
            $slider->image = $slider->image;
        }

        $slider->save();
        Session::flash('success_message', 'Great! Slider has been saved successfully!');
        return redirect()->back();
    }


    public function destroy($id)
    {
        //
    }
    public function delete_Slide($id){
        $slider = Slider::find($id);
        if($slider){
            $slider->delete();
            Session::flash('success_message', 'Great! Slider has been remove successfully!');
            return redirect()->back();
        }
        Session::flash('success_message', 'Great! Slider Not Found!');
        return redirect()->back();
    }
}
