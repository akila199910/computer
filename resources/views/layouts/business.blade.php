<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('layout_style/img/asset_logo.png') }}">
    <title>
        @yield('title') | {{ env('APP_NAME') }}
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/feather.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/style.css') }}"> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/style.css?v=') . time() }}">
    <link rel="stylesheet" href="{{ asset('layout_style/jquery_confirm/style.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/my-style.css?v=') . time() }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/bootstrap-datetimepicker.min.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
    <script src="{{ asset('layout_style/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('layout_style/js/validations.js') }}"></script>
    <script src="{{ asset('layout_style/js/fileupload.js') }}"></script>

    <script type="text/javascript">
        window.history.forward();

        function noBack() {
            window.history.forward();
            window.menubar.visible = false;
        }
    </script>


    @yield('style')

</head>

<body onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
    <div class="main-wrapper">
        <div class="header admin-dashboard">
            <div class="header-left">
                <a href="javascript:;" class="logo">
                    <img src="{{ asset('layout_style/img/asset_logo.png') }}" width="35" height="35" alt>
                    <span>{{ env('APP_NAME') }}</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><img src="{{ asset('layout_style/img/icons/menu-bar.svg') }}"
                    style="width: 40px;" alt></a>
            <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img
                    src="{{ asset('layout_style/img/icons/menu-bar.svg') }}" style="width:24px" alt></a>

            @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin')||Auth::user()->hasRole('business_user'))
                <div class="top-nav-search mob-view">
                    <form>
                        <select class="form-control js-example-basic-single select2" id="change_dashboard"
                            placeholder="Search here">
                            <option value="">-- Select the Business --</option>
                            @foreach ($businesses as $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == session()->get('_business_id') ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif

            <ul class="nav user-menu float-end">
                <li class="nav-item dropdown has-arrow user-profile-list">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                        <div class="user-names">
                            <h5>{{ ucfirst(Auth::user()->first_name) . ' ' . ucfirst(Auth::user()->last_name) }} </h5>
                            {{-- <span>Admin</span> --}}
                        </div>
                        <span class="user-img">
                            <img src="{{ (Auth::user()->UserProfile->profile == '') || (Auth::user()->UserProfile->profile == 'user/user.png') ? asset('layout_style/img/user.jpg') : config('aws_url.url') . Auth::user()->UserProfile->profile }}" style="border-radius:50%; width: 40px; height: 40px; object-fit: cover;" alt="">
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('business.profile') }}">My Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </li>

            </ul>
            <div class="dropdown mobile-user-menu float-end">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="">My Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        @php
            $segment = Request::segment(1);
            $segment2 = Request::segment(2);
        @endphp

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">{{ session()->get('_business_name') }}</li>

                        <li>
                            <a href="{{ route('business.dashboard') }}"
                               class="{{ request()->route()->getName() == 'business.dashboard' ? 'active' : '' }}">
                                <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/dashboard_admin.png') }}" style="width: 24px" alt>
                                </span>
                                <span>Dashboard</span>
                            </a>
                        </li>


                        @if (Auth::user()->hasPermissionTo('Read_Department'))
                        <li>
                            <a href="{{ route('business.department') }}"
                               class="{{ request()->routeIs('business.department*') ? 'active' : '' }}">
                                <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/department.png') }}" style="width: 24px" alt>
                                </span>
                                <span>Departments</span>
                            </a>
                        </li>
                    @endif



                    @if (Auth::user()->hasPermissionTo('Read_User'))
                    <li>
                        <a href="{{ route('business.users') }}"
                           class="{{ request()->routeIs('business.user*') ? 'active' : '' }}">
                            <span class="menu-side">
                                <img src="{{ asset('layout_style/img/icons/user.png') }}" style="width: 24px" alt>
                            </span>
                            <span>Users</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasPermissionTo('Read_Employee'))
                <li>
                    <a href="{{ route('business.employee') }}"
                       class="{{ request()->routeIs('business.employee*') ? 'active' : '' }}">
                        <span class="menu-side">
                            <img src="{{ asset('layout_style/img/icons/user-group.png') }}" style="width: 24px" alt>
                        </span>
                        <span>Employees</span>
                    </a>
                </li>
            @endif

                        @if (Auth::user()->hasPermissionTo('Read_Asset_Handling'))
                        @php
                            $asset_handling_route_name = [
                                'business.asset_handle.index',
                                'business.asset_handle.create.form',
                                'business.asset_handle.update.form',
                                'business.asset_handle.view_details',
                            ];
                        @endphp

                        <li>
                            <a href="{{ route('business.asset_handle') }}"
                            class="{{ request()->routeIs('business.asset_handle*') ? 'active' : '' }}">
                            <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/asset_handling2.png') }}" style="width: 24px"
                                        alt>
                                </span>
                                <span>Assets Handling</span>
                            </a>
                        </li>

                    @endif
                    {{-- <li class="submenu">
                        <li class="submenu">
                            @php


                            $inventory_categories_route_name = [
                                'business.inventory_category',
                                'business.inventory_category.create.form',
                                'business.inventory_category.update.form',
                                'business.inventory_category.view_details',
                            ];

                            $inventory_sub_categories_route_name = [
                                'business.inventory_sub_category',
                                'business.inventory_sub_category.create.form',
                                'business.inventory_sub_category.update.form',
                                'business.inventory_sub_category.view_details',
                            ];

                            $inventories_route_name = [
                                'business.inventory',
                                'business.inventory.create.form',
                                'business.inventory.update.form',
                                'business.inventory.view_details',
                            ];

                        @endphp
                        @if (Auth::user()->hasPermissionTo('Read_Inventory_Category')||Auth::user()->hasPermissionTo('Read_Inventory_Sub_Category')||Auth::user()->hasPermissionTo('Read_Inventory'))
                        <a href="javascript:;"><span class="menu-side">
                                <img src="{{ asset('layout_style/img/icons/inventory.png') }}"
                                    style="width: 24px" alt></span>
                            <span> Inventories </span> <span class="menu-arrow"></span></a>

                        <ul style="display: none;">
                            @if (Auth::user()->hasPermissionTo('Read_Inventory_Category'))
                            <li>
                                <a href="{{ route('business.inventory_category') }}"
                                    class="{{ in_array(request()->route()->getName(), $inventory_categories_route_name) ? 'active' : '' }}">
                                    <span>Inventory Categories</span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->hasPermissionTo('Read_Inventory_Sub_Category'))
                            <li>
                                <a href="{{ route('business.inventory_sub_category') }}"
                                    class="{{ in_array(request()->route()->getName(), $inventory_sub_categories_route_name) ? 'active' : '' }}">
                                    <span>Inventory Sub Categories</span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->hasPermissionTo('Read_Inventory'))
                            <li>
                                <a href="{{ route('business.inventory') }}"
                                    class="{{ in_array(request()->route()->getName(), $inventories_route_name) ? 'active' : '' }}">
                                    <span>Inventories</span>
                                </a>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif --}}

                        <li class="submenu">

                            <li class="submenu">
                                @php
                                    $categories_route_name = [
                                        'business.category',
                                        'business.category.create.form',
                                        'business.category.update.form',
                                        'business.category.view_details',
                                    ];

                                    $sub_categories_route_name = [
                                        'business.sub_category',
                                        'business.sub_category.create.form',
                                        'business.sub_category.update.form',
                                        'business.sub_category.view_details',
                                    ];

                                    $assets_route_name = [
                                        'business.asset',
                                        'business.asset.create.form',
                                        'business.asset.update.form',
                                        'business.asset.view_details',
                                    ];

                                @endphp

                                @if (Auth::user()->hasPermissionTo('Read_Category')||Auth::user()->hasPermissionTo('Read_Sub_Category')||Auth::user()->hasPermissionTo('Read_Asset'))
                                <a href="javascript:;"><span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/inventory.png') }}"
                                            style="width: 24px" alt></span>
                                    <span> Assets </span> <span class="menu-arrow"></span></a>

                                <ul style="display: none;">
                                    @if (Auth::user()->hasPermissionTo('Read_Category'))
                                    <li>
                                        <a href="{{ route('business.category') }}"
                                            class="{{ in_array(request()->route()->getName(), $categories_route_name) ? 'active' : '' }}">
                                            <span>Categories</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo('Read_Sub_Category'))
                                    <li>
                                        <a href="{{ route('business.sub_category') }}"
                                            class="{{ in_array(request()->route()->getName(), $sub_categories_route_name) ? 'active' : '' }}">
                                            <span>Sub Categories</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo('Read_Asset'))
                                    <li>
                                        <a href="{{ route('business.asset') }}"
                                            class="{{ in_array(request()->route()->getName(), $assets_route_name) ? 'active' : '' }}">
                                            <span>Assets</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                                @endif
                            </li>
                        <li class="submenu">
                            @php
                            $handling_report_route_name = [
                                'business.report',
                                'business.report.export',

                            ];
                            $asset_report_route_name=[
                                'business.report.assets',
                                'business.report.export.assets',
                        ];
                            @endphp
                             @if (Auth::user()->hasPermissionTo('Read_Report'))
                            <a href="javascript:;"><span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/stock.png') }}"
                                        style="width: 24px" alt></span>
                                <span> Reports </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                {{-- @if (Auth::user()->hasPermissionTo('Read_Report')) --}}
                                <li>
                                    <a href="{{ route('business.report') }}"
                                        class="{{ in_array(request()->route()->getName(), $handling_report_route_name) ? 'active' : '' }}">
                                        <span>Asset Handling</span>
                                    </a>
                                </li>
                                {{-- @endif --}}
                                {{-- @if (Auth::user()->hasPermissionTo('Read_Report')) --}}
                                <li>
                                    <a href="{{ route('business.report.assets') }}"
                                        class="{{ in_array(request()->route()->getName(), $asset_report_route_name) ? 'active' : '' }}">
                                        <span>Asset Details</span>
                                    </a>
                                </li>
                                {{-- @endif --}}
                            </ul>
                            @endif
                        </li>
                        @php
                            $profile_route_name = [
                                'business.profile',
                                'business.profile_update',
                                'business.password_update',
                            ];
                        @endphp

                        <li>
                            <a href="{{ route('business.profile') }}"
                            class="{{ request()->routeIs('business.profile*') ? 'active' : '' }}">
                            <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/profile.png') }}" style="width: 24px"
                                        alt>
                                </span>
                                <span>Profile</span>
                            </a>
                        </li>



                    </ul>

                    <div class="logout-btn">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                                class="menu-side"><img src="{{ asset('layout_style/img/icons/logout.ico') }}"
                                    alt></span>
                            <span>Logout</span></a>
                    </div>

                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="content">

                @yield('content')

            </div>
        </div>

        <!--loader-->
        <div class="ajax-loader" id="loader" style="display: none">
            <div class="max-loader">
                <div class="loader-inner">
                    <div class="spinner-border text-white" role="status"></div>
                    <p>Please Wait........</p>
                </div>
            </div>
        </div>
        <!--end loader-->
    </div>
    <div class="sidebar-overlay" data-reff></div>



    <script src="{{ asset('layout_style/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('layout_style/js/app.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/select2/js/custom-select.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/script.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/popup.js') }}"></script>

    <script src="{{ asset('layout_style/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.counterup.min.js') }}"></script>

    <script src="{{ asset('layout_style/cdn_scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/chart-data.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        //Open dropdown when clicking on element
        $(document).on("click", "a[data-dropdown='notificationMenu']", function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);

            $("body").prepend(
                '<div id="dropdownOverlay" style="background: transparent; height:100%;width:100%;position:fixed;"></div>'
            );

            var container = $(e.currentTarget).parent();
            var dropdown = container.find(".dropdown");
            var containerWidth = container.width();
            var containerHeight = container.height();

            var anchorOffset = $(e.currentTarget).offset();

            dropdown.css({
                right: containerWidth / 2 + "px"
            });

            container.toggleClass("expanded");
        });

        //Close dropdowns on document click

        $(document).on("click", "#dropdownOverlay", function(e) {
            var el = $(e.currentTarget)[0].activeElement;

            if (typeof $(el).attr("data-dropdown") === "undefined") {
                $("#dropdownOverlay").remove();
                $(".dropdown-container.expanded").removeClass("expanded");
            }
        });

        //Dropdown collapsile tabs
        $(".notification-tab").click(function(e) {
            if ($(e.currentTarget).parent().hasClass("expanded")) {
                $(".notification-group").removeClass("expanded");
            } else {
                $(".notification-group").removeClass("expanded");
                $(e.currentTarget).parent().toggleClass("expanded");
            }
        });

        $(document).ready(function() {
            $('.select2').select2()

            $('#change_dashboard').change(function(e) {
                e.preventDefault();
                var id = $(this).val()

                if (id != '') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var data = {
                        'id': id
                    }
                    $('#loader').show()
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.business.move_dashboard') }}",
                        data: data,
                        dataType: "JSON",
                        success: function(response) {
                            $('#loader').hide()
                            location.href = "{{ route('business.dashboard') }}";

                        },
                        statusCode: {
                            401: function() {
                                window.location.href =
                                    '{{ route('login') }}'; //or what ever is your login URI
                            },
                            419: function() {
                                window.location.href =
                                    '{{ route('login') }}'; //or what ever is your login URI
                            },
                        },
                        error: function(data) {
                            alert('something went to wrong')
                        }
                    });
                }
            });
        });
    </script>



    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>


    @yield('scripts')
</body>

</html>
