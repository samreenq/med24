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
                            {{ Form::model($offer, ['enctype' => 'multipart/form-data']) }}

                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Offer Name *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Offer Name', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter offer name.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Hospitals</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                            	{{ Form::select('hospital_id[]', $hospitals, $offer->hospital->pluck('id'), ['id' => 'hospital_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Hospital', 'multiple']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select hospitals.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Doctors</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                            	{{ Form::select('doctor_id[]', $doctors, $offer->doctor->pluck('id'), ['id' => 'doctor_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Doctor', 'multiple']) }}
                                            </div>
                                        	<span class="form-text text-muted">Please select doctors.</span>    
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Pharmacies</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                            	{{ Form::select('pharmacy_id[]', $pharmacies, $offer->pharmacy->pluck('id'), ['id' => 'pharmacy_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Pharmacy', 'multiple']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select pharmacies.</span>
                                        </div>                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('discount', null, ['class' => 'form-control', 'placeholder' => 'Enter Discount', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter offer discount.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount Unit *</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('discount_unit', ['%' => 'Percentage (%)', 'amount' => 'Fixed Amount'], null, ['id' => 'discount_unit', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Discount Unit', 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Start Date Time</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group date">
                                                {{ Form::text('start_datetime', null, ['id' => 'start_datetime', 'readonly', 'class' => 'form-control', 'placeholder' => 'Select Start Date', 'required']) }}
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
                                                {{ Form::text('end_datetime', null, ['id' => 'end_datetime', 'readonly', 'class' => 'form-control', 'placeholder' => 'Select End Date', 'required']) }}
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
                                        <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($offer->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Offer Details *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Offer Details']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status.</span>
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
                                                <input type="hidden" id="imagebase64" name="image">
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
    $('#hospital_id').select2();
	$('#doctor_id').select2();
	$('#pharmacy_id').select2();
	
    $('#discount_unit').select2();

    $(".gambar").attr("src", "{{ $offer->image ? url('/public/uploads/'.$offer->image) : url('/public/assets/media/personnel_boy.png') }}");
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
            height: 200
        },
        boundary: { width: 400, height: 267 },
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
            size: {width: 900, height: 600}
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
</script>
@endsection
