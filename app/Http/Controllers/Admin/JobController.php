<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Device;
use App\Models\IdCard;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\JobPreRepair;
use App\Models\JobSetting;
use App\Models\Product;
use App\Models\Province;
use App\Models\Setting;
use App\Models\ShopUser;
use App\Models\Status;
use App\Models\UsePart;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Statususe;
use PDF;
use Auth;
use Mail;
use Twilio\Rest\Client;
use Response;

use App\Mail\DynamicSMTPMail;
use Illuminate\Support\Facades\Config;
use Swift_Mailer;
use Swift_SmtpTransport;

class JobController extends Controller
{

    public function index()
    {
	    $title = 'Jobs';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
        $informations = User::where('id',auth()->user()->id)
                                ->where('role',2)
                                ->with(['images'=> function ($query) {
                        $query->where('user_id', auth()->user()->id);
                        }])->first();

	    return view('admin.jobs.index',compact('title','informations','shops'));
    }


	public function getClients(Request $request){
       // dd($request->all());
		$columns = array(
			1 => 'id',
            2 => 'action',
			3 => 'service_type',
			4 => 'expected_delivery',
			5 => 'id',
            6 => 'status',
            7 => 'customer_id',
            8 => 'cost',
            9 => 'brand_id',
//            10 => 'device_id',
            11 => 'device_id',
			12 => 'serial_number',
            13 => 'user_id',
            14 => 'user_id',
            15 => 'created_at',
        );
        if (Auth::user()->role == 2){
            $id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }

        if ($id){
            $totalData = Job::where([['user_id',$id]])->count();
        }else{
            $totalData = Job::count();
        }

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){

            if ($id){

                $users = Job::where([['user_id',$id]])->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
                $totalFiltered = Job::where([['user_id',$id]])->count();
            }else{
                $users = Job::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
                $totalFiltered = Job::count();
            }


		}else{
            $search = $request->input('search.value');

            if ($id){
                $users = Job::where([

                    ['user_id',$id]
                ])

                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('job_sheet_number', 'like', "%{$search}%")
                    ->orWhere([
                        ['id', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir);
                $totalFiltered = Job::where([

                    ['user_id',$id]
                ])
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])

                    ->orWhere('job_sheet_number', 'like', "%{$search}%")
                    ->orWhere([
                        ['id', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->count();

            }else{
                $users = Job::orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('created_at','like',"%{$search}%")

                    ->orWhere('job_sheet_number', 'like', "%{$search}%")
                    ->orWhere('id','like',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir);
                $totalFiltered = Job::where('serial_number', 'like', "%{$search}%")
                    ->orWhere('job_sheet_number', 'like', "%{$search}%")

                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('created_at','like',"%{$search}%")
                    ->count();

            }

		}

        if(!empty($request->shop_id)){
            $shop_id = $request->shop_id;
            $users = $users->with('parts','stat')->whereHas('shop', function ( $query) use ($shop_id) {
                $query->where('id',$shop_id);
               })->latest()->get();
        }
        else{
            $users = $users->with('parts','stat')->latest()->get();
        }
		$data = array();

		if($users){
			foreach($users as $r){
                $price = 0;
				$edit_url = route('jobs.edit',$r->id);
				$parts_url = route('job-parts',$r->id);
				$show_url = route('jobs.show',$r->id);
				$invoice_url = route('job-invoice',$r->id);
				$nestedData['check'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="Jobs[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['id'] = $r->id;
				$nestedData['serial_number'] = $r->serial_number;
				$nestedData['expected_delivery'] = $r->expected_delivery;
				$nestedData['job_sheet_number'] = $r->job_sheet_number;
				if ($r->invoice){
                    $nestedData['invoice_number'] = $r->invoice->number;
                }else{
                    $nestedData['invoice_number'] = "";
                }
				$nestedData['cost'] = $r->cost;
                foreach($r->parts as $parts){
                    $p  =$parts->quantity * $parts->amount;
                    $price = $price + $p;

                }
                $discount = $r->invoice->discount ?? '0';
                $nestedData['parts_price'] = $price;
                $nestedData['invoiceprice'] = $price - $discount;
				$nestedData['customer_name'] = $r->customer->name;
				$nestedData['shop_name'] = $r->shop->name;
				$nestedData['brand_name'] = $r->brand->name;
				$nestedData['device_name'] = $r->device->name;
                if($r->device->type == 1){
                    $nestedData['device'] = 'Mobile';
                }elseif($r->device->type == 2){
                    $nestedData['device'] = 'Laptop';
                }
                if($r->service_type == 1){
                    $nestedData['service_type'] = 'Carry In';
                }elseif($r->service_type == 2){
                    $nestedData['service_type'] = 'Pick Up';
                }elseif($r->service_type == 3){
                    $nestedData['service_type'] = 'On Site';
                }else{
                    $nestedData['service_type'] = 'Courier';
                }
                if($r->status_id){
                    $color = $r->stat->color ?? '';

                        $nestedData['status'] = $r->stat->name ?? 'Not Assign';



                }else{
                    $nestedData['status'] = '<span class="label label-danger label-inline mr-2">Nil</span>';
                }
                if ($r->invoice) {
                    if(!$r->credit){
                        if (!$r->notPaid) {
                            $nestedData['paid'] = '<span class="label label-success label-inline mr-2" >Paid</span>';
                        } else {
                            $nestedData['paid'] = '<span class="label label-danger label-inline mr-2">Unpaid</span>';
                        }
                    }else {
                        $nestedData['paid'] = '<span class="label label-danger label-inline mr-2">Unpaid</span>';
                    }

                }else{
                    $nestedData['paid'] = '<span class="label label-danger label-inline mr-2">Unpaid</span>';
                }
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));

                $user = Auth::user();
                if($user->role == 1){
                    $edit = 1;
                    $del = 1;
                    $view = 1;
                    $invoice = 1;
                    $parts = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                    $invoice = 1;
                    $parts = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->job_view;
                    $edit = $user->permission->job_edit;
                    $del = $user->permission->job_delete;
                    $invoice = $user->permission->invoice_add;
                    $parts = $user->permission->job_add_parts;
                }
                $view_link = ' <a title="Show" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$show_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $parts_link = ' <a title="Add parts" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$parts_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-plus"></i>
                                    </a>';
                if(!$parts){$parts_link = '';}
//                    if($r->invoice){
//                        $invoice_url = route("invoices.show", $r->invoice->id);
//                    }
                    $invoice_link = ' <a title="Show Invoice " class="btn btn-sm btn-clean btn-icon"
                                           href="'.$invoice_url.'">
                                           <i class="icon-1x text-dark-50 flaticon-file-1"></i>
                                        </a>';
                if(!$invoice){$invoice_link = '';}

                    $edit_link = '<a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                           href="'.$edit_url.'">
                                           <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                        </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
                                    $invoice_link
                                    $parts_link
                                    $edit_link
                                    $delete_link
                                </td>
                                </div>
                            ";
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

        $shops = User::where([["is_admin",1],["role",2]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

        if (Auth::user()->role == 2){
            $id = Auth::user()->id;
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->get();

            $brands = Brand::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();

            $statuses = Status::whereHas('used', function ($query) {
                $query->where('used', '1');
               })->orwhere('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();

            $job_setting = JobSetting::where([["user_id",auth()->user()->id]])->first();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->get();

            $brands = Brand::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $statuses = Status::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $job_setting = JobSetting::where([["user_id",Auth::user()->parent_id]])->first();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $statuses = Status::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $users = User::where([["is_admin",0]])->orderBy('name', 'asc')->get();
            $job_setting = JobSetting::where([["user_id",Auth::id()]])->first();
        }
        return view('admin.jobs.create',['title' => 'Add New Job ',
            'brands'=>$brands,
            'devices'=>$devices,
            'shops'=>$shops,
            'users'=>$users,
            'idCards'=>$idCards,
            'couriers'=>$couriers,
            'job_setting'=>$job_setting,
            'statuses'=>$statuses]);
    }

    public function import()
    {
        return view('admin.jobs.import', ['title' => 'Jobs Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/job.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'job-sample.xlsx', $headers);
    }


    public function importSave(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('file');
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $destinationPath = "uploads/users/";
                $extension = $file->getClientOriginalExtension('file');
                $fileName = $file->getClientOriginalName('file'); // renameing image
                $request->file('file')->move($destinationPath, $fileName);
                $readFile = $destinationPath . $fileName;
//                $organization = Auth::user()->id;
//                $request->session()->put('organization', $organization);
                $sn = 0;
                $wfts = (new FastExcel)->import($readFile, function ($line)use ($sn) {
                    $sn = $sn + 1;


                    if (Auth::user()->role == 2){
                        $user_id = Auth::id();
                    }elseif (Auth::user()->role == 3){
                        $user_id = Auth::user()->parent_id;
                    }else{
                        $user_id = Auth::id();
                    }
                    if ($line['Service Type']  == 'Courier'){
                        $service = 4;
                    }else if($line['Service Type']  == 'On Site'){
                        $service = 3;
                    }else if($line['Service Type']  == 'Pick Up'){
                        $service = 2;
                    }else{
                        $service = 1;
                    }

                    $user = User::where([["email",$line['Email Address']]])->first();
                    if (!$user){
                        $user = new User();
                        $user->password = bcrypt("12345607");
                    }
                    $user->active = 1;
                    $user->name = $line['User'];
                    $user->phone = $line['Contact Number'];
                    $user->email = $line['Email Address'];
                    $user->save();
                    $shop_user = ShopUser::where([["user_id",$user_id],["customer_id", $user->id]])->first();
                    if (!$shop_user){
                        $shop_user = new ShopUser();
                        $shop_user->customer_id = $user->id;
                        $shop_user->user_id = $user_id;
                        $shop_user->save();
                    }
                    $sh = User::find($user_id);

                    $job = new Job();
                    $job->serial_number = $line['Serial No'];
                    $job->password = $line['Password'];
                    $job->pattern = $line['Pattern'];
                    $job->product_configuration = $line['Product Configuration'];
                    $job->problem_by_customer = $line['Problem Reported By The Customer'];
                    $job->condition_of_product = $line['Condition Of The Product'];
                    $job->expected_delivery = $line['Expected Delivery Date'];
                    $job->comment = $line['Comment'];
                    $job->description = $line['Description'];
                    $job->cost = $line['Estimate Cost'];
                    $job->user_id = $user_id;
                    $job->customer_id = $user->id;
                    $job->service_type = $service;
                    $job_sheet_number = "FF-".date('YmdHis');
                    if ($sh->jobSetting){
                        if ($sh->jobSetting->jos_sheet_prefix){
                            $prefix = $sh->jobSetting->jos_sheet_prefix;
                            $job_sheet_number = "$prefix".date('YmdHis');
                        }
                    }
                    usleep(1000000);
//                    $check = Job::where("job_sheet_number",$job_sheet_number)->first();
//                    if ($check){
//                        $job_sheet_number = "FF-".(date('YmdHis')+1);
//                        if ($sh->jobSetting){
//                            if ($sh->jobSetting->jos_sheet_prefix){
//                                $prefix = $sh->jobSetting->jos_sheet_prefix;
//                                $job_sheet_number = "$prefix".(date('YmdHis')+1);
//                            }
//                        }
//                    }
                    $job->job_sheet_number = $job_sheet_number;
                    $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();
                    if (!$brand){
                        $brand = new Brand();
                        $brand->user_id = $user_id;
                        $brand->name = $line['Brand'];
                        $brand->save();
                    }
                    $status = Status::where([["name",$line['Status']],["user_id",$user_id]])->first();
                    if (!$status){
                        $status = new Status();
                        $status->user_id = $user_id;
                        $status->name = $line['Status'];
                        $status->save();
                    }
                    $job->brand_id = $brand->id;
                    $device = Device::where([["name",$line['Model']],["user_id",$user_id],["brand_id",$brand->id]])->first();
                    if ($line['Device'] == 'Mobile'){
                        $devices = 1;
                    }else{
                        $devices = 2;
                    }
                    if (!$device){
                        $device = new Device();
                        $device->user_id = $user_id;
                        $device->brand_id = $brand->id;
                        $device->name = $line['Model'];
                        $device->type = $devices;
                        $device->save();
                    }

                    $job->device_id = $device->id;
                    $job->status_id = $status->id;
                    return $job->save();

                });

//                Excel::import(new WftsImport, $readFile);
            }
        }

        Session::flash('success_message', 'Success! File Imported successfully!');
        return redirect()->back();

    }
    public function export()
    {
        if (Auth::user()->role == 2){
            $user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user_id = Auth::user()->parent_id;
        }elseif (Auth::user()->role == 1){
            $user_id = Auth::id();
        }
        $data = Job::where("user_id",$user_id)->get();
      // dd($data);
        return Response::download((new FastExcel($data))->export('jobs.csv', function ($pass) {
            if($pass->service_type == 1){
                $service = 'Carry In';
            }elseif($pass->service_type == 2){
                $service = 'Pick Up';
            }elseif($pass->service_type == 3){
                $service = 'On Site';
            }elseif($pass->service_type == 4){
                $service = 'Courier';
            }


            $br = Brand::findOrFail($pass->brand_id);
            if ($br){
                $brand = $br->name;
            }else{
                $brand ="";
            }
            // $stat = Status::findOrFail($pass->status_id);
            // if ($stat){
            //     $status = $stat->name;
            // }else{
            //     $status ="";
            // }
            $dev = Device::findOrFail($pass->device_id);
            if ($dev){
                $model = $dev->name;
                if($dev->type == 1){
                    $device = 'Mobile';
                }else{
                    $device = 'Laptop';
                }
            }else{
                $model ="";
                $device ="";
            }
            $us = User::findOrFail($pass->customer_id);
            if ($us){
                $name = $us->name;
                $email = $us->email;
                $phone = $us->phone;
            }else{
                $name = "";
                $email = "";
                $phone = "";
            }


            return [
                'User' => $name ?? '',
                'Contact Number' => $phone ?? '',
                'Email Address' => $email ?? '',
                'Service Type' => $service ?? '',
                'Device' => $device ?? '',
                'Brand' => $brand ?? '',
                'Model' => $model ?? '',
                'Job Sheet No' => $pass->job_sheet_number ?? '',
                'Serial No' => $pass->serial_number ?? '',
                'Password' => $pass->password ?? '',
                'Pattern' => $pass->pattern ?? '',
                'Product Configuration' => $pass->product_configuration ?? '',
                'Problem Reported By The Customer' => $pass->problem_by_customer ?? '',
                'Condition Of The Product' => $pass->condition_of_product ?? '',
                'Comment' => $pass->comment ?? '',
                'Estimate Cost' => $pass->cost ?? '',
                'Status' => $status ?? '',
                'Expected Delivery Date' => $pass->expected_delivery ?? '',
                'Description' => $pass->description ?? '',
            ];

        }));
    }


    public function store(Request $request)
    {

      //  dd($request->all());

	    $this->validate($request, [

            'brand' => 'required',
            'user' => 'required',
            'device_model' => 'required',
            'idCards*' => 'required|mimes:jpeg,png,jpg,doc,docx,pdf,pdfx',
	    ]);
        $user = new Job();

	    $input = $request->all();
        $res = array_key_exists('email', $input);
        if ($res == false) {
            $user->email = 0;
        } else {
            $user->email = 1;
        }
        $res = array_key_exists('sms', $input);
        if ($res == false) {
            $user->sms = 0;
        } else {
            $user->sms = 1;
        }
        $res = array_key_exists('profile', $input);
        if ($res == false) {
            $userId = null;
        } else {
            $userId = $request->user;
        }
        if (Auth::user()->role == 2){
            $user->user_id = Auth::id();
            $sh = Auth::user();
            $user->job_sheet_number = "FF-".date('YmdHis');
            if (Auth::user()->jobSetting){
                if (Auth::user()->jobSetting->jos_sheet_prefix){
                    $prefix = Auth::user()->jobSetting->jos_sheet_prefix;
                    $user->job_sheet_number = "$prefix".date('YmdHis');
                }
            }
        }elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
            $sh = User::find(Auth::user()->parent_id);
            $user->job_sheet_number = "FF-".date('YmdHis');
            if ($sh->jobSetting){
                if ($sh->jobSetting->jos_sheet_prefix){
                    $prefix = $sh->jobSetting->jos_sheet_prefix;
                    $user->job_sheet_number = "$prefix".date('YmdHis');
                }
            }
        }else{
            $user->user_id = $input['shop'];
            $sh = User::find($input['shop']);
            $user->job_sheet_number = "FF-".date('YmdHis');
            if ($sh->jobSetting){
                if ($sh->jobSetting->jos_sheet_prefix){
                    $prefix = $sh->jobSetting->jos_sheet_prefix;
                    $user->job_sheet_number = "$prefix".date('YmdHis');
                }
            }
        }
        if($sh->number_of_jobs >= 0){
            Session::flash('success_message', 'Sorry! Job Limit is over!');
        }

        if($request->type == 4){
            $user->tracking_id = $request->tracking_id;
            $user->courier_id = $request->courier;
        }


	    $user->id_card_id = $input['id_card'];
	    $user->customer_id = $input['user'];
	    $user->service_type = $input['type'];
	    $user->pattern = $input['pattern'];
	    $user->serial_number = $input['serial_number'];
	    $user->password = $input['password'];
	    if ($request->product_configuration){
            $user->product_configuration = implode(', ', $input['product_configuration']);
        }
	    if ($request->problem_by_customer){
            $user->problem_by_customer = implode(', ', $input['problem_by_customer']);
        }
	    if ($request->condition_of_product){
            $user->condition_of_product = implode(', ', $input['condition_of_product']);
        }

	    $user->comment = $input['comment'];
	    $user->cost = $input['cost'];
//	    $user->status = $input['status'];
        $user->status_id = $input['status'];
	    $user->expected_delivery = $input['expected_delivery'];
	    $user->description = $input['description'];
        $user->brand_id = $input['brand'];
        $user->device_id = $input['device_model'];

	    $save = $user->save();
        foreach ($user->customer->cards as $card) {
            if (array_key_exists("old_card$card->id",$input)){
                $img = new UserCard();
                $img->job_id = $user->id;
                $img->use = 1;
                $img->id_card_id = $card->id_card_id;
                $img->image = $card->image;
                $img->save();
            }
        }
        $device  = Device::findOrFail($request->device_model);

        $pre_repair = explode('|',$device->pre_repair);
        foreach ($pre_repair as $index => $item) {
            $iteration = $index + 1;
            if(array_key_exists("pre_repair$iteration",$input)){
                $job_pre_repair = new JobPreRepair();
                $job_pre_repair->name = $item;
                $job_pre_repair->value = $input["pre_repair$iteration"];
                $job_pre_repair->job_id = $user->id;
                $job_pre_repair->save();
            }
        }
        if ($request->hasFile('document')) {
            if ($request->file('document')->isValid()) {
                $this->validate($request, [
                    'document' => 'required|mimes:jpeg,png,jpg,doc,docx,txt,pdf'
                ]);
                $file = $request->file('document');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('document');
                $image = rand().$image;
                $request->file('document')->move($destinationPath, $image);
                $user->document = $image;

            }
        }
        $user->save();
        if ($request->hasFile('idCards')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'pdfx', 'doc'];
            $files = $request->file('idCards');

            foreach ($files as $key => $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                //$file->move('storage/photos', $filename);
                $check = in_array($extension, $allowedfileExtension);
                $fullpath = $filename . '.' . $extension ; // adding full path

                if ($check) {
                    // removing 2nd loop
                    $destinationPath = public_path('/uploads');
                    $file->move($destinationPath, $filename); // you should include extension here for retrieving in blade later
                    $img = new UserCard();
                    $img->job_id = $user->id;
                    $img->user_id = $userId;
                    $img->id_card_id = $request->id_card;
                    $sn = $key+1;
                    $name = "addJob$sn";
                    $res = array_key_exists($name, $input);
                    if ($res == false) {
                        $img->use = 0;
                    } else {
                        $img->use = 1;
                    }
                    $img->image = $filename;
                    $img->save();
                }else {
                    Session::flash('error_message', 'warning!  Sorry Only Upload png , jpg , doc');
                    return redirect()->back();
                }
            }
        }
        $url = route('job-pdf-public',[$user->id,$user->shop->name]);

        $status = $user->stat->name;
        $msg = "Your Job $status <br> <a href='$url'>Show Pdf</a>";

        $used = Statususe::where('status_id',$user->stat->id)
                            ->where('user_id',auth()->user()->id)
                            ->where('used',1)
                            ->first();

        if(auth()->user()->role == 2 && !empty($used)){
            $us = $user->customer;
            $sms_template = $user->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$user->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$user->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$user->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$user->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$user->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$user->device->name,$sms_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$user->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
            //  $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }

            $whatsapp_template = $user->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$user->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$user->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$user->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$user->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$user->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$user->device->name,$whatsapp_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$user->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }
        }
        else{


            $us = $user->customer;
            $sms_template = $user->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$user->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$user->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$user->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$user->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$user->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$user->device->name,$sms_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$user->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
            //  $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }


            $whatsapp_template = $user->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$user->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$user->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$user->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$user->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$user->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$user->device->name,$whatsapp_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$user->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }
        }






        $mail_template = $user->stat->email_body;
        $mail_template = str_replace("{customer_name}",$user->customer->name,$mail_template);
        $mail_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$mail_template);
        $mail_template = str_replace("{status}",$user->stat->name,$mail_template);
        $mail_template = str_replace("{serial_number}",$user->serial_number,$mail_template);
        $mail_template = str_replace("{delivery_date}",$user->expected_delivery,$mail_template);
        $mail_template = str_replace("{brand}",$user->brand->name,$mail_template);
        $mail_template = str_replace("{device_model}",$user->device->name,$mail_template);
        $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
        $mail_template = str_replace("{device}",$device,$mail_template);
        $mail_template = str_replace("{business_name}",$user->shop->name,$mail_template);
        $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

        //Send Mail

        if ($user->email){

            $mail_setting = Auth::user()->mailSetting;

            if (!$mail_setting){
                $mail_setting = User::findOrFail(1)->mailSetting;
            }
            if ($mail_setting){

                if($user->shop->number_of_emails > 0){

                    if ($mail_setting->type == 2){

                        $data = (object) [
                            'from_name' => Auth::user()->email,
                            'from_email' => Auth::user()->email,
                            'to_name' => $us->name,
                            'to_email' =>  $us->email,
                            'msg' =>  $mail_template,
                            'apikey' =>  $mail_setting->mailchimp_apikey,
                        ];
                        $this->sendThroughMailchimp($data);
                    }elseif ($mail_setting->type == 1){

                        $data = (object) [
                            'from_name' => Auth::user()->email,
                            'from_email' => Auth::user()->email,
                            'to_name' => $us->name,
                            'to_email' =>  $us->email,
                            'msg' =>  $mail_template,
                        ];
                        $this->approach3($data);
                    }
                    $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
                    $user->shop->save();
                }

            }
        }


        //Send SMS
        if ($user->sms){
            $sms_setting = Auth::user()->smsSetting;
            if (!$sms_setting){
                $sms_setting = User::findOrFail(1)->smsSetting;
            }

            $whatsapp_setting = Auth::user()->whatsappSetting;
            if (!$whatsapp_setting){
                $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
            }
            if ($sms_setting){
                if($user->shop->number_of_sms > 0){
                    if ($sms_setting->type == 2){
                        $data = (object) [
                            'phone' => $us->phone,
                            'account_sid' => $sms_setting->twilio_account_sid,
                            'auth_token' => $sms_setting->twilio_auth_token,
                            'twilio_number' =>  $sms_setting->twilio_number,
                            'msg' =>  $sms_template,
                        ];
                        $this->sendThroughTwilio($data);
                    }elseif ($sms_setting->type == 1){
                        $data = (object) [
                            'apikey' => $sms_setting->pearlsms_api_key,
                            'sender' => $sms_setting->pearlsms_sender,
                            'header' => $sms_setting->pearlsms_header,
                            'footer' => $sms_setting->pearlsms_footer,
                            'username' => $sms_setting->pearlsms_username,
                            'phone' => $us->phone,
                            'msg' =>  $sms_template,
                        ];
                        $response =  $this->sendThroughPearl($data);
                        $response = json_decode($response);
                        if ($response->status != "ERROR"){
                            $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                            $user->shop->save();
                        }
                    }elseif ($sms_setting->type == 3){
                        $data = (object) [
                            'apikey' => $sms_setting->bulksms_apikey,
                            'sender' => $sms_setting->bulksms_sendername,
                            'username' => $sms_setting->bulksms_username,
                            'sms_type' => $user->stat->sms_type,
                            'sms_peid' => $user->stat->sms_peid,
                            'sms_template_id' => $user->stat->sms_template_id,
                            'phone' => $us->phone,
                            'msg' =>  $sms_template,
                        ];
                        $response =  $this->sendThroughBulk($data);
                        $response = json_decode($response);
                        if ($response[0]->status == "success"){
                            $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                            $user->shop->save();
                        }
                    }

                }



            }

            if ($whatsapp_setting){
                if($user->shop->number_of_whatsapp > 0){
                    if ($whatsapp_setting->type == 1){
                        $data = (object) [
                            'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                            'to' => str_replace("+","",$us->phone),
                            'msg' =>  $whatsapp_template,
                            'id' =>  $user->id,
                            'pdf' =>  $pdf,
                        ];
                        $this->sendThroughCloud($data);
                    }elseif ($whatsapp_setting->type == 2){
                        if($user->shop->number_of_whatsapp > 0){
                            $data = (object) [
                                'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                'to' => str_replace("+","",$us->phone),
                                'msg' =>  $whatsapp_template,
                            ];
                            $this->sendThroughVonage($data);

                        }
                    }
                    $user->shop->number_of_whatsapp = $user->shop->number_of_whatsapp - 1;
                    $user->shop->save();
                }



            }
        }

        $user->shop->number_of_jobs = $user->shop->number_of_jobs - 1;
        $user->shop->save();

	    Session::flash('success_message', 'Great! Job has been saved successfully!');
        if ($request->page == 2){
            return redirect()->back();
        }elseif ($request->page == 3){
            return redirect()->route('jobs.edit',$user->id);
        }elseif ($request->page == 1){
            return redirect()->route('job-parts',$user->id);
        }
    }
    public function sendThroughVonage($data){
        $from = $data->from;
        $to = $data->to;
        $msg = $data->msg;
        $url = "https://messages-sandbox.nexmo.com/v1/messages";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Basic NDQyMTA0MmE6eUxYaVdqcFJ6cWhSMjkyUw==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
        {
            "from": $from,
            "to": $to,
            "message_type": "text",
            "text": "$msg",
            "channel": "whatsapp"
          }
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
    }
    public function sendThroughCloud($data){
        $api_key = $data->api_key;
        $to = $data->to;

        $msg = rawurlencode("$data->msg");
        $pdf_add = $data->pdf;
        $user = Job::find($data->id);
        $img = $user->shop->image;
        $logo = public_path("/uploads/$img");
     //        $logo = "$url/public/uploads/".$img;
        //        'debugPng' => true,
        $settings = JobSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $settings = JobSetting::where('user_id', Auth::user()->id)->first();
        }
        $basic = BasicSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $basic = BasicSetting::where('user_id', Auth::user()->id)->first();
        }
        if ($basic){
            $logo = url("/uploads/$basic->image");
        }
        $path = url("uploads/");
        $pdf = app('dompdf.wrapper');

