<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    private $brand_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

        $this->brand_repo = new BrandRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Brand');

        if ($check_premission == false) {
            return abort(403);
        }
        //End

        if (isset($request->json)) {

            $brands = $this->brand_repo->getBrands();



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

        return view('business.brands.index');
    }

    public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Brand');

        if ($check_premission == false) {
            return abort(403);
        }
        //END

        return view('business.brands.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'brand_name' => 'required|regex:/^[a-z A-Z]+$/u|max:30',
                'image' => 'nullable|mimes:png,jpg,jpeg',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $data = $this->brand_repo->create_brand($request);

        $data['status'] = true;
        $data['message'] = 'New Brand Created Successfully!';
        $data['route'] = route('business.brands');

        return response()->json($data);
    }
}
