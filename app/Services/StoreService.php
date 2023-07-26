<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Zipcode;
use Illuminate\Support\Str;
class StoreService {

    public function checkZipCode($zipcode)
    {
        $check_code = Zipcode::where('zip_code',$zipcode)->first();
        $zipcode_id ='';
        if($check_code== null){
            $create_zipcode = Zipcode::create(['zip_code'=>$zipcode]);
            $zipcode_id = $create_zipcode->id;
        }else{
            $zipcode_id = $check_code->id;
        }
        return $zipcode_id;
    }


    public function checkStore($zipcode_id,$name,$location,$logo){

        $check_store_in_database = Store::where(['name'=>$name,'zipcode_id'=>$zipcode_id])->first();
        if($check_store_in_database==null){
            $image_name = str_replace(' ', '', $name);
            if(!file_exists(public_path('uploads/'.$image_name.'.jpg'))){
                $store_image = file_get_contents($logo);
                $upload_path = public_path('uploads/'.$image_name.'.jpg');
                file_put_contents($upload_path, $store_image);
            }

            $create_store = Store::create([
                'name'=>$name,'zipcode_id'=>$zipcode_id,'address'=>$location,
                'phone_number'=>'123456789','image'=>$image_name.'.jpg','store_banner'=>$image_name.'.jpg'
            ]);
            $store_id =$create_store->id;
            $slug = $this->createSlug($name,$store_id);
            $store_slug = Store::find($store_id);
            $store_slug->slug= $slug;
            $store_slug->save();
        }else{
            $store_id = $check_store_in_database->id;
        }
        
        return $store_id;
    }

    public function checkCategory($store_id,$category_title) {
        $category_info = Category::where(['store_id' => $store_id, 'title' => $category_title])->first();
        if ($category_info == null) {
            $create_category = Category::create([
                'store_id' => $store_id,
                'title' => $category_title
            ]);
            $category_id = $create_category->id;
            $cat_slug = $this->createCategorySlug($category_title, $category_id);
            $update_cat_slug = Category::find($category_id);
            $update_cat_slug->slug = $cat_slug;
            $update_cat_slug->save();
        } else {
            $category_id = $category_info->id;
        }
        return $category_id;
    }
    public function checkSubCategory($store_id,$category_id,$category_title) {
        $category_info = Category::where(['store_id' => $store_id, 'title' => $category_title,'parent_id'=>$category_id])->first();
        if ($category_info == null) {
            $create_sub_category = Category::create([
                'store_id' => $store_id,
                'title' => $category_title,
                'parent_id'=>$category_id
            ]);
            $sub_category_id = $create_sub_category->id;
            $cat_slug = $this->createCategorySlug($category_title, $sub_category_id);
            $update_cat_slug = Category::find($sub_category_id);
            $update_cat_slug->slug = $cat_slug;
            $update_cat_slug->save();
        } else {
            $sub_category_id = $category_info->id;
        }
        return $sub_category_id;
    }

    public function chekProduct($store_id,$category_id,$name,$price,$size,$image){
        $check_product = Product::where(['shop_id'=>$store_id,'category_id'=>$category_id,'name'=>$name,'size'=>$size])->first();
        $price = ltrim($price, '$');
        $remove_text_from_price = explode(' ',$price);
        $price=$remove_text_from_price[0];
      
        if($check_product==null){

            Product::create(
                [
                    'shop_id'=>$store_id,
                    'category_id'=>$category_id,
                    'name'=>$name,
                    'price'=>$price,
                    'size'=>$size,
                    'image'=>$image
                ]);
        }else{
            Product::where('id',$check_product->id)
                ->update(
                    [
                        'shop_id'=>$store_id,
                        'category_id'=>$category_id,
                        'name'=>$name,
                        'price'=>$price,
                        'size'=>$size,
                        'image'=>$image
                    ]);
        }
    }
    public function createSlug($title, $id)
    {

        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getStoreRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', (string)$slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10000; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getStoreRelatedSlugs($slug, $id)
    {
        return Store::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function createCategorySlug($title, $id)
    {

        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getCategoryRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', (string)$slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10000; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }
    protected function getCategoryRelatedSlugs($slug, $id)
    {
        return Category::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }
}