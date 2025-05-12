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

    public function delete_user($request)
    {
        $user = User::find($request->id);
        $user_business = UserBusiness::where('user_id', $user->id)->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'User Not Found'
            ];
        }

        $user->delete();
        $user_business->delete();

        return [
            'status' => true,
            'message' => 'Selected User Deleted Successfully!'
        ];
    }

}
