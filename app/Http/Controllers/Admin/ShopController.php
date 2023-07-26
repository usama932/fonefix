<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\Category;
use App\Models\Country;
use App\Models\Provinces;
use App\Models\ShopUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;
use Auth;

class ShopController extends Controller
{

    public function index()
    {
	    $title = 'Shops';
	    return view('admin.shops.index',compact('title'));
    }


	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'email',
			3 => 'active',
			4 => 'created_at',
			5 => 'action'
		);

		$totalData = User::where('is_admin',1)->count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
			$users = User::where([['is_admin',1],['role',2]])->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = User::where([['is_admin',1],['role',2]])->count();
		}else{
			$search = $request->input('search.value');
			$users = User::where([
				['is_admin',1],
				['role',2],
				['name', 'like', "%{$search}%"],
			])
				->orWhere([
                    ['role',2],
                    ['is_admin',1],
                    ['email', 'like', "%{$search}%"],
                ])
				->orWhere([
                    ['role',2],
                    ['is_admin',1],
                    ['created_at', 'like', "%{$search}%"],
                ])
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = User::where([
				['role',2],
				['is_admin',1],
				['name', 'like', "%{$search}%"],
			])
				->orWhere([
                    ['role',2],
                    ['is_admin',1],
                    ['email', 'like', "%{$search}%"],
                ])
				->orWhere([
                    ['role',2],
                    ['is_admin',1],
                    ['created_at', 'like', "%{$search}%"],
                ])
				->count();
		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('shops.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="clients[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
				$nestedData['email'] = $r->email;
                $nestedData['phone'] = $r->phone ?? "Not Available";
                $nestedData['whatsapp_number'] = $r->whatsapp_number  ?? "Not Available";
				if($r->active){
					$nestedData['active'] = '<span class="label label-success label-inline mr-2">Active</span>';
				}else{
					$nestedData['active'] = '<span class="label label-danger label-inline mr-2">Inactive</span>';
				}


				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));

                    $nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Client" href="javascript:void(0)">
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
	    $title = 'Add New Shop';
        $countries = Country::where("active",1)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        $provinces = Provinces::orderBy('name', 'asc')->pluck('name','id')->toArray();
	    return view('admin.shops.create',["title"=> $title, "countries"=>$countries, "provinces"=>$provinces]);
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
		    'email' => 'required|unique:users,email',
		    'password' => 'required|min:6',
		    'country' => 'required',
		    'province' => 'required',
		    'expiry_date' => 'required',
	    ]);

	    $input = $request->all();
	    $user = new User();
	    $user->name = $input['name'];
	    $user->email = $input['email'];
        $user->slug = $this->createSlug($request->input('name'),0);
	    $user->role = 2;
	    $user->is_admin = 1;
	    $res = array_key_exists('active', $input);
	    if ($res == false) {
		    $user->active = 0;
		    $user->disable_reason = $request->disable_reason;
	    } else {
		    $user->active = 1;

	    }
	    $res = array_key_exists('custom_sms', $input);


	    if ($res == false) {
		    $user->custom_sms = 0;

	    } else {
		    $user->custom_sms = 1;
            $user->account_sid = $input['account_sid'];
            $user->auth_token = $input['auth_token'];
            $user->twilio_number = $input['twilio_number'];
	    }

	    $res = array_key_exists('custom_mail', $input);
	    if ($res == false) {
		    $user->custom_mail = 0;

	    } else {
		    $user->custom_mail = 1;
            $user->mail_host = $input['mail_host'];
            $user->mail_username = $input['mail_username'];
            $user->mail_password = $input['mail_password'];
            $user->mail_from_address = $input['mail_from_address'];
	    }
        $status = array_key_exists('custom_status', $input);
        if ($status == false) {
		    $user->custom_status = 0;

	    } else {
		    $user->custom_status = 1;

	    }
        $user->country_id = $input['country'];
        $user->province_id = $input['province'];
        $user->city = $input['city'];
        $user->postal_code = $input['postal_code'];
        $user->line1 = $input['line1'];
        $user->line2 = $input['line2'];
        $user->phone = $input['phone'];
        $user->whatsapp_number = $input['whatsapp_number'] ?? '';
        $user->expiry_date = $input['expiry_date'] ?? '';
        $user->number_of_jobs = $input['number_of_jobs'] ?? '';
        $user->number_of_emails = $input['number_of_emails'] ?? '0';
        $user->number_of_whatsapp = $input['number_of_whatsapp'] ?? '0';
        $user->number_of_sms = $input['number_of_sms'];
        $user->location = $input['location'];

	    $user->password = bcrypt($input['password']);
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('image');
                $image = rand().$image;
                $request->file('image')->move($destinationPath, $image);
                $user->image = $image;

            }
        }
	    $user->save();
        $basicSetting = new BasicSetting();
        $basicSetting->name = $user->name;
        $basicSetting->address = $user->line1;
        $basicSetting->phone = $user->phone;
        $basicSetting->image = $user->image;
        $basicSetting->user_id = $user->id;
        $basicSetting->email = $user->email;
        $basicSetting->save();

	    Session::flash('success_message', 'Great! Shop has been saved successfully!');
	    return redirect()->back();
    }

    public function createSlug($title, $id)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id )
    {
        return User::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        return view('admin.shops.import', ['title' => 'Client Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/shop.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'shop-sample.xlsx', $headers);
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
                $wfts = (new FastExcel)->import($readFile, function ($line) {
                    if (Auth::user()->role == 2){
                        $user_id = Auth::id();
                    }elseif (Auth::user()->role == 3){
                        $user_id = Auth::user()->parent_id;
                    }else{
                        $user_id = Auth::id();
                    }

                    $user = User::where([["email",$line['Email']]])->first();
                    if (!$user){
                        $user = new User();
                        $user->password = bcrypt("12345607");
                        $user->active = 1;
                        $user->role = 2;
                        $user->is_admin = 1;
                    }

                    $user->name = $line['Name'];
                    $user->phone = $line['Phone'];
                    $user->line1 = $line['Address Line 1'];
                    $user->line2 = $line['Address Line 2'];
                    $user->city = $line['City'];
                    $user->postal_code = $line['PostalCode'];
                    $user->email = $line['Email'];
                    $user->expiry_date = $line['Expiry Date'];
                    $user->number_of_jobs = $line['Number of Jobs'];
                    $user->number_of_emails = $line['Number of Emails'];
                    $user->number_of_sms = $line['Number of sms'];
                    $user->number_of_whatsapp = $line['Number of Whatsapp'];
                    $user->whatsapp_number = $line['Whatsapp Number'];
                    $user->location = $line['Location'];
                    return $user->save();
                });

//                Excel::import(new WftsImport, $readFile);
            }
        }

        Session::flash('success_message', 'Success! File Imported successfully!');
        return redirect()->back();

    }
    public function export()
    {
//        if (Auth::user()->role == 2){
//            $user_id = Auth::id();
//        }elseif (Auth::user()->role == 3){
//            $user_id = Auth::user()->parent_id;
//        }elseif (Auth::user()->role == 1){
//            $user_id = Auth::id();
//        }
        $data = User::where("role",2)
            ->get();
        return Response::download((new FastExcel($data))->export('shops.csv', function ($pass) {

            return [
                'Name' => $pass->name,
                'Phone' => $pass->phone,
                'Address Line 1' => $pass->line1,
                'Address Line 2' => $pass->line2,
                'City' => $pass->city,
                'PostalCode' => $pass->postal_code,
                'Email' => $pass->email,
                'Location' => $pass->location,
                'Expiry Date' => $pass->expiry_date,
                'Number of Jobs' => $pass->number_of_jobs,
                'Number of Emails' => $pass->number_of_emails,
                'Number of sms' => $pass->number_of_sms,
                'Number of Whatsapp' => $pass->number_of_whatsapp,
                'Whatsapp Number' => $pass->whatsapp_number,
            ];

        }));
    }
    public function show($id)
    {
	    $user = User::find($id);
	    return view('admin.shops.single', ['title' => 'Staff detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{

		$user = User::findOrFail($request->id);


		return view('admin.shops.detail', ['title' => 'Shop Detail', 'user' => $user]);
	}

	public function countryProvinces(Request $request)
	{
		$provinces = Provinces::where([["country_id",$request->id]])->get();
		return view('admin.shops.provinces', ['title' => 'Provinces Detail', 'provinces' => $provinces]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = User::find($id);
        $countries = Country::where("active",1)->orderBy('name', 'asc')->pluck('name','id')->toArray();
        $provinces = Provinces::orderBy('name', 'asc')->pluck('name','id')->toArray();
	    return view('admin.shops.edit', ['title' => 'Edit Shop details',"countries" => $countries, 'provinces' => $provinces])->withUser($user);
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
	    $user = User::find($id);
	    $this->validate($request, [
		    'name' => 'required|max:255',
		    'email' => 'required|unique:users,email,'.$user->id,
		    'country' => 'required',
		    'province' => 'required',
		    'expiry_date' => 'required',
	    ]);
	    $input = $request->all();

	    $user->name = $input['name'];
	    $user->email = $input['email'];
	    $user->role = 2;
        $user->is_admin = 1;
        $user->slug = $this->createSlug($request->input('name'),$user->id);
        $res = array_key_exists('active', $input);
        if ($res == false) {
            $user->active = 0;
            $user->disable_reason = $request->disable_reason;
        } else {
            $user->active = 1;

        }
        $res = array_key_exists('custom_sms', $input);
        if ($res == false) {
            $user->custom_sms = 0;

        } else {
            $user->custom_sms = 1;
            $user->account_sid = $input['account_sid'];
            $user->auth_token = $input['auth_token'];
            $user->twilio_number = $input['twilio_number'];
        }

        $res = array_key_exists('custom_mail', $input);
        if ($res == false) {
            $user->custom_mail = 0;

        } else {
            $user->custom_mail = 1;
            $user->mail_host = $input['mail_host'];
            $user->mail_username = $input['mail_username'];
            $user->mail_password = $input['mail_password'];
            $user->mail_from_address = $input['mail_from_address'];
        }
        $status = array_key_exists('custom_status', $input);
        if ($status == false) {
		    $user->custom_status = 0;

	    } else {
		    $user->custom_status = 1;

	    }
        $user->country_id = $input['country'];
        $user->province_id = $input['province'];
        $user->city = $input['city'];
        $user->postal_code = $input['postal_code'] ?? '';
        $user->line1 = $input['line1'] ?? '';
        $user->line2 = $input['line2'] ?? '';
        $user->phone = $input['phone'] ?? '';
        $user->whatsapp_number = $input['whatsapp_number'] ?? ' ';
        $user->expiry_date = $input['expiry_date'];
        $user->number_of_jobs = $input['number_of_jobs'] ?? '0';
        $user->number_of_emails = $input['number_of_emails'] ?? '0';
        $user->number_of_whatsapp = $input['number_of_whatsapp'] ?? '0';
        $user->number_of_sms = $input['number_of_sms'];
        $user->location = $input['location'];
	    if(!empty($input['password'])) {
		    $user->password = bcrypt($input['password']);
	    }


        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg'
                ]);
                $file = $request->file('image');
                $destinationPath = public_path('/uploads');
                //$extension = $file->getClientOriginalExtension('logo');
                $image = $file->getClientOriginalName('image');
                $image = rand().$image;
                $request->file('image')->move($destinationPath, $image);
                $user->image = $image;

            }
        }
	    $user->save();

	    Session::flash('success_message', 'Great! Shop successfully updated!');
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
	    $user = User::find($id);
	    if($user->is_admin == 0){
		    $user->delete();
		    Session::flash('success_message', 'Shop successfully deleted!');
	    }
	    return redirect()->route('shops.index');

    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'clients' => 'required',

		]);
		foreach ($input['clients'] as $index => $id) {

			$user = User::find($id);
			if($user->is_admin == 0){
				$user->delete();
			}

		}
		Session::flash('success_message', 'Shops successfully deleted!');
		return redirect()->back();

	}
}
