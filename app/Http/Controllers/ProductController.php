<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Zipcode;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index($slug)
    {
        $store= Store::where('slug',$slug)->first();
        session()->put('store_id', $store->id);
        $products = Product::take(8)->get();
        $vegetables = Product::take(8)->get();
        $fruits = Product::take(8)->get();;
        $meals = Product::take(8)->get();
        $categories = Category::with('products')->where('store_id',$store->id)->whereNull('parent_id')->get();
        return view('pages.product',compact('products','vegetables','fruits','meals','store','categories'));
    }
    public function categoryProducts($slug)
    {
        $store= Store::select('shops.*')->join('categories','categories.store_id','shops.id')->where('categories.slug',$slug)->first();
        $products = Product::select('products.*')
                        ->join('categories','categories.id','products.category_id')
                        ->where('categories.slug',$slug)
                        ->get();
        return view('pages.category-product',compact('products','store'));
    }


    public function addToCompare( $id){
        
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product,$id);

        Session::put('cart',$cart);
        return redirect()->back();
        
    }

    public function getCart() {
        $zipcodes = Zipcode::get();
        if(!Session::has('cart')){
            $products = null;
            return view('pages.add-to-compare',compact('products','zipcodes'));
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $products = $cart->items;
        return view('pages.add-to-compare',compact('products','zipcodes'));
       
    }
    public function removeProduct($id){
        $cart = Session::get('cart');
        $products = $cart->items;
        foreach ($products as $key => $value)
        {
            if ($value['id'] == $id) 
            {                
                unset($products [$key]);            
            }
        }
        //put back in session array without deleted item
        Session::put('cart',$products);
        //then you can redirect or whatever you need
        return redirect()->back();
    }

    public function getShops($id){

        $shops = Store::where('zipcode_id',$id)->get();
        $view = view('pages.shop-dropdown',compact('shops'))->render();
        return response()->json(['shops'=>$view]);
    }
    public function productCompare(Request $request){
       
        $shops = $request->shop_id;
        
        $products = array_values(array_unique($request->product_id));
        
        $main = [];
        //get products by store using products name
        $product_not_found =[];
        $get_products=[];
        $total =0;
        for($i=0;$i<count($shops);$i++){
            for($o=0;$o<count($products);$o++){
                $split_product = explode('--',$products[$o]);
                
                 //$product= Product::select('id','name','price','shop_id')->where('name',$products[$o])->where('shop_id',$shops[$i])->orderBy('name', 'ASC')->first();
                 $product= Product::select('id','name','price','shop_id','size')->where(['name'=>$split_product[0],'size'=>$split_product[1]])->where('shop_id',$shops[$i])->orderBy('name', 'ASC')->first();
                 if($product!=''){
                    $get_products[] =$product->toArray();
                    $total = $total + Product::where(['id'=>$product->id,'name'=>$split_product[0],'size'=>$split_product[1]])->where('shop_id',$shops[$i])->sum('price');
                 }else{
                    $get_products[] =[]; //['shop_id'=>$shops[$i],'price'=>'','name'=>$products[$o]];
                    $product_not_found[] =['name'=>$split_product[0].' '.$split_product[1],'product_name'=>$split_product[0],'weight'=>$split_product[1]];
                 }
                 
            }
           
            //$total = Product::wherein('name',$products)->where('shop_id',$shops[$i])->sum('price');
         
        //     $zipcode = Store::select('zipcode_id')->wherein('id',$shops)->first()->zipcode_id;
        //     $stores = Store::where('zipcode_id',$zipcode)->pluck('id');
        //     $geta_products = Product::wherein('name',$products)->wherein('shop_id',$stores)->orderBy('name', 'ASC')->min('price');
        //     echo '<pre>';
        //     print_r($geta_products);
        // exit;
            $main[] = ['store'=>$shops[$i],'products'=>$get_products,'total_price'=>$total];
            $total =0;
            $get_products=[];
        }
       
        // sort array asc by price
        $keys = array_column($main, 'total_price');
        array_multisort($keys, SORT_DESC, $main);
        
        $store_header =[];
        $st_products = [];
        $st_total = [];
        // Rearrange shops and products in column wise
        for($j=0;$j<count($main);$j++){
            $store = Store::where('id',$main[$j]['store'])->first();
            $store_header[] =['shop_name'=>$store->name,'image'=>$store->image,'address'=>$store->address];
            $st_products[] = $main[$j]['products'];
            $st_total[] = $main[$j]['total_price'];
           
           
        }
       
        $fianl =[];
        
        $first_store = $st_products[0];
       
        for( $k=0;$k<count($first_store);$k++){
            $temp = [];
            for( $j=0;$j<count($st_products);$j++){
                $store = $st_products[$j];
                if(isset($store[$k])){
                    //print_r($store[$k]);
                    $temp[] = $store[$k];
                }else{
                    
                    $temp[] = [];
                }
                
            }

            $fianl[] = $temp;
        }
        
        $mainLoop=[];
        foreach($fianl as $key=>$value){
            if(empty(array_column($value, 'price'))){
               $minvalue =''; 
            }else{
                $minvalue = min(array_column($value, 'price'));
            }
            
           
            $innerLoop =[];
            foreach($value as $inner){
               if(count($inner)){
                   if($minvalue==''){
                       $inner['low']=0;
                   }else{
                      if($inner['price']==$minvalue){
                        $inner['low']=1;
                    }else{
                        $inner['low']=0; 
                    } 
                   }
                    
               }
               $innerLoop[] = $inner;
                
            }

            $mainLoop[] = $innerLoop;
          
       
    }  
    $fianl =$mainLoop;
    // echo '<pre>';
    // print_r($fianl);
    // exit;
        //Session::flush();
        return view('pages.compare-result',compact('store_header','fianl','st_total','product_not_found'));
    }
    // public function productCompare(Request $request){

    //     $shops = $request->shop_id;
        
    //     $products = array_values(array_unique($request->product_id));
       
    //     $main = [];
    //     for($i=0;$i<count($products);$i++){
    //         $get_products = Product::with('shop')->where('name',$products[$i])->whereIn('shop_id',$request->shop_id)->orderBy('name','ASC')->get();
            
    //         $shop_product =[];
    //         foreach($get_products as $product){
    //             $shop_product[] = ['shop_id'=>$product->shop->id,'shop_name'=>$product->shop->name,'image'=>$product->shop->image,'address'=>$product->shop->address,'product_name'=>$product->name,'price'=>$product->price];
    //         }
    //         $main[] = $shop_product;
           
    //     }
    //     echo '<pre>';
    //     print_r($main);
    //     exit;
    //     $total_shop_price = [];
    //     for($i=0;$i<count($shops);$i++){
    //         $get_total = Product::with('shop')->whereIn('name',$products)->where('shop_id',$request->shop_id[$i])->sum('price');
    //         $total_shop_price[] = ['shop_total_price' => $get_total,'shop_id'=>$request->shop_id[$i]];
    //     }
    
    //     Session::flush();
    //     return view('pages.compare-result',compact('main','total_shop_price'));
    // }
    // public function productCompare(Request $request){

    //     $shops = $request->shop_id;
    //     $products = array_values(array_unique($request->product_id));
        
    //     $main = [];
    //     for($i=0;$i<count($products);$i++){
    //         $get_products = Product::with('shop')->where('name',$products[$i])->whereIn('shop_id',$request->shop_id)->orderBy('name','ASC')->get();
            
    //         $shop_product =[];
    //         foreach($get_products as $product){
    //             $shop_product[] = ['shop_name'=>$product->shop->name,'image'=>$product->shop->image,'address'=>$product->shop->address,'product_name'=>$product->name,'price'=>$product->price];
    //         }
    //         $main[] = $shop_product;
           
    //     }
    //     echo '<pre>';
    //     print_r($main);
    //     exit;
    //     Session::flush();
    //     return view('pages.compare-result',compact('main'));
    // }
    public function searchProduct(Request $request){
        $store ='';
        $products = Product::select('products.*')
        ->join('shops', 'shops.id', '=', 'products.shop_id')
        ->join('zipcodes', 'zipcodes.id', '=', 'shops.zipcode_id')
        ->where('zipcodes.zip_code', \Session::get('post_code'))
        ->where('shops.id',session()->get('store_id'))
        ->where('products.name','like','%'.$request->title.'%')->get();
        return view('pages.category-product',compact('products','store'));
       
    }

    public static  function getAbsoluteCheapest($store_id,$product_name,$size){
        $zipcode = Store::where('id',$store_id)->first()->zipcode_id;
        $stores = Store::where('zipcode_id',$zipcode)->pluck('id');
        // $product_price = Product::wherein('shop_id',$stores)->where('name',$product_name)->min('price');
        $product_price = Product::select('shop_id','price')->wherein('shop_id',$stores)->where(['name'=>$product_name,'size'=>$size])->orderBy('price','asc')->first();
        $store_info  = Store::where('id',$product_price->shop_id)->first();
        return ['price'=>$product_price->price,'shop'=>$store_info];
    }
    public static  function getAbsoluteCheapestByProduct($product_name,$product_weight){
        
        $zipcode_id = Zipcode::where('zip_code',\Session::get('post_code'))->first()->id;
        
        $stores = Store::where('zipcode_id',$zipcode_id)->pluck('id');
       
        // $product_price = Product::wherein('shop_id',$stores)->where('name',$product_name)->min('price');
        $product_price = Product::select('shop_id','price')->wherein('shop_id',$stores)->where(['name'=>$product_name,'size'=>$product_weight])->orderBy('price','asc')->first();
        $store_info  = Store::where('id',$product_price->shop_id)->first();
        return ['price'=>$product_price->price,'shop'=>$store_info];
    }
    public function clearCart(){
        //Session::flush();
        \Session::forget('cart');
        return redirect('cart')->with('success','Cart item removed successfully.');
    }
}
