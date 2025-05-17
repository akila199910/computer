<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $monthName = Carbon::now()->format('F');

        $baseQuery = Order::whereYear('order_date', Carbon::now()->year)
                        ->whereMonth('order_date', Carbon::now()->month);

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 0)->count();
        $approved = (clone $baseQuery)->where('status', 1)->count();
        $completed = (clone $baseQuery)->where('status', 2)->count();
        $cancelled = (clone $baseQuery)->where('status', 3)->count();

        return view('business.dashboard', [
            'monthName' => $monthName,
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'completed' => $completed,
            'cancelled' => $cancelled
        ]);
    }

}
