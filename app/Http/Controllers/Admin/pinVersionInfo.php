<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\pinVersionInfo as ModelsPinVersionInfo;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class pinVersionInfo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('admin.support.pinVersionInfo.index');
    }


    public function getversionInfo(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'description',
           
        );

        $totalData = ModelsPinVersionInfo::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $versionInfo = ModelsPinVersionInfo::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = ModelsPinVersionInfo::count();
        } else {
            $search = $request->input('search.value');
            $versionInfo = ModelsPinVersionInfo::where('title', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = ModelsPinVersionInfo::where('title', 'like', "%{$search}%")
                ->count();
        }


        $data = array();

        if ($versionInfo) {
            foreach ($versionInfo as $r) {
                $edit_url = route('pinVersionInfo.edit', $r->id);
                $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="categories[]" value="'.$r->id.'"><span></span></label></td>';
                $nestedData['title'] = $r->title;
                $nestedData['description'] = $r->description;
                
                $nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View pinInfo" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit pinInfo" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Vehicle" href="javascript:void(0)">
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.support.pinVersionInfo.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            

        ]);
        $data = $request->all();
        // dd($data);

        $pin = ModelsPinVersionInfo::create($data);
        Session::flash('success_message', 'Success! Pin version has been Added successfully!');
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
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pinVersion = ModelsPinVersionInfo::findOrFail($id);
        return view('admin.support.pinVersionInfo.edit', ['title' => 'Edit pinVersion', 'pinVersion' => $pinVersion]);
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
        $pinVersion = ModelsPinVersionInfo::findOrFail($id);
        $pinVersion->save();
        Session::flash('success_message', 'Success! Pin Vehicle has been updated successfully!');
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
        $vehicle = ModelsPinVersionInfo::findOrFail($id);
        $vehicle->delete();
        Session::flash('success_message', 'Success! pinVersion successfully deleted!');
        return redirect()->view('admin.support.pinVersionInfo.index');
    }
    public function pinVersionDetail(Request $request)
    {
        /*dd('working');*/
        $id = $request->id;
        $pinVersion = ModelsPinVersionInfo::findOrFail($id);
        // dd($vehicles);
        return view('admin.support.pinVersionInfo.show', ['title' => 'pinVersion Detail','pinVersion' => $pinVersion]);
    }
    public function DeleteSelectedpinVersion(Request $request)
    {
        $this->validate($request, [
            'vehicle' => 'required',
        ]);

        foreach ($request->input('vehicle') as $index => $vehicle_id) {
            $vehicle = ModelsPinVersionInfo::findOrFail($vehicle_id);
            $vehicle->delete();
        }
        Session::flash('success_message', 'Success! Vehicles successfully deleted!');
        return redirect()->back();

    }
}
