<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StripePlan;
use Illuminate\Http\Request;
use Stripe\Plan;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
class PlanController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey('sk_test_51N5OM8JLgrmN3ahkO2Zb0vhHlxFijimGxqRCGHHearK2Xof0I5aaUDFkj4vTwe8m5xF6GfOkThEmYYKE0jLtOEy900EsApyfG7');
        
    }
    public function index()
    {

        $title = 'Plans';
        return view('admin.plans.index',compact('title'));
    }

    public function getPlans(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'amount',
            3 => 'action'
        );

        $totalData = StripePlan::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $records = StripePlan::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = StripePlan::count();
        }else{
            $search = $request->input('search.value');
            $records = StripePlan::where([
                ['title', 'like', "%{$search}%"],
            ])
                ->orWhere('created_at','like',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = StripePlan::where([
                ['title', 'like', "%{$search}%"],
            ])

                ->orWhere('created_at','like',"%{$search}%")
                ->count();
        }


        $data = array();
        if($records){
            foreach($records as $r){
                $edit_url = route('plans.edit',$r->id);
                $nestedData['name'] = $r->name;
                $nestedData['amount'] = "$".$r->amount;
                $nestedData['action'] = '
                                <div>
                                <td>
                                    
                                    <a title="Edit Customer" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Plan" href="javascript:void(0)">
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
    { $title ="Plans";
        $currencies = [
            ['name'=>'AUD'],
            ['name'=>'CAD'],
            ['name'=>'DKK'],
            ['name'=>'EUR'],
            ['name'=>'HKD'],
            ['name'=>'JPY'],
            ['name'=>'NOK'],
            ['name'=>'NZD'],
            ['name'=>'GBP'],
            ['name'=>'SGD'],
            ['name'=>'SEK'],
            ['name'=>'CHF'],
            ['name'=>'USD']
        ];
        return view('admin.plans.create',['title'=>$title,'currencies'=>$currencies]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'amount' => 'required',
            'interval' => 'required',
            'currency' => 'required',
            'nickname' => 'required',

        ]);
        $plan_id = Str::random(16);
      
        try{
            $plan = Plan::retrieve($request->name);
       }
       catch(\Exception $e){
        if($e->getMessage() == "No such plan: '$request->name'" ){
            $plan = Plan::create([
                'amount' => $request->amount*100,
                'currency' => $request->currency,
                'interval' => $request->interval,
                'product' => [
                    'name' => $request->name
                ],
                'nickname' => $request->nickname,
                'id' => $plan_id,
            ]);
       

            StripePlan::create(['name'=>$request->name,'amount'=>$request->amount,'interval'=>$request->interval,'currency'=>$request->currency,'nickname'=>$request->nickname,'stripe_plan_id'=>$plan_id]);
            Session::flash('success_message', 'Great! Plan has been created successfully!');
            return redirect()->back();
        }
            
       }
       Session::flash('error_message', 'Great! Plan has been created successfully!');
       return redirect()->back();
        
    }

    public function edit($id){
        $plan = StripePlan::find($id);
        $currencies = [
            ['name'=>'AUD'],
            ['name'=>'CAD'],
            ['name'=>'DKK'],
            ['name'=>'EUR'],
            ['name'=>'HKD'],
            ['name'=>'JPY'],
            ['name'=>'NOK'],
            ['name'=>'NZD'],
            ['name'=>'GBP'],
            ['name'=>'SGD'],
            ['name'=>'SEK'],
            ['name'=>'CHF'],
            ['name'=>'USD']
        ];
        return view('admin.plans.edit', ['title' => 'Edit plan details','plan'=>$plan,'currencies'=>$currencies]);
       
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'interval' => 'required',
            'currency' => 'required',
            'nickname' => 'required',

        ]);
        $database_plan = StripePlan::find($id);
        try{
            $plan = Plan::retrieve($database_plan->stripe_plan_id);

            dd($plan);
       }
       catch(\Exception $e){
        dd($e->getMessage());
       }
       $plan->metadata['updated_by'] = auth()->user()->name;
       $plan->metadata['update_date'] = date('Y-m-d H:i:s');
       $plan->nickname = $request->nickname;
   
       
       $plan->save();

       $database_plan->name = $request->name;
       $database_plan->amount = $request->amount;
       $database_plan->interval = $request->interval;
       $database_plan->currency = $request->currency;
       $database_plan->nickname = $request->nickname;
       if($database_plan->save()){
        Session::flash('success_message', 'Great! plan successfully updated!');
        return redirect()->back();
       }
       dd($plan);
        
    }

    public function destroy($id)
    {
        $dbPlan = StripePlan::find($id);
        try {
            $plan = Plan::retrieve($dbPlan->stripe_plan_id);
            if($plan->delete()){
                $dbPlan->delete(); 
            }

            Session::flash('success_message', 'Product successfully deleted!');
            return redirect()->route('plans.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', $e->getMessage());
        }
    }
}
