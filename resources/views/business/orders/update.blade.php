@extends('layouts.business')

@section('title')
    Manage Orders
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.order') }}">Manage Orders </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Order</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.order') }}" class="btn btn-primary btn-lg me-2" style='width:100px'>Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <form id="submitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-heading">
                                    <h4>Update Order</h4>
                                </div>
                            </div>

                            <input type="hidden" value="{{ $order->id }}" name="id">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="">Select Customer<span class="text-danger"> *</span></label>
                                    <select class="form-control select2" name="customer_name" id="customer_name">
                                        <option value="" disabled selected>-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" title="{{ $customer->name }}"
                                                {{ $order->customer_id == $customer->id ? 'selected' : '' }}
                                                >
                                                {{ Str::limit($customer->name . " ( " . $customer->contact . " )", 45) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_customer_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="">Select Technician<span class="text-danger"> </span></label>
                                    <select class="form-control select2" name="technician_name" id="technician_name">
                                        <option value="" disabled selected>-- Select Technician --</option>
                                        @foreach ($technicians as $technician)

                                            <option value="{{ $technician->id }}" title="{{ $technician->name }}"
                                            {{ $order->technician_id == $technician->id ? 'selected' : '' }}
                                            >{{ Str::limit($technician->name . " ( " . $technician->contact . " )", 45) }}</option>

                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_technician_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Price <span class="login-danger"> </span></label>
                                    <input type="text" name="price" class="form-control number_only_val" id="price"
                                        maxlength="190">
                                    <small class="text-danger font-weight-bold err_price"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="input-block local-forms">
                                    <label for="description">Description <span class="login-danger"> *</span></label>
                                    <textarea name="description" id="description" class="form-control" rows="4">{{ $order->description }}</textarea>
                                    <small class="text-danger font-weight-bold err_description"></small>
                                </div>
                            </div>

                                @if (Auth::user()->hasPermissionTo('Update_Order'))
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit"
                                                class="btn btn-primary text-uppercase submit-form me-2">Update</button>
                                        </div>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#customer_name').select2({
                placeholder: "-- Select Customer --",
                allowClear: true
            });

            $('#technician_name').select2({
                placeholder: "-- Select Technician --",
                allowClear: true
            });

            $('#submitForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData($('#submitForm')[0]);

                $.ajax({
                    type: "POST",
                    beforeSend: function() {
                        $('#loader').show()
                    },
                    url: "{{ route('business.order.update') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $('#loader').hide()

                        clearError();

                        if (response.status == false) {
                            $.each(response.message, function(key, item) {
                                if (key) {
                                    $('.err_' + key).text(item)
                                    $('#' + key).addClass('is-invalid');
                                }
                            });
                        } else {
                            successPopup(response.message, response.route)
                        }
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
                        someThingWrong();
                    }
                });
            });

            function clearError() {
                $('#category_name').removeClass('is-invalid');
                $('.err_category_name').text('');

                $('#price').removeClass('is-invalid');
                $('.err_price').text('');

                $('#description').removeClass('is-invalid');
                $('.err_description').text('');

                $('#brand_name').removeClass('is-invalid');
                $('.err_brand_name').text('');

                $('#image').removeClass('is-invalid');
                $('.err_image').text('');

            }

        });
    </script>
@endsection
