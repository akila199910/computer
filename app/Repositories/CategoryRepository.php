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
