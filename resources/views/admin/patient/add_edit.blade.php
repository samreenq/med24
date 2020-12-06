@extends('admin.layouts.main')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">
<style>
label.cabinet input.file {
    opacity: 1 !important;
}
</style>
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Portlet-->
        <div class="row">
            <div class="col-lg-12">

                @if ( count( $errors ) > 0 )
                <div class="alert alert-light alert-elevate" role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i>
                            <div class="alert-text">{{ $error }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ ucwords($title) }}
                            </h3>
                        </div>
                    </div>

                    {{ Form::model($record, ['enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('user_type', 'user') }}

                    <div class="row">
                        <div class="col-lg-6">
                            <!--begin::Form-->
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">First Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('first_name', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your first name</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Last Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('last_name', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your last name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Gender *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('gender', $genders, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'gender', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Email *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::email('email', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">We'll never share your email with anyone else</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Password *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::password('password', ['placeholder' => '', 'class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your password</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Phone *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('phone_no', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                                <div class="input-group-append"><a class="btn btn-brand btn-icon"><i class="la la-phone"></i></a></div>
                                            </div>
                                            <span class="form-text text-muted">Please enter your phone number</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">ID Card (Front)</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">                                            
                                                <figure>
                                                    <img src="" class="id_card_front img-responsive img-thumbnail" width="200px"/>
                                                </figure>
                                                <input type="file" class="file center-block" name="id_card_front"/>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">ID Card (Back)</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="id_card_back img-responsive img-thumbnail" width="200px"/>
                                                </figure>
                                                <input type="file" class="file center-block" name="id_card_back"/>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Insurance ID (Front)</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="insurance_id_front img-responsive img-thumbnail" width="200px"/>
                                                </figure>
                                                <input type="file" class="file center-block" name="insurance_card_front"/>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Insurance ID (Back)</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="insurance_id_back img-responsive img-thumbnail" width="200px"/>
                                                </figure>
                                                <input type="file" class="file center-block" name="insurance_card_back"/>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Image *</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" width="200px"/>
                                                </figure>
                                                <input type="file" class="item-img file center-block" name="image"/>
                                                <input type="hidden" id="imagebase64" name="file_photo">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($record->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-9 ml-lg-auto">
                                                <button type="submit" class="btn btn-brand btn-submit">Submit</button>
                                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end::Form-->
                        </div>
                    </div>
                    {{ Form::close() }}

                </div>

                <!--end::Portlet-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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
{{--<script src="{{ asset('public/assets/js/demo1/pages/upload.js') }}" type="text/javascript"></script>--}}

<script type="text/javascript">
    @if($record->status != null)
    $('.update-status').click(function() {
        var status = $(this).attr('data-id');
        window.location.replace("{{ route('admin.user.customer.update_status', $record->id) }}?status="+status);
    });
    @endif

    $(".id_card_front").attr("src", "{{ $record->id_card_front ? url('/public/uploads/images/'.$record->id_card_front) : url('/public/assets/media/personnel_boy.png') }}");
	$(".id_card_back").attr("src", "{{ $record->id_card_back ? url('/public/uploads/images/'.$record->id_card_back) : url('/public/assets/media/personnel_boy.png') }}");
	$(".insurance_id_front").attr("src", "{{ $record->insurance_id_front ? url('/public/uploads/images/'.$record->insurance_id_front) : url('/public/assets/media/personnel_boy.png') }}");
	$(".insurance_id_back").attr("src", "{{ $record->insurance_id_back ? url('/public/uploads/images/'.$record->insurance_id_back) : url('/public/assets/media/personnel_boy.png') }}");
	
	$(".gambar").attr("src", "{{ $record->image ? url('/public/uploads/images/'.$record->image) : url('/public/assets/media/personnel_boy.png') }}");
   
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
            height: 300
        },
        boundary: {width: 400, height: 400},
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
            size: {width: 300, height: 300}
        }).then(function (resp) {
            $('#item-img-output').attr('src', resp);
            $('#imagebase64').val(resp);
            $('#cropImagePop').modal('hide');
        });
    });

    $('#roles, #kt_select2_3_validate').select2({
        placeholder: "Select User Roles",
    });

    $('#gender, #kt_select2_3_validate').select2({
        placeholder: "Select Gender",
    });
	
	$('#country_id, #kt_select2_3_validate').select2({
        placeholder: "Select Country",
    });
	
	$('#city_id, #kt_select2_3_validate').select2({
        placeholder: "Select City",
    });
	
	$('#hospitals, #kt_select2_3_validate').select2({
        placeholder: "Select Hospital",
    });
	
	$('#specialities, #kt_select2_3_validate').select2({
        placeholder: "Select Speciality",
    });
	
	$('#certifications, #kt_select2_3_validate').select2({
        placeholder: "Select Certification",
    });
</script>

@endsection
