@extends('admin.layouts.main')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Portlet-->
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ $title }}
                            </h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!--begin::Form-->
                            {{ Form::model($banner, ['enctype' => 'multipart/form-data']) }}

                                <div class="kt-portlet__body">
{{--                                    <div class="form-group row">--}}
{{--                                        <label class="col-form-label col-lg-3 col-sm-12">Select Module Type</label>--}}
{{--                                        <div class=" col-lg-4 col-md-9 col-sm-12">--}}
{{--                                            {{ Form::select('module_type', $module_types, null, ['id' => 'module_type', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Module Type', 'required']) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    {{ Form::hidden('module_type', $module_type) }}
{{--                                    <div class="form-group row listing">--}}
{{--                                        <label class="col-form-label col-lg-3 col-sm-12">Select Gym</label>--}}
{{--                                        <div class=" col-lg-4 col-md-9 col-sm-12">--}}
{{--                                            {{ Form::select('gym_id', $listings, ($banner->module_type == 'gym') ? $banner->module_id : '', ['id' => 'gym_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Listing']) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    @if($module_type == 'offers')
                                    <div class="form-group row offers">
                                        <label class="col-form-label col-lg-3 col-sm-12">Select Offer</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('offer_id', $offers, ($banner->module_type == 'offers') ? $banner->module_id : '', ['id' => 'offer_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Offer']) }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Start Date Time</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group date">
                                                {{ Form::text('start_date_time', null, ['id' => 'start_datetime', 'readonly', 'class' => 'form-control', 'placeholder' => 'Select Start Date', 'required']) }}
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted">Please select start date time</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">End Date Time</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group date">
                                                {{ Form::text('end_date_time', null, ['id' => 'end_datetime', 'readonly', 'class' => 'form-control', 'placeholder' => 'Select End Date', 'required']) }}
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted">Please select end date time</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Status</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group date">
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($banner->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Image *</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" width="200px"/>
                                                </figure>
                                                <input type="file" class="item-img file center-block" name="file_photo"/>
                                                <input type="hidden" id="imagebase64" name="banner_img">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-9 ml-lg-auto">
                                                <button type="submit" class="btn btn-brand">Submit</button>
                                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- </form> -->
                            {{ Form::close() }}
                            <!--end::Form-->
                        </div>
                    </div>

                </div>

                <!--end::Portlet-->
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
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/components/croppie/croppie.min.js') }}" type="text/javascript"></script>

<script>
    $('#gym_id').select2();
    $('#offer_id').select2();
    $('#module_type').select2();

    $(".gambar").attr("src", "{{ $banner->banner_img ? url('/public/uploads/'.$banner->banner_img) : url('/public/assets/media/personnel_boy.png') }}");
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
            height: 180
        },
        boundary: {width: 400, height: 240},
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
            size: {width: 750, height: 452}
        }).then(function (resp) {
            $('#item-img-output').attr('src', resp);
            $('#imagebase64').val(resp);
            $('#cropImagePop').modal('hide');
        });
    });

    $('#start_datetime').datetimepicker({
        todayHighlight: true,
        autoclose: true,
        pickerPosition: 'bottom-left',
        todayBtn: true,
        format: 'yyyy-mm-dd hh:ii:ss'
    });

    $('#end_datetime').datetimepicker({
        todayHighlight: true,
        autoclose: true,
        pickerPosition: 'bottom-left',
        todayBtn: true,
        format: 'yyyy-mm-dd hh:ii:ss'
    });

    $('#module_type').change(function(){
        var type = $(this).val();
        active_module(type);
    });


    {{--$(function(){--}}
    {{--    var type = "{!! $banner->module_type !!}";--}}
    {{--    active_module(type);--}}
    {{--});--}}

    {{--function active_module(type)--}}
    {{--{--}}
    {{--    if(type == 'gym') {--}}
    {{--        $('.offers').hide(200);--}}
    {{--        $('#offer_id').attr('disabled', 'disabled');--}}
    {{--        $('.listing').show(200);--}}
    {{--        $('#gym_id').removeAttr('disabled', 'disabled');--}}
    {{--    } else if(type == 'offers') {--}}
    {{--        $('.listing').hide(200);--}}
    {{--        $('#gym_id').attr('disabled', 'disabled');--}}
    {{--        $('.offers').show(200);--}}
    {{--        $('#offer_id').removeAttr('disabled', 'disabled');--}}
    {{--    } else {--}}
    {{--        $('.listing').hide(200);--}}
    {{--        $('#gym_id').attr('disabled', 'disabled');--}}
    {{--        $('.offers').hide(200);--}}
    {{--        $('#offer_id').attr('disabled', 'disabled');--}}
    {{--    }--}}
    {{--}--}}
</script>
@endsection
