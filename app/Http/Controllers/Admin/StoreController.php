<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\StoreService;
use Illuminate\Support\Str;
class StoreController extends Controller
{
    protected $store_service ;

    public function __construct(StoreService $store_service)
    {
     $this->store_service  = $store_service;  
    }
    public function index()
    {
    }
    public function stores($id)
    {

        $title = 'Stores';
        return view('admin.stores.index', compact('title', 'id'));
    }

    public function getStores(Request $request, $id)
    {
        $columns = array(
            0 => 'name',
            1 => 'phone_number',
            2 => 'address',
            3 => 'action'

        );

        $totalData = Store::where('zipcode_id', $id)->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = Store::where('zipcode_id', $id)->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
            $totalFiltered = Store::where('zipcode_id', $id)->count();
        } else {
            $search = $request->input('search.value');
            $users = Store::where([
                ['zip_code', 'like', "%{$search}%"],
            ])
                ->orWhere('zip_code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")

                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
            $totalFiltered = Store::where('zipcode_id', $id)
                ->where([
                    ['zip_code', 'like', "%{$search}%"],
                ])
                ->orWhere('name', 'like', "%{$search}%")

                ->orWhere('created_at', 'like', "%{$search}%")
                ->count();
        }


        $data = array();
        if ($users) {
            foreach ($users as $r) {
                

                $products = route('admin.shop-products',$r->id);

                $nestedData['name'] = $r->name;
                $nestedData['phone_number'] = $r->phone_number;
                $nestedData['address'] = $r->address;
                $nestedData['action'] = '
                <div>
                <td>
                    
              
                    <a title="show products" target="_blank" class="btn btn-sm btn-clean btn-icon" href="' . $products . '">
                         <i class="icon-1x text-dark-50 flaticon-eye"></i>
                    </a>
                </td>
                </div>
            ';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
    public function show($id)
    {
    }


    public function StoreJSON(){
        $title ="Import JSON File";
        return view('admin.stores.uploadjson',compact('title'));
    }

    public function StoreJSONData(Request $request) {
        if ($request->hasFile('name')) {
            $file = $request->file('name');
            $path = $file->path(); // Get the path of the uploaded file

            $json = json_decode(file_get_contents($path), true); // Read and parse the JSON file
            if(count($json)>0){
                foreach($json as $data){
                    //check zipcode exist or not
                    // if exist then return that zipcode id
                    //if not exist then create and return zipcode id
                    $zipcode_id = $this->store_service->checkZipCode($data['zipcode']);
                    //params zipcode_id, store name,store location, store logo
                    $store_id = $this->store_service->checkStore($zipcode_id,$data['store_name'],$data['store_location'],$data['store_logo']);

                    $category_id = $this->store_service->checkCategory($store_id,$data['category']);
                    if($data['sub_category']!=''){
                        $category_id = $this->store_service->checkSubCategory($store_id,$category_id,$data['sub_category']);
                    }
                    

                    $this->store_service->chekProduct($store_id,$category_id,$data['product_title'],$data['regular_price'],$data['weight'],$data['image_url']);


                }
               
            }

            
        }
        return redirect()->back()->with('success_message', 'JSON file uploaded successfully.');
    }
}
