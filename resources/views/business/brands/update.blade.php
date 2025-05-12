@extends('layouts.business')

@section('title')
    Manage Brands
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.brands') }}">Manage Brands </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Brand</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.brands') }}" class="btn btn-primary btn-lg me-2" style='width:100px'>Back</a>
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
                                    <h4>Update Brand</h4>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $brand->id }}">

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Brand Name <span class="login-danger">*</span></label>
                                    <input type="text" name="brand_name" class="form-control brand_name " id="brand_name"
                                        maxlength="190" value="{{ Str::limit($brand->name, 30) }}" title="{{ $brand->name }}">
                                    <small class="text-danger font-weight-bold err_brand_name"></small>
                                </div>
                            </div>


                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block select-gender">
                                    <label class="gen-label">Status Inactive/Active</label>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="status" name="status"
                                            {{ $brand->status == 1 ? 'checked' : '' }} class="check">
                                        <label for="status" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>

                            @if ($brand->image != 'user/user.png')

                                <div class="col-12 col-md-12 col-xl-12">
                                    <div class="input-block local-forms">
                                        <label for="description">Current Image </label>
                                        <img src="{{ asset($brand->image) }}" alt="image" class="w-50">
                                    </div>
                                </div>

                            @endif
                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="input-block local-forms">
                                    <label for="description">Update Image </label>
                                    <input type="file" class="form-control w-50" name="image" >
                                    <small class="text-danger font-weight-bold err_image"></small>
                                </div>
                            </div>

                            @if (Auth::user()->hasPermissionTo('Update_Brand'))
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

            $('#submitForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData($('#submitForm')[0]);

                $.ajax({
                    type: "POST",
                    beforeSend: function() {
                        $('#loader').show()
                    },
                    url: "{{ route('business.brands.update') }}",
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
                        }else if(response.status == "error") {
                            errorPopup(response.message, "")

                        }else {
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
                $('#brand_name').removeClass('is-invalid');
                $('.err_brand_name').text('');

                $('#image').removeClass('is-invalid');
                $('.err_image').text('');
            }

        });
    </script>
@endsection
