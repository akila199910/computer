@extends('layouts.business')

@section('title')
    Manage Products
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.product') }}">Manage Products </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Product</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.product') }}" class="btn btn-primary btn-lg me-2" style='width:100px'>Back</a>
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
                                    <h4>Update Product</h4>
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Product Name <span class="login-danger">*</span></label>
                                    <input type="text" name="product_name" class="form-control" id="product_name"
                                        maxlength="190" value="{{ Str::limit($product->name, 30) }}" title="{{ $product->name }}">
                                    <small class="text-danger font-weight-bold err_product_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="">Select Brand<span class="text-danger"> *</span></label>
                                    <select class="form-control select2" name="brand_name" id="brand_name">
                                        <option value="" disabled selected>-- Select Brand --</option>
                                        @foreach ($brands as $brand)

                                            <option value="{{ $brand->id }}" title="{{ $brand->name }}"
                                                {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                                {{ Str::limit($brand->name,30) }}
                                            </option>

                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_brand_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="">Select Category<span class="text-danger"> *</span></label>
                                    <select class="form-control select2" name="category_name" id="category_name">
                                        <option value="" disabled selected>-- Select Category --</option>
                                        @foreach ($categories as $item)

                                            <option value="{{ $item->id }}" title="{{ $item->name }}"
                                                {{ $item->id == $product->category_id ? 'selected' : '' }}>
                                                {{ Str::limit($item->name,30) }}
                                            </option>

                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_category_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Price <span class="login-danger"> *</span></label>
                                    <input type="text" name="price" class="form-control number_only_val" id="price"
                                        maxlength="190" value="{{ $product->price }}">
                                    <small class="text-danger font-weight-bold err_price"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="">Upload Image </label>
                                    <input type="file" class="form-control w-50" name="image" id="image" >
                                    <small class="text-danger font-weight-bold err_image"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="input-block local-forms">
                                    <label for="description">Description </label>
                                    <textarea name="description" id="description" class="form-control description" rows="4">
                                        {{ $product->description }}
                                    </textarea>
                                    <small class="text-danger font-weight-bold err_description"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block select-gender">
                                    <label class="gen-label">Status Inactive/Active</label>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="status" name="status"
                                            {{ $product->status == 1 ? 'checked' : '' }} class="check">
                                        <label for="status" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>
                                @if (Auth::user()->hasPermissionTo('Update_Product'))
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
                    url: "{{ route('business.product.update') }}",
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
