<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;;
use Exception;
use Validator;
use Illuminate\Support\Facades\DB;
class ConfigController extends Controller
{
    public function getBrands()
    {
        $brands = Brand::withCount('products')->get();
        if($brands->isNotEmpty()){
            return response()->json(['brands'=>$brands,'message'=>"brands list",'error'=>false],200);
        }else{
            return response()->json(['message'=>"No brands found",'error'=>true],200);
        }
    }

    public function getCategories()
    {
        $categories = Category::get();
        if($categories->isNotEmpty()){
            return response()->json(['categories'=>$categories,'message'=>"brands list",'error'=>false],200);
        }else{
            return response()->json(['message'=>"No category found",'error'=>true],200);
        }
    }

    public function getGenericData(){
        $categories = Category::get();
        $brands = Brand::get();
       
        return response()->json([
                        'categories'=>$categories,
                        'brands'=>$brands,
                        'message'=>"brands list",
                        'error'=>false],200);

    }
}