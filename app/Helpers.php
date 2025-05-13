<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

if (!function_exists('file_upload')) {

    function file_upload($file, $path)
    {
        $path_store = Storage::disk('s3')->put($path, $file);

        return $path_store;
    }
}

if (!function_exists('mailNotification')) {
    function mailNotification($data)
    {
        Mail::send($data["view"], $data, function ($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });
    }
}

if (!function_exists('refno_generate')) {


    function refno_generate($length, $type, $id)
    {
        // 0 = Digits
        if ($type == 0) {
            $pool = '0123456789';
        }

        // 1 = Letter Only
        if ($type == 1) {
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        // 2 = Digit and Letter
        if ($type == 2) {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $id_length = strlen($id);
        $ref_length = $length - $id_length;

        $ref_no = $id;
        if ($ref_length > 0) {
            $otp = substr(str_shuffle(str_repeat($pool, $ref_length)), 0, $ref_length);
            $ref_no = $otp . $id;
        }

        return $ref_no;
    }
}

if (!function_exists('action_btns')) {
    function action_btns($action, $user, $permission, $edit_url, $route_id, $view_url)
    {
        if ($permission == 'User') {
            $roles = ['Reception', 'Manager', 'Technician', 'Customer'];

            foreach ($roles as $role) {
                if ($edit_url != '' && $user->hasPermissionTo('Update_' . $role)) {
                    $action .= '<a class="dropdown-item" title="Edit" href="' . $edit_url . '"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>';
                    break;
                }
            }

            foreach ($roles as $role) {
                if ($user->hasPermissionTo('Delete_' . $role)) {
                    $action .= '<a class="dropdown-item" title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
                    break;
                }
            }

            foreach ($roles as $role) {
                if ($user->hasPermissionTo('Read_' . $role) && $view_url != '') {
                    $action .= '<a class="dropdown-item" title="View" href="' . $view_url . '"><i class="fa-solid fa-eye m-r-5"></i> View</a>';
                    break;
                }
            }

        } else {
            if ($edit_url != '' && $user->hasPermissionTo('Update_' . $permission)) {
                $action .= '<a class="dropdown-item" title="Edit" href="' . $edit_url . '"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>';
            }

            if ($user->hasPermissionTo('Delete_' . $permission)) {
                $action .= '<a class="dropdown-item" title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
            }

            if ($user->hasPermissionTo('Read_' . $permission) && $view_url != '') {
                $action .= '<a class="dropdown-item" title="View" href="' . $view_url . '"><i class="fa-solid fa-eye m-r-5"></i> View</a>';
            }
        }

        return $action;
    }
}


if (!function_exists('action_btns2')) {
    function action_btns2($action, $user, $permission, $edit_url, $route_id,  $view_url,$item)
    {
        if ($edit_url != '' && $user->hasPermissionTo('Update_' . $permission)) {
            $action .= '<a class="dropdown-item" title="Edit" href="' . $edit_url . '"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>';
        }

        if ($user->hasPermissionTo('Delete_' . $permission)) {
            $action .= '<a class="dropdown-item" title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
        }

        if ($user->hasPermissionTo('Update_' . $permission) && $item->status == 0) {
            $action .= '<a class="dropdown-item" title="Approve"  href="javascript:;" onclick="orderStatus(' . $item->id . ',1)" data-id="' . $item->id . '"><i class="fa-solid fas fa-check m-r-5"></i> Approve</a>';
        }

        if ($user->hasPermissionTo('Update_' . $permission) && $item->status == 1) {
            $action .= '<a class="dropdown-item" title="Complete"  href="javascript:;" onclick="orderStatus(' . $item->id . ',2)" data-id="' . $item->id . '"><i class="fa-solid fas fa-check m-r-5"></i> Complete</a>';
        }

        if ($user->hasPermissionTo('Update_' . $permission) && $item->status == 0) {
            $action .= '<a class="dropdown-item" title="Cancel"  href="javascript:;" onclick="orderStatus(' . $item->id . ',3)" data-id="' . $item->id . '"><i class="fa-solid fas fa-times m-r-5"></i>Cancel</a>';
        }

        if ($user->hasPermissionTo('Update_' . $permission) && $view_url != '') {
            $action .= '<a class="dropdown-item" title="View" href="' . $view_url . '"><i class="fa-solid fa-eye m-r-5"></i> View</a>';
        }

        return $action;
    }
}


if (!function_exists('action_btns_pur')) {
    function action_btns_pur($action, $user, $permission, $view_url, $route_id,  $pur_url)
    {
        if ($view_url != '' && $user->hasPermissionTo('Update_' . $permission)) {
            $action .= '<a class="dropdown-item"  title="View" href="' . $view_url . '"><i class="fa fa-eye m-r-5"></i>View</a>';
        }

        if ($user->hasPermissionTo('Delete_' . $permission)) {
            $action .= '<a class="dropdown-item"  title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
        }

        if ($user->hasPermissionTo('Create_Payement')) {
            $action .= '<a href="' . $pur_url . '"  class="dropdown-item"  title="Payment"><i class="fa-solid fa-dollar-sign m-r-10"></i>Payment</a>';
        }

        return $action;
    }
}

if (!function_exists('user_permission_check')) {
    function user_permission_check($user, $permission)
    {
        $status = false;
        if($permission == 'Read_User'){
            if ($user->hasAnyPermission(['Read_Reception', 'Read_Manager','Read_Technician','Read_Customer'])){
                    $status = true;
                }
        }elseif($permission == 'Create_User'){
            if ($user->hasAnyPermission(['Create_Reception', 'Create_Manager','Create_Technician','Create_Customer'])){
                $status = true;
            }
        }elseif($permission == 'Update_User'){
            if ($user->hasAnyPermission(['Update_Reception', 'Update_Manager','Update_Technician','Update_Customer'])){
                $status = true;
            }
        }elseif($permission == 'Delete_User'){
            if ($user->hasAnyPermission(['Delete_Reception', 'Delete_Manager','Delete_Technician','Delete_Customer'])){
                $status = true;
            }
        }else{

            if ($user->hasPermissionTo($permission)) {
                $status = true;
            }
        }


        return $status;
    }
}


if (!function_exists('action_btns_po')) {
    function action_btns_po($action, $user, $edit_url, $can_delete, $read_url, $permission, $order)
    {
        if ($edit_url != '' && $user->hasPermissionTo('Update_' . $permission)) {
            $action .= '<a class="dropdown-item"  title="Edit" href="' . $edit_url . '"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>';
        }

        if ($can_delete && $user->hasPermissionTo('Delete_' . $permission)) {
            $action .= '<a class="dropdown-item"  title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $order->id . ')" data-id="' . $order->id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
        }

        if ($user->hasPermissionTo('PO_Approval') && $order->status == 0) {
            $action .= '<a class="dropdown-item" title="Approve" href="javascript:;" onclick="change_status(' . $order->id . ',1)" data-id="' . $order->id . '"><i class="fa-solid fas fa-check m-r-5"></i> Approve</a>';
        }

        if ($user->hasPermissionTo('PO_Hold') && $order->status == 1) {
            $action .= '<a class="dropdown-item" title="On Hold" href="javascript:;" onclick="change_status(' . $order->id . ',2)" data-id="' . $order->id . '"><i class="fa-solid fas fa-pause m-r-5"></i> On Hold</a>';
        }

        if ($user->hasPermissionTo('PO_Cancel') && in_array($order->status, [0, 1, 2])) {
            $action .= '<a class="dropdown-item" title="Cancel" href="javascript:;" onclick="change_status(' . $order->id . ',3)" data-id="' . $order->id . '"><i class="fa-solid fas fa-times m-r-5"></i> Cancel</a>';
        }

        if ($user->hasPermissionTo('PO_Fullfillment') && in_array($order->status, [1, 2])) {
            $action .= '<a class="dropdown-item" title="Fullfillment" href="javascript:;" onclick="change_status(' . $order->id . ',4)" data-id="' . $order->id . '"><i class="fa-solid fas fa-check m-r-5"></i> Fullfillment</a>';
        }

        if ($user->hasPermissionTo('PO_Received') && $order->status == 4) {
            $route = route('business.purchaseorder.order.receive', $order->ref_no);
            $action .= '<a class="dropdown-item" title="Received" href="' . $route . '" data-id="' . $order->id . '"><i class="fa-solid fas fa-check m-r-5"></i>Received</a>';
        }

        if ($user->hasPermissionTo('PO_Closed') && $order->status == 5) {
            $action .= '<a class="dropdown-item" title="Close" href="javascript:;" onclick="change_status(' . $order->id . ',6)" data-id="' . $order->id . '"><i class="fa-solid fas fa-check m-r-5"></i> Close</a>';
        }

        if ($user->hasPermissionTo('Create_PurchaseOrder') && $order->status == 6) {
            $action .= '<a class="dropdown-item" title="Reorder" href="javascript:;" onclick="re_order(' . $order->id . ')" data-id="' . $order->id . '"><i class="fa-solid fas fa-redo-alt m-r-5"></i> Reorder</a>';
        }

        $permissons = ['PO_Approval', 'PO_Hold', 'PO_Cancel', 'PO_Fullfillment', 'PO_Received', 'PO_Closed'];

        if ($user->hasAnyPermission($permissons)) {
            $action .= '<a class="dropdown-item" title="View Order" href="' . $read_url . '"><i class="fa-solid fa-list m-r-5"></i> View Order</a>';
        }

        return $action;
    }
}
