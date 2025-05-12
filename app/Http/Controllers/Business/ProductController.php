<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private $product_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

        $this->product_repo = new ProductRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Product');

        if ($check_premission == false) {
            return abort(403);
        }
        //End

        if (isset($request->json)) {

            $products = $this->product_repo->getProduct();



            $data =  datatables()::of($products)
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
                ->addColumn('category', function ($item) {
                    return  Str::limit(ucwords($item->category->name), 30);
                })
                ->addColumn('description', function ($item) {
                    return  Str::limit(ucwords($item->description), 30);
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
                    $user = Auth::user();
                    $edit_url = route('business.product.update.form', $item->ref_no);
                    $view_url = route('business.product.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'Product', $edit_url, $item->id,  $view_url);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        '</div></div>';

                    return $action;
                })
                ->rawColumns(['action', 'status', 'profile', 'name', 'brand', 'category'])
                ->make(true);

            return $data;
        }

        return view('business.product.index');
    }

    public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Product');

        if ($check_premission == false) {
            return abort(403);
        }
        //END

        $brands = Brand::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();

        return view('business.product.create',['brands'=>$brands,'categories'=>$categories]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'brand_name' => 'required',
                'image' => 'nullable|mimes:png,jpg,jpeg',
                'category_name' => 'required',
                'product_name' => 'required|regex:/^[a-z A-Z]+$/u|max:30',
                'description' => 'nullable|max:190',
                'price' => 'required|numeric',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $data = $this->product_repo->create_product($request);

        $data['status'] = true;
        $data['message'] = 'New Product Created Successfully!';
        $data['route'] = route('business.product');

        return response()->json($data);
    }

    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Product');

        if ($check_premission == false) {
            return abort(403);
        }

        $product = Product::where(['ref_no' => $id])->first();
        $brands = Brand::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();


        return view('business.product.update',[
            'categories' => $categories,
            'brands' => $brands,
            'product' => $product
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make(
            $request->all(),
            [
            'brand_name' => 'required',
            'category_name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'description' => 'nullable|max:190',
            'product_name' =>['required',
                                'regex:/^[a-z A-Z]+$/u','max:30',
                                Rule::unique('products', 'name')->ignore($id)->whereNull('deleted_at')
                            ],
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        // if ($request->status == false) {

        //     $product = Product::where('category_id', $id)->get();
        //     if ($product->count() > 0) {
        //         return response()->json([
        //             'status' => "error",
        //             'message' => 'Category is associated with products'
        //         ]);
        //     }
        // }

        $data = $this->product_repo->update_product($request);
        $data['status'] = true;
        $data['message'] = 'Product Updated Successfully!';
        $data['route'] = route('business.product');

        return response()->json($data);

    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Product');

        if ($check_premission == false) {
            return abort(403);
        }
        // End

        $product = Product::Where(['ref_no' => $ref_no])->first();

        if (!$product) {
            return abort(404);
        }

        return view('business.product.view_details', [
            'product' =>  $product
        ]);
    }
}
