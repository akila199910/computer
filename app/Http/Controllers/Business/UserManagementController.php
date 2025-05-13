<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserManageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserManagementController extends Controller
{
    private $user_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

        $this->user_repo = new UserManageRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_User');

        if ($check_premission == false) {
            return abort(403);
        }
        //End

        if (isset($request->json)) {

            $user = auth()->user();
            $allowedRoles = [];

            if ($user->hasPermissionTo('Read_Reception')) {
                $allowedRoles[] = 'reception';
            }
            if ($user->hasPermissionTo('Read_Manager')) {
                $allowedRoles[] = 'manager';
            }
            if ($user->hasPermissionTo('Read_Customer')) {
                $allowedRoles[] = 'customer';
            }
            if ($user->hasPermissionTo('Read_Technician')) {
                $allowedRoles[] = 'technician';
            }

            $users = User::where('id', '!=', $user->id)
                        ->whereHas('roles', function ($query) use ($allowedRoles) {
                            $query->whereIn('name', $allowedRoles);
                        })
                        ->get();


            $data =  DataTables()::of($users)
                ->addIndexColumn()
                ->addColumn('profile', function ($item) {
                    $url = config('aws_url.url') . ($item->UserProfile->profile);

                    if ($item->UserProfile->profile == ''||$item->UserProfile->profile == 'user/user.png') {
                        $url = asset('layout_style/img/user.jpg');
                    }
                    return '<img src="' . $url . '" border="0" width="50" height="50" style="border-radius:50%" class="stylist-image" align="center" />';
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })

                ->addColumn('permissions', function ($item) {
                    $permissions = $item->getDirectPermissions()->pluck('name')->toArray();

                    $data = '';
                    foreach ($permissions as $perm) {
                        $name = explode('_', $perm);

                        $data .= '<a class="dropdown-item" style="cursor: none;" href="javascript:;" title="' . $perm . '">' . implode(' ', $name) . '</a>';
                    }

                    return '<div class="dropdown action-label scrollbar">
                                <a class="custom-badge status-purple dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" >
                                    View Permissions
                                </a>
                                <div class="dropdown-menu dropdown-menu-end status-staff" style="max-height: 200px; position: relative;overflow: hidden;width: 100%;overflow-y: scroll;">
                                    ' . $data . '
                                </div>
                            </div>';
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

        return view('business.users.index');
    }

        public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_User');

        if ($check_premission == false) {
            return abort(403);
        }
        //END

        $action = ['Read', 'Create', 'Update', 'Delete'];

        $permissions = [
                        'Reception', 'Manager', 'Technician', 'Customer', 'Order', 'Category', 'Brand', 'Product',
                    ];

        $permission_list = [];

        foreach ($permissions as $perm) {
            $permission_list[$perm] = [];
            foreach ($action as $act) {
                $permission_list[$perm][] = $act . '_' . $perm;
            }
        }

        return view('business.users.create', [
            'permissions' => $permissions,
            'permission_list' => $permission_list
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|regex:/^[a-z A-Z]+$/u|max:30',
                'last_name' => 'required|regex:/^[a-z A-Z]+$/u|max:30',
                'email' => 'required|email:rfc,dns|max:190|unique:users,email,NULL,id,deleted_at,NULL',
                'contact' => 'required|digits:10|unique:users,contact,NULL,id,deleted_at,NULL',
                'permissions' => 'nullable',
                'role' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }


        $request->merge([
            'password' => Str::random(16)
        ]);

        $data = $this->user_repo->create_users($request);

        $data['status'] = true;
        $data['message'] = 'New User Created Successfully!';
        $data['route'] = route('business.users');

        return response()->json($data);
    }

}
