<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    private $order_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

        $this->order_repo = new OrderRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Order');

        if ($check_premission == false) {
            return abort(403);
        }
        //End

        if (isset($request->json)) {

            $orders = $this->order_repo->getOrder();

            if($user->hasRole('technician')) {
                $orders = $orders->where('technician_id', $user->id);
            }

                if ($request->filled('status')) {
                    $orders->where('status', $request->status);
                }

                if ($request->filled('order_date')) {
                    $orders->whereDate('order_date', $request->order_date);
                }

            $data =  DataTables()::of($orders)
                ->addIndexColumn()

                ->addColumn('customer', function ($item) {
                    return  Str::limit(ucwords($item->customer_info? $item->customer_info->name : "N/A"), 30);
                })
                ->addColumn('technician', function ($item) {
                    return  Str::limit(ucwords($item->technician_info ? $item->technician_info->name : "N/A"), 30);
                })
                ->addColumn('created_by', function ($item) {
                    return  Str::limit(ucwords($item->created_by_info ? $item->created_by_info->name : "N/A"), 30);
                })
                ->addColumn('approved_by', function ($item) {
                    return  Str::limit(ucwords($item->approved_by_info ? $item->approved_by_info->name : "N/A"), 30);
                })
                ->addColumn('description', function ($item) {
                    return  Str::limit(ucwords($item->description), 30);
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-warning badge-border">Pending</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-primary badge-border">Approved</span>';
                    }

                    if ($item->status == 2) {
                        return '<span class="badge badge-soft-success badge-border">Completed</span>';
                    }
                    if ($item->status == 3) {
                        return '<span class="badge badge-soft-danger badge-border">Cancelled</span>';
                    }
                })
                ->addColumn('total_cost', function ($item) {
                    return  $item->total_cost  ?? "N\A";
                })
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_url = route('business.order.update.form', $item->ref_no);
                    $view_url = route('business.order.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns2($actions, $user, 'Order', $edit_url, $item->id,  $view_url,$item);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        '</div></div>';

                    return $action;
                })
                ->rawColumns(['action', 'status', 'customer', 'technician', 'created_by','approved_by','description'])
                ->make(true);

                // dd($data);
            return $data;
        }

        return view('business.orders.index');
    }

    public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Order');

        if ($check_premission == false) {
            return abort(403);
        }
        //END

        $customers = User::role('customer')->where('status',1)->get();
        $technicians = User::role('technician')->where('status',1)->get();


        return view('business.orders.create',[
            'customers' => $customers,
            'technicians' =>$technicians
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required',
                'description' => 'required|max:190',
                'technician_name' => 'required',
                'price'=>'nullable'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $data = $this->order_repo->create_order($request);

        $data['status'] = true;
        $data['message'] = 'New Order Created Successfully!';
        $data['route'] = route('business.order');

        return response()->json($data);
    }

    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Order');

        if ($check_premission == false) {
            return abort(403);
        }

        $order = Order::where(['ref_no' => $id])->first();
        $customers = User::role('customer')->where('status',1)->get();
        $technicians = User::role('technician')->where('status',1)->get();


        return view('business.orders.update',[
            'customers'=>$customers,
            'technicians'=>$technicians,
            'order' =>$order
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make(
            $request->all(),
            [
            'customer_name' => 'required',
            'technician_name' => 'required',
            'price' => 'nullable|numeric',
            'description' => 'required|max:190',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $data = $this->order_repo->update_order($request);
        $data['status'] = true;
        $data['message'] = 'Order Updated Successfully!';
        $data['route'] = route('business.order');

        return response()->json($data);

    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Order');

        if ($check_premission == false) {
            return abort(403);
        }
        // End

        $order = Order::Where(['ref_no' => $ref_no])->first();

        if (!$order) {
            return abort(404);
        }

        return view('business.orders.view_details', [
            'order' =>  $order
        ]);
    }

    public function change(Request $request)
    {

        $order = Order::find($request->id);

        if($request->status != 3){

            if ($order->total_cost == null) {
                return response()->json(['status' => "error",  'message' => 'Please update price in the order!']);
            }

            if ($order->technician_id == null) {
                return response()->json(['status' => "error",  'message' => 'Please update technician in the order!']);
            }
        }

        $data = $this->order_repo->change_order($request);
        $data['status'] = true;
        $data['message'] = 'Order Approved Successfully!';
        $data['route'] = route('business.order');

        return response()->json($data);
    }


}
