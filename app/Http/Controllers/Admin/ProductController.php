<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Device;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use Auth;
use Response;

class ProductController extends Controller
{

    public function index()
    {
	    $title = 'Products';
        $shops = User::where(['is_admin'=> '1', 'active' => '1', 'role' => '2'])->latest()->get();
	    return view('admin.products.index',compact('title','shops'));
    }

	public function getClients(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'sale_price',
			3 => 'minimum_sale_price',
			4 => 'quantity',
			5 => 'brand_id',
			6 => 'created_at',
			7 => 'action'
		);
        if (Auth::user()->role == 2){
            $id = Auth::id();
        } elseif (Auth::user()->role == 3){
            $id = Auth::user()->parent_id;
        }else{
            $id = null;
        }
        if ($id){
            $totalData = Product::where([['user_id',$id]])->count();
        }else{
            $totalData = Product::count();
        }
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){
            if ($id){
                $users = Product::where([['user_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Product::where([['user_id',$id]])->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->count();
            }else{
                $users = Product::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = Product::when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })->count();
            }

		}else{
			$search = $request->input('search.value');
            if ($id){
                $users = Product::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                        })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Product::where([
                    ['name', 'like', "%{$search}%"],
                    ['user_id',$id]
                ])
                    ->orWhere([
                        ['created_at', 'like', "%{$search}%"],
                        ['user_id',$id]
                    ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                        })
                    ->count();


            }else{
                $users = Product::where([
                    ['name', 'like', "%{$search}%"],
                ])
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                    $query->where('user_id', $request->shop_id);
                    })
                    ->orWhere('created_at','like',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = Product::where([
                    ['name', 'like', "%{$search}%"],
                ])
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('created_at','like',"%{$search}%")
                    ->when($request->has('shop_id'), function ($query) use ($request) {
                        $query->where('user_id', $request->shop_id);
                        })
                    ->count();
            }

		}


		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('products.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="products[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
                $nestedData['shop'] = $r->shop->name ?? 'Not Assign';
				$nestedData['minimum_sale_price'] = $r->minimum_sale_price;
				$nestedData['sale_price'] = $r->sale_price;
				$nestedData['quantity'] = $r->quantity;

				if ($r->brand){
                    $nestedData['brand'] = $r->brand->name;
                }else{
                    $nestedData['brand'] = "Nil";
                }

				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
                $user = Auth::user();
                if($user->role == 1){
                    $edit = 1;
                    $del = 1;
                    $view = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->product_view;
                    $edit = $user->permission->product_edit;
                    $del = $user->permission->product_delete;
                }
                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $edit_link = '<a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('.$r->id.');\" title=\"Delete Client\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
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
        if (Auth::user()->role == 2){
            $categories = Category::where('user_id',Auth::id())->get();
            $brands = Brand::where('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();

            $devices = Device::where('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();

        } elseif (Auth::user()->role == 3){
            $categories = Category::where('user_id',Auth::user()->parent_id)->get();
            $brands = Brand::where('user_id',Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where('user_id',Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $categories = Category::get();
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();

        }
        $shops = User::where([["is_admin",1],["role",2]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        return view('admin.products.create',['title' => 'Add New Product ','brands'=>$brands,'devices'=>$devices,'shops'=>$shops,'categories'=>$categories,]);
    }

    public function import()
    {
        return view('admin.products.import', ['title' => 'Client Import']);
    }

    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/product.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'product-sample.xlsx', $headers);
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

                  // dd($line['sku']);
                    $user = Product::where([["sku",$line['Sku']],["user_id",$user_id]])->first();
                    if (!$user){
                        $user = new Product();
                    }
                    $brand = Brand::where([["name",$line['Brand']],["user_id",$user_id]])->first();

                    if (!$brand){
                        $brand = new Brand();
                        $brand->name = $line['Brand'];
                        $brand->user_id = $user_id;
                        $brand->save();
                    }
                    $category = Category::where([["name",$line['Category']],["user_id",$user_id]])->first();

                    if (!$category){
                        $category = new Category();
                        $category->name = $line['Category'];
                        $category->user_id = $user_id;
                        $category->save();
                    }
                    if ($line['Active'] == 'Yes'){
                        $user->active = 1;
                    }else{
                        $user->active = 0;
                    }
                    if ($line['Manage Stock'] == 'Yes'){
                        $user->manage_stock = 1;
                    }else{
                        $user->manage_stock = 0;
                    }
                    if ($line['Product Description'] == 'Yes'){
                        $user->product_description = 1;
                    }else{
                        $user->product_description = 0;
                    }
                    $user->name = $line['Name'];
                    $user->sku = $line['Sku'];
                    $user->imei = $line['IMEI'];
                    $user->serial_number = $line['Serial Number'];
                    $user->alert_quantity = $line['Alert Quantity'];
                    $user->quantity = $line['Quantity'];
                    $user->location_position = $line['Location Position'];
                    $user->location_row = $line['Location Row'];
                    $user->location_rack = $line['Location Rack'];
                    $user->warranty = $line['Warranty'];
                    $user->description = $line['Description'];
                    $user->short_description = $line['Short Description'];
                    $user->maximum_discount = $line['Maximum Discount'];
                    $user->minimum_sale_price = $line['Minimum Sale Price'];
                    $user->margin = $line['Margin'];
                    $user->sale_price = $line['Sale Price'];
                    $user->purchase_price = $line['Purchase Price'];
                    $user->category_id = $category->id;
                    $user->brand_id = $brand->id;
                    $user->user_id = $user_id;
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
        if (Auth::user()->role == 2){
            $user_id = Auth::id();
        }elseif (Auth::user()->role == 3){
            $user_id = Auth::user()->parent_id;
        }elseif (Auth::user()->role == 1){
            $user_id = Auth::id();
        }
        $data = Product::where("user_id",$user_id)->get();
        return Response::download((new FastExcel($data))->export('products.csv', function ($pass) {
            if($pass->active == 1){
                $active = 'Yes';
            }else{
                $active = 'No';
            }
            if($pass->manage_stock == 1){
                $manage = 'Yes';
            }else{
                $manage = 'No';
            }
            if($pass->product_description == 1){
                $product_description = 'Yes';
            }else{
                $product_description = 'No';
            }
            if($pass->brand_id){
                $record = Brand::findOrFail($pass->brand_id);
                $brand = $record->name;
            }else{
                $brand = '';
            }
            if($pass->category_id){
                $record = Category::findOrFail($pass->category_id);
                $category = $record->name;
            }else{
                $category = '';
            }
            return [
                'Name' => $pass->name,
                'Brand' => $brand,
                'Sku' => $pass->sku,
                'Purchase Price' => $pass->purchase_price,
                'Sale Price' => $pass->sale_price,
                'Margin' => $pass->margin,
                'Minimum Sale Price' => $pass->minimum_sale_price,
                'Maximum Discount' => $pass->maximum_discount,
                'Short Description' => $pass->short_description,
                'Description' => $pass->description,
                'Warranty' => $pass->warranty,
                'Location Rack' => $pass->location_rack,
                'Location Row' => $pass->location_row,
                'Location Position' => $pass->location_position,
                'Quantity' => $pass->quantity,
                'Alert Quantity' => $pass->alert_quantity,
                'Serial Number' => $pass->serial_number,
                'IMEI' => $pass->imei,
                'Product Description' => $product_description,
                'Category' => $category,
                'Manage Stock' => $manage,
                'Active' => $active,

            ];

        }));
    }
    public function importSave1(Request $request)
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
                    $price = trim($line['Price'], " ");
                    $t_cat = trim( $line['Category'] );
                    $cat = Category::where("name",$t_cat)->first();
                    if ($cat){
                        $user = Product::where("sku",$line['Sku'])->first();
                        if (!$user){
                            $user = new Product();
                        }
                        $user->name = $line['Name'];
                        $user->description = $line['Description'];
                        $user->price = $price;
                        $user->sku = $line['Sku'];
                        $user->category_id = $cat->id;
                        return $user->save();
                    }else{
                        $user = Product::where("sku",$line['Sku'])->first();
                        if (!$user){
                            $user = new Product();
                        }
                        $user->name = $line['Name'];
                        $user->description = $line['Description'];
                        $user->price = $price;
                        $user->sku = $line['Sku'];
                        $user->category_id = null;
                        return $user->save();
                    }

                });

//                Excel::import(new WftsImport, $readFile);
            }
        }

        Session::flash('success_message', 'Success! File Imported successfully!');
        return redirect()->back();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        //dd($request->all());
        try {

            $this->validate($request, [
                'name' => 'required|max:255',
    //		    'price' => 'required|numeric',
    //		    'sku' => 'required',
           //     'description' => 'required',
    //		    'shop' => 'required',
                'purchase_price' => 'required|numeric',
                'sale_price' => 'required|numeric',

            ]);
            $user = new Product();
            $input = $request->all();
            $res = array_key_exists('active', $input);
            $res = array_key_exists('active', $input);

            if ($res == false) {
                $user->active = 0;
                $user->reason =  $request->disable_reason;
            } else {
                $user->active = 1;
                $user->reason =  null;

            }
            $is_regular = array_key_exists('is_regular', $input);
            if ($is_regular == false) {
                $user->is_regular = 0;

            } else {
                $user->is_regular = 1;

            }
            $res = array_key_exists('product_description', $input);
            if ($res == false) {
                $user->product_description = 0;
                $user->imei =  null;
                $user->serial_number =  null;
            } else {
                $user->product_description = 1;
                $user->imei =  $request->imei;
                $user->serial_number =  $request->serial_number;
            }

            $res = array_key_exists('stock', $input);
            if ($res == false) {
                $user->manage_stock = 0;

                $user->quantity = 0;
                $user->opening_stock = 0;
                $user->opening_stock_quantity = 0;
                $user->opening_stock_timestamp = null;
                $user->alert_quantity = 0;
            } else {
                $user->manage_stock = 1;
                $user->quantity = $request->quantity;
                $user->alert_quantity = $input['alert_quantity'];
                $user->opening_stock = 1;
                $user->opening_stock_quantity = $request->quantity;
                $user->opening_stock_timestamp = date('Y-m-d H:i:s');
            }

            $res = array_key_exists('not_for_sale', $input);
            if ($res == false) {
                $user->not_for_sale = 0;
            } else {
                $user->not_for_sale = 1;
            }


            $user->name = $input['name'];
            $user->sale_price = $input['sale_price'];
            $user->purchase_price = $input['purchase_price'];
            $user->minimum_sale_price = $input['minimum_sale_price'];
            $user->maximum_discount = $input['maximum_discount'];
            $user->margin = $input['margin'];
            if ($request->sku){
                $user->sku = $input['sku'];

            }else{
                $user->sku = $this->genrateRandomInteger();
            }
            $user->short_description = $input['short_description'];
            $user->description = $input['description'];
            $user->warranty = $input['warranty'];
            $user->brand_id = $input['brand'];
            $user->location_rack = $input['location_rack'];
            $user->location_row = $input['location_row'];
            $user->location_position = $input['location_position'];
            $user->location_position = $input['location_position'];
            $user->category_id = $input['category'];
            if (Auth::user()->role == 2){
                $user->user_id = Auth::id();
            }elseif (Auth::user()->role == 3){
                $user->user_id = Auth::user()->parent_id;
            }else{
                $user->user_id = $input['shop'];
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

             $user->devices()->sync($request->devices,false);
            if ($request->hasFile('images')) {
                $allowedfileExtension = [ 'jpg', 'png', 'svg', 'jpeg', 'gif'];
                $files = $request->file('images');

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
                        $img = new ProductImage();
                        $img->product_id = $user->id;
                        $img->image = $filename;
                        if (array_key_exists("thumb$key", $input)) {
                            $img->thumb = 1;
                        }
                        $img->save();
                    }else {
                        Session::flash('error_message', 'warning!  Sorry Only Upload png , jpg , jpeg ,svg ,gif ');
                        return redirect()->back();
                    }
                }
            }
            DB::commit();
            Session::flash('success_message', 'Great! Product has been saved successfully!');
            if ($request->page == 2){
                return redirect()->back();
            }elseif ($request->page == 1){
                return redirect()->route('products.edit',$user->id);
            }
        } catch (Throwable $e) {
            info($e);

            DB::rollback();
            Session::flash('error_message', "$e");
            return redirect()->back();

        }
    }

    function genrateRandomInteger($len = 6)
    {
        $last = -1;
        $code = '';
        for ($i = 0; $i < $len; $i++) {
            do {
                $next_digit = mt_rand(0, 9);
            } while ($next_digit == $last);
            $last = $next_digit;
            $code .= $next_digit;
        }
        $code = "FF-" . $code;
        $sku = Product::where("sku", $code)->first();
        if ($sku) {
            $this->genrateRandomInteger();
        }
        return $code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $user = Product::find($id);
	    return view('admin.products.single', ['title' => 'Product detail', 'user' => $user]);
    }

	public function clientDetail(Request $request)
	{
		$user = Product::findOrFail($request->id);
		return view('admin.products.detail', ['title' => 'Product Detail', 'user' => $user]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $user = Product::find($id);
        if (Auth::user()->role == 2){
            $categories = Category::where('user_id',Auth::id())->get();
            $brands = Brand::where('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where('user_id',Auth::id())->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }elseif (Auth::user()->role == 3){
            $categories = Category::where('user_id',Auth::user()->parent_id)->get();
            $brands = Brand::where('user_id',Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::where('user_id',Auth::user()->parent_id)->orderBy('name', 'asc')->pluck('name','id')->toArray();

        }else{
            $categories = Category::get();
            $brands = Brand::orderBy('name', 'asc')->pluck('name','id')->toArray();
            $devices = Device::orderBy('name', 'asc')->pluck('name','id')->toArray();

        }

          $shops = User::where([["is_admin",1],["role",2]])->orderBy('name', 'asc')->pluck('name','id')->toArray();
        return view('admin.products.edit', ['title' => 'Edit Product','brands'=>$brands,'devices'=>$devices,'shops'=>$shops,'categories'=>$categories])->withUser($user);
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

	    $user = Product::find($id);
        DB::beginTransaction();

        try {

            $this->validate($request, [
                'name' => 'required|max:255',
                //		    'price' => 'required|numeric',
                //		    'sku' => 'required',
                'description' => 'required',
                //		    'shop' => 'required',
                'purchase_price' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'images*' => 'required|image|mimes:jpeg,png,jpg'
            ]);

            $input = $request->all();
            $res = array_key_exists('active', $input);
            if ($res == false) {
                $user->active = 0;
                $user->reason =  $request->disable_reason;
            } else {
                $user->active = 1;
                $user->reason =  null;

            }

            $is_regular = array_key_exists('is_regular', $input);

            if ($is_regular == false) {
                $user->is_regular = 0;

            } else {
                $user->is_regular = 1;

            }
            $res = array_key_exists('product_description', $input);
            if ($res == false) {
                $user->product_description = 0;
                $user->imei =  null;
                $user->serial_number =  null;
            } else {
                $user->product_description = 1;
                $user->imei =  $request->imei;
                $user->serial_number =  $request->serial_number;
            }
            $res = array_key_exists('stock', $input);
            if ($res == false) {
                $user->manage_stock = 0;

                $user->quantity = 0;
                $user->opening_stock = 0;
                $user->opening_stock_quantity = 0;
                $user->opening_stock_timestamp = null;
                $user->alert_quantity = 0;
            } else {
                $user->manage_stock = 1;
                $user->quantity = $request->quantity;
                $user->alert_quantity = $input['alert_quantity'];
                if(!$user->opening_stock){
                    $user->opening_stock = 1;
                    $user->opening_stock_quantity = $request->quantity;
                    $user->opening_stock_timestamp = date('Y-m-d H:i:s');
                }

            }
            $res = array_key_exists('not_for_sale', $input);
            if ($res == false) {
                $user->not_for_sale = 0;
            } else {
                $user->not_for_sale = 1;
            }


            $user->name = $input['name'];
            $user->sale_price = $input['sale_price'];
            $user->purchase_price = $input['purchase_price'];
            $user->minimum_sale_price = $input['minimum_sale_price'];
            $user->maximum_discount = $input['maximum_discount'];
            $user->margin = $input['margin'];
            if ($request->sku){
                $user->sku = $input['sku'];

            }else{
                $user->sku = $this->genrateRandomInteger();
            }
            $user->short_description = $input['short_description'];
            $user->description = $input['description'];
            $user->warranty = $input['warranty'];
            $user->brand_id = $input['brand'];
            $user->location_rack = $input['location_rack'];
            $user->location_row = $input['location_row'];
            $user->location_position = $input['location_position'];
            $user->location_position = $input['location_position'];
            $user->category_id = $input['category'];
            if (Auth::user()->role == 2){
                $user->user_id = Auth::id();
            }elseif (Auth::user()->role == 3){
                $user->user_id = Auth::user()->parent_id;
            }else{
                $user->user_id = $input['shop'];
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
            if(isset($request->devices)){
                $user->devices()->sync($request->devices);
            }else {
                $user->devices()->sync(array());
            }
            if ($request->hasFile('images')) {
                $allowedfileExtension = [ 'jpg', 'png', 'svg', 'jpeg', 'gif'];
                $files = $request->file('images');

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
                        $img = new ProductImage();
                        $img->product_id = $user->id;
                        $img->image = $filename;
                        $img->save();
                    }else {
                        Session::flash('error_message', 'warning!  Sorry Only Upload png , jpg , jpeg ,svg ,gif ');
                        return redirect()->back();
                    }
                }
            }
            DB::commit();
            Session::flash('success_message', 'Great! Product has been saved successfully!');
            if ($request->page == 2){
                return redirect()->back();
            }elseif ($request->page == 1){
                return redirect()->route('products.edit',$user->id);
            }
        } catch (Throwable $e) {
            info($e);

            DB::rollback();
            Session::flash('error_message', "$e");
            return redirect()->back();

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
	    $user = Product::find($id);

        $user->delete();
        Session::flash('success_message', 'Product successfully deleted!');
	    return redirect()->route('products.index');

    }
    public function deleteImage($id)
    {
	    $user = ProductImage::find($id);
        $user->delete();
        Session::flash('success_message', 'Product Image successfully deleted!');
	    return redirect()->back();
    }
    public function defaultImage($id)
    {

	    $user = ProductImage::find($id);
	    $old_images = ProductImage::where("product_id", $user->product_id)->get();
        foreach ($old_images as $old_image) {
            $old_image->thumb = 0;
            $old_image->save();
	    }
	    $user->thumb = 1;
        $user->save();
        Session::flash('success_message', 'Product Image successfully deleted!');
	    return redirect()->back();
    }
	public function deleteSelectedClients(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'products' => 'required',

		]);
		foreach ($input['products'] as $index => $id) {

			$user = Product::find($id);
				$user->delete();

		}
		Session::flash('success_message', 'Products successfully deleted!');
		return redirect()->back();

	}
}
