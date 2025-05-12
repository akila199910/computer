<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\Product;

class ProductRepository

{

    public function getProduct()
    {
        return Product::all();
    }
    public function create_product($request)
    {
        $file = 'user/user.png';
        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'image');
        }

        $product = new Product();
        $product->name = $request->product_name;
        $product->brand_id = $request->brand_name;
        $product->category_id = $request->category_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->status = $request->status == true ? 1 : 0;
        $product->image = $file;
        $product->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $product->id);
        $product->ref_no = $ref_no;
        $product->update();

        return [
            'id' => $product->id,
            'product_name' => $product->name,
            'image' => $product->image
        ];
    }

    // public function update_users($request)
    // {
    //     $user = User::find($request->id);
    //     $user->first_name = $request->first_name;
    //     $user->last_name = $request->last_name;
    //     $user->name = ucwords($request->first_name.' '. $request->last_name);
    //     $user->email = $request->email;
    //     $user->contact = $request->contact;
    //     $user->status = $request->status == true ? 1 : 0;
    //     $user->update();


    //     //Check permission available or not
    //     if (isset($request->permissions) && !empty($request->permissions)) {
    //         $user->syncPermissions($request->permissions);
    //     }

    //     return [
    //         'id' => $user->id,
    //         'first_name' => $user->first_name,
    //         'last_name' => $user->last_name,
    //         'full_name' => $user->name,
    //         'contact' => $user->contact,
    //         'email' => $user->email,
    //         'profile' => config('constants.aws_url') . $user->UserProfile->profile
    //     ];
    // }

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
