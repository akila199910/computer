<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository

{

    public function getCategories()
    {
        $categories = Category::with('brand')->get();

        return $categories;
    }
    public function create_brand($request)
    {
        $file = 'user/user.png';
        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'image');
        }

        $category = new Category();
        $category->name = $request->category_name;
        $category->brand_id = $request->brand_name;
        $category->status = $request->status == true ? 1 : 0;
        $category->image = $file;
        $category->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $category->id);
        $category->ref_no = $ref_no;
        $category->update();

        return [
            'id' => $category->id,
            'category_name' => $category->name,
            'brand_name' => $category->brand->name,
            'image' => $category->image
        ];
    }

    public function update_category($request)
    {
        $category = Category::find($request->id);

        $file = '';

        if (isset($request->image) && $request->image->getClientOriginalName()) {

            $file = file_upload($request->image, 'image');

        }else{

            if (!$category->image){
                $file = '';
            }
            else
                $file = $category->image;
        }

        $category->name = $request->category_name;
        $category->brand_id = $request->brand_name;
        $category->status = $request->status == true ? 1 : 0;
        $category->image = $file;
        $category->update();


        return [
            'id' => $category->id,
            'brand_name' => $category->name,
            'image' => $file
        ];
    }
}
