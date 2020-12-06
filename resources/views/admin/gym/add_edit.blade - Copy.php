@extends('admin.layouts.main')

@section('styles')
<link href="{{ asset('public/assets/css/demo1/pages/wizard/wizard-2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">
    <style>
        label.cabinet2{
            display: block;
            cursor: pointer;
        }

        label.cabinet2 input.file2{
            position: relative;
            height: 100%;
            width: auto;
            opacity: 0;
            -moz-opacity: 0;
            filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
            margin-top:-30px;
        }

        #upload-demo2 {
            padding-bottom:25px;
        }

        figure figcaption {
            position: absolute;
            bottom: 0;
            color: #fff;
            width: 100%;
            padding-left: 9px;
            padding-bottom: 5px;
            text-shadow: 0 0 10px #000;
        }
    </style>
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-grid  kt-wizard-v2 kt-wizard-v2--white" id="kt_wizard_v2" data-ktwizard-state="step-first">
                    <div class="kt-grid__item kt-wizard-v2__aside">

                        <!--begin: Form Wizard Nav -->
                        <div class="kt-wizard-v2__nav">
                            <div class="kt-wizard-v2__nav-items">
                                <a class="kt-wizard-v2__nav-item" href="javascript:;" data-ktwizard-type="step" data-ktwizard-state="current">
                                    <div class="kt-wizard-v2__nav-body">
                                        <div class="kt-wizard-v2__nav-icon">
                                            <i class="flaticon-globe"></i>
                                        </div>
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Basic Details
                                            </div>
                                            <div class="kt-wizard-v2__nav-label-desc">
                                                Add Your Gym's Basic Details
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a class="kt-wizard-v2__nav-item" href="javascript:;" data-ktwizard-type="step">
                                    <div class="kt-wizard-v2__nav-body">
                                        <div class="kt-wizard-v2__nav-icon">
                                            <i class="flaticon-bus-stop"></i>
                                        </div>
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Setup Locations
                                            </div>
                                            <div class="kt-wizard-v2__nav-label-desc">
                                                Choose Your Location Map
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a class="kt-wizard-v2__nav-item" href="javascript:;" data-ktwizard-type="step">
                                    <div class="kt-wizard-v2__nav-body">
                                        <div class="kt-wizard-v2__nav-icon">
                                            <i class="flaticon-trophy"></i>
                                        </div>
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Schedule
                                            </div>
                                            <div class="kt-wizard-v2__nav-label-desc">
                                                Add your Gym's Schedule
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!--end: Form Wizard Nav -->
                    </div>
                    <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v2__wrapper">

                        <!--begin: Form Wizard Form-->
                        <form method="POST" class="kt-form" id="kt_form" enctype="multipart/form-data">
                            @csrf
                            {{ Form::hidden('listing_id', $listing->id, ['id' => 'listing_id']) }}
                            <!--begin: Form Wizard Step 1-->
                            <div class="kt-wizard-v2__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                                <div class="kt-heading kt-heading--md">Basic Details</div>
                                <div class="kt-form__section kt-form__section--first">
                                    <div class="kt-wizard-v2__form">
                                        <div class="form-group">
                                            <label>Gym Name</label>
                                            {{ Form::text('name', $listing->name, ['class' => 'form-control', 'placeholder' => 'Gym Name']) }}
                                            <span class="form-text text-muted">Please enter your gym's name.</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            {{ Form::number('phone', $listing->phone, ['class' => 'form-control', 'placeholder' => 'Phone']) }}
                                            <span class="form-text text-muted">Please enter your gym's phone number.</span>
                                        </div>
                                        @if(!$listing->id && $parent_id)
                                        {{ Form::hidden('owner_id', $parent->owner_id) }}
                                        @else
                                        <div class="form-group">
                                            <label>Select Owner</label>
                                            {{ Form::select('owner_id', $owners, $listing->owner_id, ['class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Owner', 'id' => 'owner_id', 'required']) }}
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label>Select Branch Manager</label>
                                            {{ Form::select('branch_manager_id', $branch_managers, $listing->branch_manager_id, ['class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Branch Manager', 'id' => 'branch_manager_id', 'required']) }}
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>First hour Price</label>
                                                    {{ Form::number('price', $listing->price, ['class' => 'form-control', 'placeholder' => 'Price']) }}
                                                    <span class="form-text text-muted">Please enter your gym's price.</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Additional Price</label>
                                                    {{ Form::number('additional_price', $listing->additional_price, ['class' => 'form-control', 'placeholder' => 'Additional Price']) }}
                                                    <span class="form-text text-muted">Please enter your gym's additional price.</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-3 col-sm-12">Banner *</label>
                                                    <div class="col-xs-12">
                                                        <label class="cabinet center-block">
                                                            <figure>
                                                                <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" width="200px"/>
                                                            </figure>
                                                            <input type="file" class="item-img file center-block" name="file_photo"/>
                                                            <input type="hidden" id="imagebase64" name="banner">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-3 col-sm-12">Logo *</label>
                                                    <div class="col-xs-12">
                                                        <label class="cabinet2 center-block">
                                                            <figure>
                                                                <img src="" class="gambar2 img-responsive img-thumbnail" id="item-img-output2" width="200px"/>
                                                            </figure>
                                                            <input type="file" class="item-img2 file2 center-block" name="file_photo"/>
                                                            <input type="hidden" id="imagebase642" name="logo">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Select Amenities</label>
                                            {{ Form::select('amenities[]', $amenities, $listing->amenities->pluck('id'), ['class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Amenities', 'id' => 'amenities', 'multiple', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($listing->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status.</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Details</label>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                {{ Form::textarea('description', $listing->description, ['class' => 'form-control', 'placeholder' => 'Details']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter gym details.</span>
                                        </div>
                                        @if(isset($listing->checkin_token))
                                        <div class="form-group row">
                                            <div class="col-xl-12">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <label>Scan In Token</label>
                                                        <img width="150px" src="{{ 'public/uploads/gym/'.$listing->checkin_token . '.png' }}" alt="your image" class="custom_input_img" />
                                                        <a href="{{ 'public/uploads/gym/'.$listing->checkin_token . '.png' }}" class="btn btn-brand" download>
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                        <a id="checkin_token" class="btn btn-danger" style="color: white">
                                                            <i class="fa fa-download"></i> Re-generate Token
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <label>Scan Out Token</label>
                                                        <img width="150px" src="{{ 'public/uploads/gym/'.$listing->checkout_token . '.png' }}" alt="your image" class="custom_input_img" />
                                                        <a href="{{ 'public/uploads/gym/'.$listing->checkout_token . '.png' }}" class="btn btn-brand" download>
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                        <a id="checkout_token" class="btn btn-danger" style="color: white">
                                                            <i class="fa fa-download"></i> Re-generate Token
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!--end: Form Wizard Step 1-->

                            <!--begin: Form Wizard Step 2-->
                            <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                                <div class="kt-heading kt-heading--md">Setup Your Gym's Location</div>
                                <div class="kt-form__section kt-form__section--first">
                                    <div class="kt-wizard-v2__form">
                                        <div class="form-group">
                                        <div class="form-group">
                                            <label>Select Country</label>
                                            {{ Form::select('country_id', $countries, $listing->country_id, ['class' => 'form-control', 'placeholder' => 'Select Country', 'id' => 'country_id', 'required', 'style' => 'width:100%']) }}
                                        </div>
                                            <label>Select City</label>
                                            {{ Form::select('city_id', (isset($listing->city)) ? [$listing->city_id => $listing->city->name] : [], $listing->city_id, ['class' => 'form-control', 'placeholder' => 'Select City', 'id' => 'city_id', 'required', 'style' => 'width:100%']) }}
                                        </div>
                                        <div class="form-group">
                                            <label>Area</label>
                                            {{ Form::text('town', $listing->town, ['class' => 'form-control', 'placeholder' => 'Area', 'required']) }}
                                            <span class="form-text text-muted">Please enter your gym's area.</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            {{ Form::text('location', $listing->location, ['class' => 'form-control', 'placeholder' => 'Location']) }}
                                            <span class="form-text text-muted">Please enter your gym's address.</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Latitude</label>
                                                    {{ Form::text('latitude', $listing->latitude, ['class' => 'form-control', 'placeholder' => 'Latitude']) }}
                                                    <span class="form-text text-muted">Please enter your gym's latitude value.</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Longitude</label>
                                                    {{ Form::text('longitude', $listing->longitude, ['class' => 'form-control', 'placeholder' => 'Longitude']) }}
                                                    <span class="form-text text-muted">Please enter your gym's longitude value.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--end: Form Wizard Step 2-->

                            <!--begin: Form Wizard Step 3-->
                            <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                                <div class="kt-heading kt-heading--md">Schedule</div>
                                <div class="kt-form__section kt-form__section--first">
                                    <div class="kt-wizard-v2__form">
                                        <table class="table table-responsive table-bordered" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Open Time</th>
                                                    <th>Close Time</th>
                                                    <th>24 Hour</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($days as $key => $day)
                                                <?php
                                                    $c_timings = $listing->timings->where('day', $day);
                                                    if(count($c_timings) > 0) {
                                                        $o_time = $c_timings[$key]->open_time;
                                                        $c_time = $c_timings[$key]->close_time;
                                                        $t_hour = $c_timings[$key]->is_24hour;
                                                        $t_type = $c_timings[$key]->type;
                                                    } else {
                                                        $o_time = '';
                                                        $c_time = '';
                                                        $t_hour = '';
                                                        $t_type = '';
                                                    }

//                                                    $c_time = $listing->timings->where('day', $day);
//                                                    if(count($c_time) > 0) {
//                                                        $c_time = $c_time[$key]->close_time;
//                                                    } else {
//                                                        $c_time = '';
//                                                    }
//
//                                                    $hour = $listing->timings->where('day', $day);
//                                                    if(count($c_time) > 0) {
//                                                        $c_time = $c_time[$key]->close_time;
//                                                    } else {
//                                                        $c_time = '';
//                                                    }
                                                ?>
                                                <tr>
                                                    <input type="hidden" name="day[]" value="{{ $day }}">
                                                    <td><label>{{ $day }}</label></td>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="input-group timepicker">
                                                                {{ Form::text('open_time['.$key.']', $o_time, ['class' => 'form-control', 'id' => 'kt_timepicker_2', 'placeholder' => 'Select Start Time']) }}
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-clock-o"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="input-group timepicker">
                                                                {{ Form::text('close_time['.$key.']', $c_time, ['class' => 'form-control', 'id' => 'kt_timepicker_2', 'placeholder' => 'Select Close Time']) }}
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-clock-o"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group form-group-last row">
                                                            <div class="kt-checkbox-inline">
                                                                <label class="kt-checkbox">
                                                                    <input type="checkbox" name='is_24hour[{{ $key }}]' value="1" {{ $t_hour ? 'checked' : '' }}> Open 24 Hour
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group form-group-last row">
                                                            <div class="kt-checkbox-inline">
                                                                {{ Form::select('type['.$key.']', ['mixed' => 'Mixed', 'men' => 'Men', 'women' => 'Women'], $t_type, ['class' => 'form-control', 'placeholder' => 'Select Type']) }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!--end: Form Wizard Step 3-->

                            <!--begin: Form Actions -->
                            <div class="kt-form__actions">
                                <div class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                    Previous
                                </div>
                                <div id="submit" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
                                    Submit
                                </div>
                                <div class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                    Next Step
                                </div>
                            </div>

                            <!--end: Form Actions -->
                        </form>

                        <!--end: Form Wizard Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog full_modal-dialog">
            <div class="modal-content full_modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Edit Photo</h4>
                </div>
                <div class="modal-body">
                    <div id="upload-demo" class="center-block"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropImagePop2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog full_modal-dialog">
            <div class="modal-content full_modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Edit Photo</h4>
                </div>
                <div class="modal-body">
                    <div id="upload-demo2" class="center-block"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="cropImageBtn2" class="btn btn-primary">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/wizard/wizard-2.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-timepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/components/croppie/croppie.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">

    $('#country_id').select2();
    $('#city_id').select2();
    $(".gambar").attr("src", "{{ $listing->banner ? url('/public/uploads/'.$listing->banner) : url('/public/assets/media/personnel_boy.png') }}");
    var $uploadCrop,
        tempFilename,
        rawImg,
        imageId;
    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.upload-demo').addClass('ready');
                $('#cropImagePop').modal('show');
                rawImg = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#upload-demo').croppie({
        viewport: {
            width: 300,
            height: 213
        },
        boundary: {width: 400, height: 284},
        enforceBoundary: false,
        enableExif: true
    });

    $('#cropImagePop').on('shown.bs.modal', function(){
        // alert('Shown pop');
        $uploadCrop.croppie('bind', {
            url: rawImg
        }).then(function(){
            console.log('jQuery bind complete');
        });
    });

    $('.item-img').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
        $('#cancelCropBtn').data('id', imageId); readFile(this); });
    $('#cropImageBtn').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'base64',
            format: 'jpeg',
            size: {width: 1200, height: 850}
        }).then(function (resp) {
            $('#item-img-output').attr('src', resp);
            $('#imagebase64').val(resp);
            $('#cropImagePop').modal('hide');
        });
    });

    $('#country_id').change(function(){
        var country_id = $(this).val();
        $('#city_id').html('');
        $('#city_id').append($("<option></option>").val('').html('Select City'));
        $.get("{{ route('admin.gym.cities') }}", {country_id: country_id}, function(res) {
            if(res) {

                if(res['status'] == 1) {
                    var data = res['data'];
                    // data = data;
                    for(var i = 0; i < data.length; i++)
                    {
                        $('#city_id').append($("<option></option>").val(data[i]['id']).html(data[i]['name']));
                    }
                }
            }
        });
    });

    $(".gambar2").attr("src", "{{ $listing->logo ? url('/public/uploads/'.$listing->logo) : url('/public/assets/media/personnel_boy.png') }}");
    var $uploadCrop2,
        tempFilename2,
        rawImg2,
        imageId2;
    function readFile2(input2) {
        if (input2.files && input2.files[0]) {
            var reader2 = new FileReader();
            reader2.onload = function (e) {
                $('.upload-demo2').addClass('ready');
                $('#cropImagePop2').modal('show');
                rawImg2 = e.target.result;
            }
            reader2.readAsDataURL(input2.files[0]);
        }
        else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop2 = $('#upload-demo2').croppie({
        viewport: {
            width: 300,
            height: 300
        },
        boundary: {width: 400, height: 400},
        enforceBoundary: false,
        enableExif: true
    });

    $('#cropImagePop2').on('shown.bs.modal', function(){
        // alert('Shown pop');
        $uploadCrop2.croppie('bind', {
            url: rawImg2
        }).then(function(){
            console.log('jQuery bind complete');
        });
    });

    $('.item-img2').on('change', function () { imageId2 = $(this).data('id'); tempFilename2 = $(this).val();
        $('#cancelCropBtn2').data('id', imageId2); readFile2(this); });
    $('#cropImageBtn2').on('click', function (ev) {
        $uploadCrop2.croppie('result', {
            type: 'base64',
            format: 'jpeg',
            size: {width: 200, height: 200}
        }).then(function (resp) {
            $('#item-img-output2').attr('src', resp);
            $('#imagebase642').val(resp);
            $('#cropImagePop2').modal('hide');
        });
    });

    $('#checkin_token').click(function() {
        window.location.replace("{{ route('admin.gym.regenerate_token', $listing->id) }}?checkin_token=1");
    });

    $('#checkout_token').click(function() {
        window.location.replace("{{ route('admin.gym.regenerate_token', $listing->id) }}?checkout_token=1");
    });
</script>
@endsection
