<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository

{

    public function getBrands()
    {
        return Brand::all();
    }
    public function create_brand($request)
    {
        $file = 'user/user.png';
        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'image');
        }

        $brand = new Brand();
        $brand->name = $request->brand_name;
        $brand->status = $request->status == true ? 1 : 0;
        $brand->image = $file;
        $brand->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $brand->id);
        $brand->ref_no = $ref_no;
        $brand->update();

        return [
            'id' => $brand->id,
            'brand_name' => $brand->name,
            'image' => $brand->image
        ];
    }

    public function update_brand($request)
    {
        $brand = Brand::find($request->id);

        $file = '';

        if (isset($request->image) && $request->image->getClientOriginalName()) {

            $file = file_upload($request->image, 'image');

        }else{

            if (!$brand->image)
                $file = '';
            else
                $file = $brand->image;
        }

        $brand->name = $request->brand_name;
        $brand->status = $request->status == true ? 1 : 0;
        $brand->update();


        return [
            'id' => $brand->id,
            'brand_name' => $brand->name,
            'image' => $file
        ];
    }

    // public function delete_user($request)
    // {
    //     $user = User::find($request->id);
    //     $user_business = UserBusiness::where('user_id', $user->id)->first();

    //     if (!$user) {
    //         return [
    //             'status' => false,
    //             'message' => 'User Not Found'
    //         ];
    //     }

    //     $user->delete();
    //     $user_business->delete();

    //     return [
    //         'status' => true,
    //         'message' => 'Selected User Deleted Successfully!'
    //     ];
    // }

}
