@extends('layouts.business')

@section('title')
Manage Products
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.product') }}">Manage Products</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Product Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.product') }}" class="btn btn-primary btn-lg me-2" style='width:100px'>Back</a>
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
                                            <h3 class="text-uppercase">Product Details</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Product Name</h2>
                                                    <h3>{{ ucwords($product->name) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Brand Name</h2>
                                                    <h3>{{ ucwords($product->brand->name ?? "N/A") }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Category</h2>
                                                    <h3>{{ ucwords($product->category->name ?? "N/A") }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-12 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Status </h2>
                                                    <h3>
                                                        @if ($product->status == 1)
                                                            <span class="custom-badge status-green ">Active</span>
                                                        @else
                                                            <span class="custom-badge status-red ">Inactive</span>
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>

                                            @if ($product->image !== 'user/user.png')
                                                <div class="col-xl-6 col-md-12 mb-3">
                                                    <div class="detail-personal">
                                                        <h2>Image</h2>
                                                        <img src="{{ asset($product->image) }}" alt="image" class="w-50 mb-3 rounded" >
                                                    </div>
                                                </div>
                                            @endif
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