        //############ Permitir ver imagenes si falla ################################
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif']);
        $pdf->getDomPDF()->setHttpContext($contxt);
        //#################################################################################
        //        return view("admin.jobs.pdf",compact('user','logo','settings','path'));
        $path = public_path("/uploads/$user->job_sheet_number.pdf");
        $url = url("/uploads/$user->job_sheet_number.pdf");
        $pdf =  $pdf->loadView('admin.jobs.pdf', compact('user','logo','settings','path'))
        ->save("$path");
        $curl = curl_init();
        if($pdf_add == 1){
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey='.$api_key.'&mobile='.$to.'&msg='.$msg.'&pdf='.$url.'',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

        }else{
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey='.$api_key.'&mobile='.$to.'&msg='.$msg.'',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
        }

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function sendThroughTwilio($data){
        $receiverNumber = $data->phone;
        $message = $data->msg;
        try {
            $account_sid = $data->account_sid;
            $auth_token = $data->auth_token;
            $twilio_number = $data->twilio_number;



            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);
        } catch (Exception $e) {

            Session::flash('error_message', $e->getMessage());
            return redirect()->back();
        }
    }
    public function sendThroughPearl($data){
        $curl = curl_init();
        $sender = ($data->sender)?$data->sender : 'PALLVl';
        $header = ($data->header)?$data->header : 'Dear,';
        $footer = ($data->footer)?$data->footer : '';
        $message = rawurlencode("$header  $data->msg $sender $footer");
        $phone = $data->phone;
        $apikey = ($data->apikey)?$data->apikey : '855e314b060d485d9b4a6952c9f52bec';
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.pearlsms.com/public/sms/send?sender='.$sender.'&smstype=TRANS&numbers='.$phone.'&apikey='.$apikey.'&message='.$message.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function sendThroughBulk($data){
        $sender = ($data->sender)?$data->sender : 'FONFIX';
        $username = ($data->username)?$data->username : 'thefonefix21';
        $message = rawurlencode("$data->msg");
        $phone = $data->phone;
        $sms_type = $data->sms_type;
        $sms_peid = $data->sms_peid;
        $sms_template_id = $data->sms_template_id;
        $apikey = ($data->apikey)?$data->apikey : '8721ed80-7591-41c4-a96c-76a9c1768fec';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.bulksmsind.in/v2/sendSMS?username='.$username.'&message='.$message.'&sendername='.$sender.'&smstype='.$sms_type.'&numbers='.$phone.'&apikey='.$apikey.'&peid='.$sms_peid.'&templateid='.$sms_template_id.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function sendThroughMailchimp($data){

        $mailchimp = new \MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey($data->apikey);
        $response = $mailchimp->messages->send(
            [
                "message" => [
                    "from_email" => $data->from_email,
                    "from_name" => $data->from_name,
                    "subject" => "Job Sheet ",
                    "text" => $data->msg,
                    "html" => "$data->msg",

                ],
                "to" => [
                    "email" => $data->to_email,
                    "name" => $data->from_name,
                    "type" => "to",
                ]
            ]
        );
    }

    public function approach3($user) {

        $mail_setting = Auth::user()->mailSetting;
        if (!$mail_setting){
            $mail_setting = User::findOrFail(1)->mailSetting;
        }
        if($mail_setting){

            $configuration = [
                'smtp_host'    => $mail_setting->smtp_host,
                'smtp_port'    => $mail_setting->smtp_port,
                'smtp_username'  => $mail_setting->smtp_username,
                'smtp_password'  => $mail_setting->smtp_password,
                'smtp_encryption'  => $mail_setting->smtp_encryption,
                'from_email'    => $mail_setting->from_email,
                'from_name'    => $mail_setting->from_name,
                'replyTo_email'    => $mail_setting->from_email,
                'replyTo_name'    => $mail_setting->from_name,
            ];

        }else{

            $configuration = [
                'smtp_host'    => 'mail.webexert.us',
                'smtp_port'    => '465',
                'smtp_username'  => 'noreply@webexert.us',
                'smtp_password'  => 'LiB3ds9^euRq',
                'smtp_encryption'  => 'ssl',
                'from_email'    => 'noreply@webexert.us',
                'from_name'    => 'FoneFix',
                'replyTo_email'    => 'noreply@webexert.us',
                'replyTo_name'    => 'FoneFix',
            ];
        }
        $backup = Config::get('mail.mailers.smtp');

        Config::set('mail.mailers.smtp.host', $configuration['smtp_host']);
        Config::set('mail.mailers.smtp.port', $configuration['smtp_port']);
        Config::set('mail.mailers.smtp.username', $configuration['smtp_username']);
        Config::set('mail.mailers.smtp.password', $configuration['smtp_password']);
        Config::set('mail.mailers.smtp.encryption', $configuration['smtp_encryption']);
        Config::set('mail.mailers.smtp.transport', 'smtp');

        $settings = Setting::pluck('value', 'name')->all();
        $data = array(
            'name' => $user->to_name,
            'user_email' => 'usama1517a@gmail.com',
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "Job Sheet Status is Updated",

            'msg' => $user->msg,
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );

        // Mail::send('emails.order', $data, function($message) use ($data) {
        //     $message->to($data['user_email'])->subject($data['subject']);
        // });
        Mail::send('emails.order', $data, function($message) use ($data) {
            $message->to($data['user_email'], $data['name'])
            ->subject('Job Sheet updated');
            $message->from($data['from_email'],$data['name']);
            });
        // Mail::send('emails.order', $data, function ($message) use ($data) {
        //     $message->to($data['user_email'])
        //         ->from($data['from_email'],$data['from_name'])
        //         ->subject($data['subject']);
        // });

//
    }



    public function invoiceStore(Request $request)
    {

	    $job = Job::find($request->job_id);
	    $invoice = new Invoice();
	    $invoice->total = $request->total;
	    $invoice->discount = $request->discount;
	    $invoice->discount_type = $request->discount_type;
	    $invoice->payment_method = $request->payment_type;
        $invoice->number = date('YmdHis');
	    $invoice->job_id = $request->job_id;
	    $invoice->customer_id = $job->customer_id;
	    $invoice->save();
        $parts = UsePart::where([['invoice_id',null],['job_id',$request->job_id]])->get();
	    foreach ($parts as $part){
	        $part->invoice_id = $invoice->id;
	        $part->save();
        }
        Session::flash('success_message', 'Great! Job has been saved successfully!');
	    return redirect()->back();
    }
    public function show($id)
    {

	    $user = Job::find($id);
	    return view('admin.jobs.single', ['title' => 'Job detail', 'user' => $user]);
    }
    public function invoice($id)
    {
	    $user = Job::find($id);
        $invoice = Invoice::where("job_id",$id)->first();
      //  dd($invoice);
	    return view('admin.jobs.invoice', ['title' => 'Job Invoice', 'user' => $user,'invoice' => $invoice]);
    }
    public function invoicePdf($id)
    {
	    $user = Invoice::where("number",$id)->first();
	    return view('admin.jobs.invoice-pdf', ['title' => 'Pdf Invoice', 'user' => $user]);
    }
    public function pdf($id)
    {
	    $user = Job::find($id);
	    $img = $user->shop->image;
        $logo = public_path("/uploads/$img");
//        $logo = "$url/public/uploads/".$img;
//        'debugPng' => true,
        $settings = JobSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $settings = JobSetting::where('user_id', Auth::user()->id)->first();
        }
        $basic = BasicSetting::where('user_id', Auth::id())->first();
        if (Auth::user()->role == 3){
            $basic = BasicSetting::where('user_id', Auth::user()->id)->first();
        }
        if ($basic){
            $logo = url("/uploads/$basic->image");
        }
        $path = url("uploads/");
        $pdf = app('dompdf.wrapper');

        //############ Permitir ver imagenes si falla ################################
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif']);
        $pdf->getDomPDF()->setHttpContext($contxt);
        //#################################################################################
