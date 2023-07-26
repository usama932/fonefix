<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Zipcode;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Postal codes';
        return view('admin.postal_codes.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getpostalCodes(Request $request){
        $columns = array(
            0 => 'zip_code'
        );

        $totalData = Zipcode::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = Zipcode::offset($start)
                ->limit($limit)
                ->orderBy('id','desc')
                ->get();
            $totalFiltered = Zipcode::count();
        }else{
            $search = $request->input('search.value');
            $users = Zipcode::where([
                ['zip_code', 'like', "%{$search}%"],
            ])
               ->orWhere('zip_code', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('id','desc')
                ->get();
            $totalFiltered = Zipcode::where([
                ['zip_code', 'like', "%{$search}%"],
            ])

                ->orWhere('created_at','like',"%{$search}%")
                ->count();
        }


        $data = array();
        if($users){
            foreach($users as $r){
                $nestedData['zip_code'] = '<a href="javascript:void(0);" onclick="event.preventDefault();stores(' . $r->id . ');"> ' . $r->zip_code . '</a></div>';

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    
}

