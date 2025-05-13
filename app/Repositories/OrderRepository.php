<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class OrderRepository

{

    public function getOrder()
    {
        return Order::with(['customer_info','technician_info','created_by_info','approved_by_info']);
    }
    public function create_order($request)
    {

        $job_no = 'JOB-' . Str::upper(Str::random(6)).$request->customer_name;

        $order = new Order();
        $order->job_no = $job_no;
        $order->customer_id = $request->customer_name;
        $order->status = 0;
        $order->description = $request->description ?? "";
        $order->created_by = Auth::user()->id;
        $order->order_date = now();
        $order->technician_id = $request->technician_name;
        $order->total_cost = $request->price;
        $order->save();

        if (!empty($request->price)) {
        $data = [
            "title" => 'Order Created | ' . env('APP_NAME'),
            "email" => $order->customer_info->email,
            "name" => $order->customer_info->name,
            "order" => $order,
            "view" => 'mail.order.created'
        ];

        mailNotification($data);
    }

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $order->id);
        $order->ref_no = $ref_no;
        $order->update();

        return [
            'id' => $order->id,
            'ref_no' => $order->ref_no
        ];
    }

    public function update_order($request)
    {
        $order = Order::find($request->id);

        $oldPrice = $order->total_cost;
        $newPrice = $request->price;
        $order->customer_id = $request->customer_name;
        $order->description = $request->description ?? "";
        $order->technician_id = $request->technician_name;
        $order->total_cost = $newPrice;
        $order->update();

        if (!is_null($newPrice) && $newPrice != $oldPrice) {

        $data = [
            "title" => 'Order Updated | ' . env('APP_NAME'),
            "email" => $order->customer_info->email,
            "name" => $order->customer_info->name,
            "order" => $order,
            "view" => 'mail.order.created',
        ];

        mailNotification($data);
    }
        return [
            'id' => $order->id,
        ];
    }

    public function change_order($request){

        $order = Order::find($request->id);
        $order->status = $request->status;

        if($request->status == 1){
            $order->approved_by = Auth::user()->id;

            $data = [
                "title" => 'Order Approved | ' . env('APP_NAME'),
                "email" => $order->customer_info->email,
                "name" => $order->customer_info->name,
                "order" => $order,
                "view" => 'mail.order.approved',
            ];

            mailNotification($data);
        }
        $order->update();

        if($request->status == 2){
            $data = [
                "title" => 'Order Completed | ' . env('APP_NAME'),
                "email" => $order->customer_info->email,
                "name" => $order->customer_info->name,
                "order" => $order,
                "view" => 'mail.order.completed',
            ];

            mailNotification($data);
        }

        return [
            'id' => $order->id,
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
