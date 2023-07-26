<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpiderController extends Controller
{
    public function spider()
    {
        $main = [];
        $root = scandir('/root/spider');
        $root_path = '/root/spider/';
        unset($root[0]);
        unset($root[1]);
        $all_files=[];
        $stores = array_values($root);
        foreach($stores as $store){
            $spider_dates =  scandir($root_path .$store,SCANDIR_SORT_DESCENDING);
            array_splice($spider_dates, 1);
            // echo '<pre>';
            // print_r( $spider_dates);
            // exit;
            // unset($spider_dates[0]);
            // unset($spider_dates[1]);
            foreach($spider_dates as $date){
                
                $zipCodes = scandir($root_path .$store.'/'.$date);
                unset($zipCodes[0]);
                unset($zipCodes[1]);
                foreach($zipCodes as $code)
                {
                    $zipCode = scandir($root_path .$store.'/'.$date.'/'.$code);        
                    unset($zipCode[0]);
                    unset($zipCode[1]);
                    $zipCode = array_values($zipCode);
                    
                    $all_files[]= ['zipcode'=>$code,'files'=>$zipCode];
                   
                }
                $all_dates[] = ['dates' => $date, 'codes' => $all_files];

                $all_files = [];
            }
            $all_stores[] = ['store' => $store, 'date' => $all_dates];
            $all_dates = [];
         
        }

        // echo '<pre>';
        //         print_r($all_stores);
        //         exit; 
        return view('admin.spider.index', compact('all_stores'));

    }
    public function spider1()
    {
        $root = scandir('/root/spider');
        $root_path = '/root/spider/';
        unset($root[0]);
        unset($root[1]);
       
        $stores = array_values($root);
        
        foreach($stores as $store){
            //get sipder data date wise
            $zipCodes = scandir($root_path .$store.'/'.date('d-m-Y'));
            $spider_dates =  scandir($root_path .$store);
            unset($spider_dates[0]);
            unset($spider_dates[1]);
            $spider_dates = array_values($spider_dates);
            print_r($spider_dates);
            exit;
            $date_path=$root_path .$store.'/'.date('d-m-Y');
            unset($zipCodes[0]);
            unset($zipCodes[1]);
       
            foreach($zipCodes as $zipCode){
                echo $code = $zipCode;
                echo '<br>';
                //exit; 
                $check_zipcode = Zipcode::where('zip_code',$code)->first();
                
                if($check_zipcode == ''){
                
                    $check_zipcode = Zipcode::create(['zip_code'=>$code]);
                }
                $check_store = Store::where(['name'=>$store,'zipcode_id'=>$check_zipcode->id])->first();
                if($check_store==''){
                    $check_store = Store::create([
                        'name'=>$store,
                        'zipcode_id'=>$check_zipcode->id,
                        'phone_number'=>'6802003961',
                        'address'=>'2965 Cropsey Avenue',
                        'slug'=>$this->slugify($store),
                        'image'=>'579cb2af-b731-4cec-826d-bc1c1ecbf335.webp',
                        'store_banner'=>'579cb2af-b731-4cec-826d-bc1c1ecbf335.webp'
                    ]);
                }
              
                $code_path = $date_path.'/'.$code;
                $zipCode = scandir($date_path.'/'.$zipCode);        
                unset($zipCode[0]);
                unset($zipCode[1]);
                $zipCode = array_values($zipCode);
                if(count($zipCode)==1 && $zipCode[0] == 'storeinfo.csv'){
                    continue;
                }
                if(count($zipCode)>0){
                   
                    $categories = $this->csvToArray($code_path.'/'.$store."categories.csv",',');
                    $key = array_search($store."categories.csv", $zipCode);
                    unset($zipCode[$key]);
                    $zipCode = array_values($zipCode);
                    
                    if(count($categories)){
                        foreach($categories as $category){
                            $check_category = Category::where(['title'=>ucwords($category['name']),'store_id'=>$check_store->id])->first();
                            if($check_category==''){
                                $check_category = Category::create(['title'=>ucwords($category['name']),
                                'store_id'=>$check_store->id,'image'=>'10_Produce-Thumbnail_20_300x300.jpg']);
                            }
                            if(count($zipCode))
                            {
                                foreach($zipCode as $folder){
                                    if($folder=='storeinfo.csv'){
                                        continue;
                                    }
                               
                                    $products = $this->csvToArray($code_path.'/'.$folder,',');
                                    
                                    if(count($products)){
                                        foreach($products as $product){
                                           
                                           $price = $product['prices'];
                                            
                                           $price = ltrim($price, $price[0]);
                                          
                                            Product::updateOrCreate([
                                                    "name"=>ucwords($product['name']),
                                                    'shop_id'=>$check_store->id,
                                                    'category_id'=>$check_category->id
                                                ],
                                                [
                                                    'price'=>$price,
                                                    'size'=>"KG",
                                                    "image"=>$product['image']
            
                                                ]
                                            );
                                        }
                                    }
                                    //unlink($code_path.'/'.$folder);
                                    
                                    //dd($products);
                                }
                                //unlink($code_path.'/'.$folder);
                            }
                        }
                    }
                    //unlink($code_path.'/'.$store."categories.csv");
               
                }
            }
              
        }
        echo "done";
    }
}
