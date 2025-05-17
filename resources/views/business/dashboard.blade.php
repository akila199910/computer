@extends('layouts.business')

@section('title')
    Dashboard
@endsection

@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;">Dashboard </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="doctor-list-blk col-12">
        <div class="row pb-3">
            <div class="col-xl-6 col-md-6">
                <div class="doctor-table-blk">
                    <h3 class="text-uppercase">{{ $monthName }} Month Orders </h3>
                </div>
            </div>
        </div>

        <div class="row pb-3">
            @if (Auth::user()->hasPermissionTo('Read_Order'))
                <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current','')">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <i class="fa-solid fa-utensils fa-xl" style="color: #ffffff;"></i>
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4>{{ $total }}</h4>
                            <h5>Total</h5>
                        </div>
                    </div>
                </div>
            @endif


            @if (Auth::user()->hasPermissionTo('Read_Order'))
                <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',0)">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <i class="fa-solid fa-spinner fa-xl" style="color: #ffffff;"></i>
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4>{{ $pending }}</h4>
                            <h5>Pending</h5>
                        </div>
                    </div>
                </div>
            @endif


            @if (Auth::user()->hasPermissionTo('Read_Order'))
                <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',2)">
                    <div class="doctor-widget">
                        <div class="doctor-box-icon flex-shrink-0">
                            <i class="fa-solid fa-check fa-xl" style="color: #ffffff;"></i>
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                              <h4>{{ $approved }}</h4>
                              <h5>Approved</h5>
                        </div>
                    </div>
                </div>
            @endif
    </div>

    <div class="row">
        @if (Auth::user()->hasPermissionTo('Read_Order'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',4)">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-box-icon flex-shrink-0">
                        <i class="fa-solid fa-list fa-xl" style="color: #ffffff;"></i>
                    </div>
                    <div class="doctor-content dash-count flex-grow-1">
                        <h4>{{ $completed }}</h4>
                        <h5>Completed</h5>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->hasPermissionTo('Read_Order'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',3)">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-box-icon flex-shrink-0">
                        <i class="fa-solid fa-xmark fa-xl" style="color: #ffffff;"></i>
                    </div>
                    <div class="doctor-content dash-count flex-grow-1">
                        <h4>{{ $cancelled }}</h4>
                        <h5>Cancelled</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row _current_reservation_div">

    </div>
</div>

@endsection
