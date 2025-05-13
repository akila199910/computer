<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Completed</title>
</head>
<body>
    Hi {{ $order->customer_info->name }},<br>
        Your order has been Completed.<br>

    <table class="table table-bordered-none">
        <tr>
            <td>Job No</td>
            <td>{{ $order->job_no }}</td>
        </tr>
        <tr>
            <td>Order Date</td>
            <td>{{ $order->order_date }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>{{ $order->total_cost }}</td>
        </tr>
    </table>

    <div>
        {{ $order->description }}
    </div>
    <p>Your order has been completed. Please pickup your order.</p>

</body>
</html>
