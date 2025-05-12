<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private $category_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

        $this->category_repo = new CategoryRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Category');

        if ($check_premission == false) {
            return abort(403);
        }
        //End

        if (isset($request->json)) {

            $brands = $this->category_repo->getCategories();



            $data =  datatables()::of($brands)
                ->addIndexColumn()
                ->addColumn('profile', function ($item) {
                    $url = config('aws_url.url') . ($item->image);

                    if ($item->image == ''||$item->image == 'user/user.png') {
                        $url = asset('layout_style/img/user.jpg');
                    }
                    return '<img src="' . $url . '" border="0" width="50" height="50" style="border-radius:50%" class="stylist-image" align="center" />';
                })
                ->addColumn('name', function ($item) {
                    return  Str::limit(ucwords($item->name), 30);
                })
                ->addColumn('brand', function ($item) {
                    return  Str::limit(ucwords($item->brand->name), 30);
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })
                ->addColumn('action', function ($item) {
                    // $user = Auth::user();
                    // $edit_url = route('business.users.update.form', $item->ref_no);
                    // $view_url = route('business.users.view_details', $item->ref_no);

                    // $actions = '';
                    // $actions .= action_btns($actions, $user, 'User', $edit_url, $item->id,  $view_url);

                    // $action = '<div class="dropdown dropdown-action">
                    //     <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    //         <i class="fa fa-ellipsis-v"></i>
                    //     </a>
                    // <div class="dropdown-menu dropdown-menu-end">'
                    //     . $actions .
                    //     '</div></div>';

                    return '';
                })
                ->rawColumns(['action', 'status', 'profile', 'permissions'])
                ->make(true);

            return $data;
        }

        return view('business.category.index');
    }

    public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Category');

        if ($check_premission == false) {
            return abort(403);
        }
        //END

        $brands = Brand::where('status', 1)->get();

        return view('business.category.create',['brands'=>$brands]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'brand_name' => 'required',
                'image' => 'nullable|mimes:png,jpg,jpeg',
                'category_name' => 'required|regex:/^[a-z A-Z]+$/u|max:30'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $data = $this->category_repo->create_brand($request);

        $data['status'] = true;
        $data['message'] = 'New Category Created Successfully!';
        $data['route'] = route('business.category');

        return response()->json($data);
    }
}
