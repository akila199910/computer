@extends('layouts.business')

@section('title')
Manage Orders
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.order') }}">Manage Orders</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Order Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.order') }}" class="btn btn-primary btn-lg me-2" style='width:100px'>Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doctor-table-blk mb-4 pt-2">
                                            <h3 class="text-uppercase">Order Details</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Order Job No</h2>
                                                    <h3>{{ ucwords($order->job_no) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Customer Name</h2>
                                                    <h3>{{ ucwords($order->customer_info->name ?? "N/A") }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Total Cost</h2>
                                                    <h3>{{ ucwords($order->total_cost ?? "N/A") }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Order Date</h2>
                                                    <h3>{{ ucwords($order->order_date ?? "N/A") }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Technician Name</h2>
                                                    <h3>{{ ucwords($order->technician_info->name ?? "N/A") }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-12 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Status </h2>
                                                    <h3>
                                                        @if ($order->status == 0)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif ($order->status == 1)
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif ($order->status == 2)
                                                            <span class="badge badge-danger">Completed</span>
                                                        @else
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-md-12 col-xl-12 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Description</h2>

                                                    <textarea name="description" id="description" class="form-control description" rows="4" readonly>{{ $order->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

