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
                                        <label class="col-form-label col-lg-3 col-sm-12">Patient *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('patient_id', $patients, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'patient_id', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Title *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('title', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter title</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Description *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('description', null, ['class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please write description</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Type *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('type', ["Medical Condition"=>"Medical Condition","Health Record"=>"Health Record"], null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'type', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Attachment *</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" width="200px"/>
                                                </figure>
                                                <input type="file" class="item-img file center-block" name="attachment"/>
                                                <input type="hidden" id="imagebase64" name="file_photo">
                                            </label>
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

    $(".gambar").attr("src", "{{ $record->attachment ? url('/public/uploads/images/'.$record->attachment) : url('/public/assets/media/personnel_boy.png') }}");
   
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

    $('#patient_id, #kt_select2_3_validate').select2({
        placeholder: "Select Patient",
    });

    $('#type, #kt_select2_3_validate').select2({
        placeholder: "Select Type",
    });
</script>

@endsection
