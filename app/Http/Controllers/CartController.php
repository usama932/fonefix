<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Zipcode;
use Illuminate\Http\Request;
use App\Models\Store;
class CartController extends Controller
{
     /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart()
    {
        $zipcodes = Zipcode::get();
        $area_stores = Store::select('shops.id','shops.name','shops.address')->join('zipcodes', 'zipcodes.id', '=', 'shops.zipcode_id')
            ->where('zipcodes.zip_code', \Session::get('post_code'))
            ->get();
        return view('pages.cart',compact('zipcodes','area_stores'));
    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name"      => $product->name,
                "quantity"  => 1,
                "price"     => $product->price,
                "size"      =>$product->size,
                "image"     => $product->image
            ];
        }

        session()->put('cart', $cart);
        $total = count((array)session()->get('cart', []));
        return response()->json($total);
        //return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    function getCartCount(){

        return count(session()->get('cart', []));
    }
}