//        return view("admin.jobs.pdf",compact('user','logo','settings','path'));
        $pdf =  $pdf->loadView('admin.jobs.pdf', compact('user','logo','settings','path'));
        return $pdf->download("$user->job_sheet_number.pdf");
    }

	public function clientDetail(Request $request)
	{
		$user = Job::findOrFail($request->id);
        if (Auth::user()->role == 2){
            $statuses = Status::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){$id = Auth::user()->parent_id;
             $statuses = Status::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $statuses = Status::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
		return view('admin.jobs.detail', ['title' => 'Job Detail', 'user' => $user, 'statuses' => $statuses]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Job::find($id);
        $shops = User::where([["is_admin",1],["role",2]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        if (Auth::user()->role == 2){
            $id = Auth::user()->id;
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $brands = Brand::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::where([["user_id",Auth::id()]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $statuses = Status::whereHas('used', function ($query) {
                $query->where('used', '1');
               })->orwhere('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
            $users = User::where([["is_admin",0]])
                ->join('shop_users', function ($join) use ($id) {
                    $join->on('shop_users.customer_id', '=', 'users.id')
                        ->where('shop_users.user_id', '=', $id);
                })
                ->select(
                    'users.*'
                )
                ->orderBy('name', 'asc')->pluck('name','id')->toArray();

            $brands = Brand::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $statuses = Status::where([["user_id",Auth::user()->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $couriers = Courier::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $idCards = IdCard::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $statuses = Status::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $users = User::where([["is_admin",0]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        return view('admin.jobs.edit', ['title' => 'Edit Job','brands'=>$brands,'devices'=>$devices,'shops'=>$shops,'users'=>$users,'idCards'=>$idCards,'couriers'=>$couriers,'statuses'=>$statuses])->withUser($user);
    }

    public function parts($id)
    {

        $user = Auth::user();
        if($user->role == 2){
            $products = Product::where([["user_id",$user->id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        elseif($user->role == 3){
            $products = Product::where([["user_id",$user->parent_id]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        }else{
            $products = Product::orderBy('name', 'asc')->pluck('name','id')->toArray();
        }
        $user = Job::find($id);
        return view('admin.jobs.parts', ['title' => 'Edit Job','products'=>$products])->withUser($user);
    }

    public function addParts(Request $request)
    {

        $user = new UsePart();
        $this->validate($request, [
            'product' => 'required',
            'quantity' => 'required|numeric',
        ]);
        $product = Product::findOrFail($request->product);

        $input = $request->all();
        $user->description = $product->name;
        $user->product_id = $product->id;
        $user->amount = $product->sale_price;
        $user->job_id = $request->id;
        $user->quantity = $input['quantity'];
        $user->save();
        if ($product->manage_stock){
            $product->decrement("quantity",$request->quantity);
            $product->save();
        }
        Session::flash('success_message', 'Great! Part successfully added!');
        return redirect()->back();
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
            'brand' => 'required',
            'user' => 'required',
            'device_model' => 'required',
        ]);
	    $user = Job::find($id);
        $old_status = $user->status_id;
        $new_status = $request->status;
        $old_expected_delivery = $user->expected_delivery;
        $new_expected_delivery = $request->expected_delivery;
//	    $this->validate($request, [
//		    'name' => 'required|max:255',
//            'price' => 'required|numeric',
//            'sku' => 'required',
//            'description' => 'required',
//            'shop' => 'required',
//	    ]);
	    $input = $request->all();
        $res = array_key_exists('email', $input);
        if ($res == false) {
            $user->email = 0;
        } else {
            $user->email = 1;
        }
        $res = array_key_exists('sms', $input);
        if ($res == false) {
            $user->sms = 0;
        } else {
            $user->sms = 1;
        }
        $res = array_key_exists('profile', $input);
        if ($res == false) {
            $userId = null;
        } else {
            $userId = $request->user;
        }
        if (Auth::user()->role == 2){

            $user->user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user->user_id = Auth::user()->parent_id;
        }else{
            $user->user_id = $input['shop'];
        }

        if($request->type == 4){
            $user->tracking_id = $request->tracking_id;
            $user->courier_id = $request->courier;
        }
        $user->customer_id = $input['user'];
        $user->service_type = $input['type'];
        $user->pattern = $input['pattern'];
        $user->serial_number = $input['serial_number'];
        $user->password = $input['password'];
        if ($request->product_configuration){
            $user->product_configuration = implode(', ', $input['product_configuration']);
        }
        if ($request->problem_by_customer){
            $user->problem_by_customer = implode(', ', $input['problem_by_customer']);
        }
        if ($request->condition_of_product){
            $user->condition_of_product = implode(', ', $input['condition_of_product']);
        }
        $user->comment = $input['comment'];
        $user->cost = $input['cost'];
//        $user->status = $input['status'];
        $user->status_id = $input['status'];
        $user->expected_delivery = $input['expected_delivery'];
        $user->description = $input['description'];
        $user->brand_id = $input['brand'];
        $user->device_id = $input['device_model'];

        $user->save();
        JobPreRepair::where("job_id",$user->id)->delete();

        $device  = Device::findOrFail($request->device_model);
        $pre_repair = explode('|',$device->pre_repair);

        foreach ($pre_repair as $index => $item) {
            $iteration = $index + 1;
            if(array_key_exists("pre_repair$iteration",$input)){
                $job_pre_repair = new JobPreRepair();
                $job_pre_repair->name = $item;
                $job_pre_repair->value = $input["pre_repair$iteration"];
                $job_pre_repair->job_id = $user->id;
                $job_pre_repair->save();
            }
        }

        if ($request->hasFile('document')) {
            if ($request->file('document')->isValid()) {
                $this->validate($request, [
                    'document' => 'required|mimes:jpeg,png,jpg,doc,docx,txt,pdf'
                ]);
                $file = $request->file('document');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('document');
                $image = rand().$image;
                $request->file('document')->move($destinationPath, $image);
                $user->document = $image;

            }
        }
        $user->save();
        if ($request->hasFile('idCards')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'pdfx', 'doc'];
            $files = $request->file('idCards');

            foreach ($files as $key => $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                //$file->move('storage/photos', $filename);
                $check = in_array($extension, $allowedfileExtension);
                $fullpath = $filename . '.' . $extension ; // adding full path

                if ($check) {
                    // removing 2nd loop
                    $destinationPath = public_path('/uploads');
                    $file->move($destinationPath, $filename); // you should include extension here for retrieving in blade later
                    $img = new UserCard();
                    $img->job_id = $user->id;
                    $img->user_id = $userId;
                    $img->id_card_id = $request->id_card;
                    $sn = $key+1;
                    $name = "addJob$sn";
                    $res = array_key_exists($name, $input);
                    if ($res == false) {
                        $img->use = 0;
                    } else {
                        $img->use = 1;
                    }
                    $img->image = $filename;
                    $img->save();
                }else {
                    Session::flash('error_message', 'warning!  Sorry Only Upload png , jpg , doc');
                    return redirect()->back();
                }
            }
        }
        if ($old_status != $new_status or $old_expected_delivery != $new_expected_delivery) {
            $url = route('job-pdf-public',[$user->id,$user->shop->name]);

            $us = $user->customer;
            $sms_template = $user->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$user->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$user->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$user->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$user->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$user->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$user->device->name,$sms_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$user->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
//                $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }

            $whatsapp_template = $user->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$user->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$user->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$user->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$user->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$user->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$user->device->name,$whatsapp_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$user->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }

            $mail_template = $user->stat->email_body;
            $mail_template = str_replace("{customer_name}",$user->customer->name,$mail_template);
            $mail_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$mail_template);
            $mail_template = str_replace("{status}",$user->stat->name,$mail_template);
            $mail_template = str_replace("{serial_number}",$user->serial_number,$mail_template);
            $mail_template = str_replace("{delivery_date}",$user->expected_delivery,$mail_template);
            $mail_template = str_replace("{brand}",$user->brand->name,$mail_template);
            $mail_template = str_replace("{device_model}",$user->device->name,$mail_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $mail_template = str_replace("{device}",$device,$mail_template);
            $mail_template = str_replace("{business_name}",$user->shop->name,$mail_template);
            $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

            //Send Mail
            if ($user->email){
                $mail_setting = Auth::user()->mailSetting;
                if (!$mail_setting){
                    $mail_setting = User::findOrFail(1)->mailSetting;
                }
                if ($mail_setting){
                    if($user->shop->number_of_emails > 0){
                        if ($mail_setting->type == 2){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                                'apikey' =>  $mail_setting->mailchimp_apikey,
                            ];
                            $this->sendThroughMailchimp($data);
                        }elseif ($mail_setting->type == 1){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                            ];
                            $this->approach3($data);
                        }
                        $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
                        $user->shop->save();
                    }

                }
            }


            //Send SMS
            if ($user->sms){
                $sms_setting = Auth::user()->smsSetting;
                if (!$sms_setting){
                    $sms_setting = User::findOrFail(1)->smsSetting;
                }

                $whatsapp_setting = Auth::user()->whatsappSetting;
                if (!$whatsapp_setting){
                    $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                }
                if ($sms_setting){
                    if($user->shop->number_of_sms > 0){
                        if ($sms_setting->type == 2){
                            $data = (object) [
                                'phone' => $us->phone,
                                'account_sid' => $sms_setting->twilio_account_sid,
                                'auth_token' => $sms_setting->twilio_auth_token,
                                'twilio_number' =>  $sms_setting->twilio_number,
                                'msg' =>  $sms_template,
                            ];
                            $this->sendThroughTwilio($data);
                        }elseif ($sms_setting->type == 1){
                            $data = (object) [
                                'apikey' => $sms_setting->pearlsms_api_key,
                                'sender' => $sms_setting->pearlsms_sender,
                                'header' => $sms_setting->pearlsms_header,
                                'footer' => $sms_setting->pearlsms_footer,
                                'username' => $sms_setting->pearlsms_username,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughPearl($data);
                            $response = json_decode($response);
                            if ($response->status != "ERROR"){
                                $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                                $user->shop->save();
                            }
                        }elseif ($sms_setting->type == 3){
                            $data = (object) [
                                'apikey' => $sms_setting->bulksms_apikey,
                                'sender' => $sms_setting->bulksms_sendername,
                                'username' => $sms_setting->bulksms_username,
                                'sms_type' => $user->stat->sms_type,
                                'sms_peid' => $user->stat->sms_peid,
                                'sms_template_id' => $user->stat->sms_template_id,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughBulk($data);
                            $response = json_decode($response);
                            if ($response[0]->status == "success"){
                                $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                                $user->shop->save();
                            }
                        }

                    }



                }

                if ($whatsapp_setting){
                    if($user->shop->number_of_whatsapp > 0){
                        if ($whatsapp_setting->type == 1){
                            $data = (object) [
                                'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                'to' => str_replace("+","",$us->phone),
                                'msg' =>  $whatsapp_template,
                                'id' =>  $user->id,
                                'pdf' =>  $pdf,
                            ];
                            $response = $this->sendThroughCloud($data);
                            $response = json_decode($response);
                        }elseif ($whatsapp_setting->type == 2){
                            if($user->shop->number_of_whatsapp > 0){
                                $data = (object) [
                                    'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                    'to' => str_replace("+","",$us->phone),
                                    'msg' =>  $whatsapp_template,
                                ];
                                $this->sendThroughVonage($data);

                            }
                        }
                        $user->shop->number_of_whatsapp = $user->shop->number_of_whatsapp - 1;
                        $user->shop->save();
                    }



                }
            }


        }

	    Session::flash('success_message', 'Great! Job successfully updated!');
        if ($request->page == 2){
            return redirect()->back();
        }elseif ($request->page == 3){
            return redirect()->route('jobs.edit',$user->id);
        }elseif ($request->page == 1){
            return redirect()->route('job-parts',$user->id);
        }
    }

    public function updateStatus(Request $request)
    {
	    $user = Job::find($request->id);
        $old_status = $user->status_id;
        $new_status = $request->status;
	    $input = $request->all();
        $user->note = $input['note'];
        $user->status_id = $new_status;
        $user->save();

        if ($old_status != $new_status) {
            $url = route('job-pdf-public',[$user->id,$user->shop->name]);

            $us = $user->customer;
            $sms_template = $user->stat->sms_template;
            $sms_template = str_replace("{customer_name}",$user->customer->name,$sms_template);
            $sms_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$sms_template);
            $sms_template = str_replace("{status}",$user->stat->name,$sms_template);
            $sms_template = str_replace("{serial_number}",$user->serial_number,$sms_template);
            $sms_template = str_replace("{delivery_date}",$user->expected_delivery,$sms_template);
            $sms_template = str_replace("{brand}",$user->brand->name,$sms_template);
            $sms_template = str_replace("{device_model}",$user->device->name,$sms_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $sms_template = str_replace("{device}",$device,$sms_template);
            $sms_template = str_replace("{business_name}",$user->shop->name,$sms_template);
            if (str_contains($sms_template, '{pdf}')) {
                $pdf = 1;
                $sms_template = str_replace("{pdf}",$url,$sms_template);
//                $sms_template = "$sms_template  $url";
            }else{
                $pdf = 0;
            }

            $whatsapp_template = $user->stat->whatsapp_template;
            $whatsapp_template = str_replace("{customer_name}",$user->customer->name,$whatsapp_template);
            $whatsapp_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$whatsapp_template);
            $whatsapp_template = str_replace("{status}",$user->stat->name,$whatsapp_template);
            $whatsapp_template = str_replace("{serial_number}",$user->serial_number,$whatsapp_template);
            $whatsapp_template = str_replace("{delivery_date}",$user->expected_delivery,$whatsapp_template);
            $whatsapp_template = str_replace("{brand}",$user->brand->name,$whatsapp_template);
            $whatsapp_template = str_replace("{device_model}",$user->device->name,$whatsapp_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $whatsapp_template = str_replace("{device}",$device,$whatsapp_template);
            $whatsapp_template = str_replace("{business_name}",$user->shop->name,$whatsapp_template);
            if (str_contains($whatsapp_template, '{pdf}')) {
                $pdf = 1;
                $whatsapp_template = str_replace("{pdf}","",$whatsapp_template);
                $whatsapp_template = "$whatsapp_template  $url";
            }else{
                $pdf = 0;
            }





            $mail_template = $user->stat->email_body;
            $mail_template = str_replace("{customer_name}",$user->customer->name,$mail_template);
            $mail_template = str_replace("{job_sheet_no}",$user->job_sheet_number,$mail_template);
            $mail_template = str_replace("{status}",$user->stat->name,$mail_template);
            $mail_template = str_replace("{serial_number}",$user->serial_number,$mail_template);
            $mail_template = str_replace("{delivery_date}",$user->expected_delivery,$mail_template);
            $mail_template = str_replace("{brand}",$user->brand->name,$mail_template);
            $mail_template = str_replace("{device_model}",$user->device->name,$mail_template);
            $device = ($user->device->type == 1) ? "Mobile" : "Laptop";
            $mail_template = str_replace("{device}",$device,$mail_template);
            $mail_template = str_replace("{business_name}",$user->shop->name,$mail_template);
            $mail_template = "$mail_template <br> <a href='$url'>Show Pdf</a>";

            //Send Mail
            if ($user->email){
                $mail_setting = Auth::user()->mailSetting;
                if (!$mail_setting){
                    $mail_setting = User::findOrFail(1)->mailSetting;
                }
                if ($mail_setting){
                    if($user->shop->number_of_emails > 0){
                        if ($mail_setting->type == 2){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                                'apikey' =>  $mail_setting->mailchimp_apikey,
                            ];
                            $this->sendThroughMailchimp($data);
                        }elseif ($mail_setting->type == 1){
                            $data = (object) [
                                'from_name' => Auth::user()->email,
                                'from_email' => Auth::user()->email,
                                'to_name' => $us->name,
                                'to_email' =>  $us->email,
                                'msg' =>  $mail_template,
                            ];
                            $this->approach3($data);
                        }
                        $user->shop->number_of_emails = $user->shop->number_of_emails - 1;
                        $user->shop->save();
                    }

                }
            }


            //Send SMS
            if ($user->sms){
                $sms_setting = Auth::user()->smsSetting;
                if (!$sms_setting){
                    $sms_setting = User::findOrFail(1)->smsSetting;
                }

                $whatsapp_setting = Auth::user()->whatsappSetting;
                if (!$whatsapp_setting){
                    $whatsapp_setting = User::findOrFail(1)->whatsappSetting;
                }
                if ($sms_setting){
                    if($user->shop->number_of_sms > 0){
                        if ($sms_setting->type == 2){
                            $data = (object) [
                                'phone' => $us->phone,
                                'account_sid' => $sms_setting->twilio_account_sid,
                                'auth_token' => $sms_setting->twilio_auth_token,
                                'twilio_number' =>  $sms_setting->twilio_number,
                                'msg' =>  $sms_template,
                            ];
                            $this->sendThroughTwilio($data);
                        }elseif ($sms_setting->type == 1){
                            $data = (object) [
                                'apikey' => $sms_setting->pearlsms_api_key,
                                'sender' => $sms_setting->pearlsms_sender,
                                'header' => $sms_setting->pearlsms_header,
                                'footer' => $sms_setting->pearlsms_footer,
                                'username' => $sms_setting->pearlsms_username,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughPearl($data);
                            $response = json_decode($response);
                            if ($response->status != "ERROR"){
                                $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                                $user->shop->save();
                            }
                        }elseif ($sms_setting->type == 3){
                            $data = (object) [
                                'apikey' => $sms_setting->bulksms_apikey,
                                'sender' => $sms_setting->bulksms_sendername,
                                'username' => $sms_setting->bulksms_username,
                                'sms_type' => $user->stat->sms_type,
                                'sms_peid' => $user->stat->sms_peid,
                                'sms_template_id' => $user->stat->sms_template_id,
                                'phone' => $us->phone,
                                'msg' =>  $sms_template,
                            ];
                            $response =  $this->sendThroughBulk($data);
                            $response = json_decode($response);

                            if ($response[0]->status == "success"){
                                $user->shop->number_of_sms = $user->shop->number_of_sms - 1;
                                $user->shop->save();
                            }
                        }

                    }



                }

                if ($whatsapp_setting){
                    if($user->shop->number_of_whatsapp > 0){
                        if ($whatsapp_setting->type == 1){
                            $data = (object) [
                                'api_key' => str_replace("+","",$whatsapp_setting->cloudwhatsapp_api_key),
                                'to' => str_replace("+","",$us->phone),
                                'msg' =>  $whatsapp_template,
                                'id' =>  $user->id,
                                'pdf' =>  $pdf,
                            ];
                            $this->sendThroughCloud($data);
                        }elseif ($whatsapp_setting->type == 2){
                            if($user->shop->number_of_whatsapp > 0){
                                $data = (object) [
                                    'from' => str_replace("+","",$whatsapp_setting->whatsapp_vonage_from),
                                    'to' => str_replace("+","",$us->phone),
                                    'msg' =>  $whatsapp_template,
                                ];
                                $this->sendThroughVonage($data);

                            }
                        }
                        $user->shop->number_of_whatsapp = $user->shop->number_of_whatsapp - 1;
                        $user->shop->save();
                    }



                }
            }

        }

	    Session::flash('success_message', 'Great! Job Status successfully updated!');
        return redirect()->route('jobs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkQty(Request $request)
    {
	    $user = Product::find($request->id);

        if(!empty($user)){
            if ($user->manage_stock){
                return $user->quantity;
            }
        }
        else{
            return 10000000000;
        }

    }
    public function destroy($id)
    {
	    $user = Job::find($id);

        $user->delete();
        Session::flash('success_message', 'Job successfully deleted!');
	    return redirect()->route('jobs.index');

    }
    public function removeIdCard($id)
    {
	    $user = UserCard::find($id);

        $user->delete();
        Session::flash('success_message', 'Card  successfully deleted!');
	    return redirect()->back();

    }
    public function partDelete($id)
    {
	    $user = UsePart::find($id);
	    $product = Product::find($user->product_id);
	    if ($product->manage_stock){
            $product->increment('quantity',$user->quantity);
            $product->save();
        }
        $user->delete();
        Session::flash('success_message', 'Part successfully deleted!');
	    return redirect()->back();

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'Jobs' => 'required',

		]);
		foreach ($input['Jobs'] as $index => $id) {

			$user = Job::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Jobs successfully deleted!');
		return redirect()->back();

	}
}
