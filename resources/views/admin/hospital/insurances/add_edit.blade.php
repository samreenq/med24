@extends('admin.layouts.main')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(count($errors ) > 0)
                <div class="alert alert-light alert-elevate" role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i>
                            <div class="alert-text">{{ $error }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ ucwords($title) }}
                            </h3>
                        </div>
                    </div>
                    {{ Form::model($record, ['enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Insurance  *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::text('name', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                        </div>
                                        <span class="form-text text-muted">Please enter {{ $title }}.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Email  *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::text('email', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                        </div>
                                        <span class="form-text text-muted">Please enter {{ $title }}.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Description *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::textarea('description', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                        </div>
                                        <span class="form-text text-muted">Please enter {{ $title }}.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Profile Photo *</label>
                                    <div class="col-xs-12">
                                        <label class="cabinet center-block">
                                            <figure>
                                                <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" width="200px"/>
                                            </figure>
                                            <input type="file" class="item-img file center-block" name="profilePhoto"/>
                                            <input type="hidden" id="imagebase64" name="photo1">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Cover Photo *</label>
                                    <div class="col-xs-12">
                                        <label class="cabinet center-block">
                                            <figure>
                                                <img src="" class="gambar2 img-responsive img-thumbnail" id="item-img-output2" width="200px"/>
                                            </figure>
                                            <input type="file" class="item-img2 file center-block" name="coverPhoto"/>
                                            <input type="hidden" id="imagebase642" name="photo2">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                    <div class="col-lg-4 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($record->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                        </div>
                                        <span class="form-text text-muted">Please select active status.</span>
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
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
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
    <div class="modal fade" id="cropImagePop2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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
    <script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/demo1/pages/components/croppie/croppie.min.js') }}" type="text/javascript"></script>
    {{--<script src="{{ asset('public/assets/js/demo1/pages/upload.js') }}" type="text/javascript"></script>--}}

    <script type="text/javascript">
        $(".gambar").attr("src", "{{ $record->profilePhoto ? url('/public/uploads/'.$record->profilePhoto) : url('/public/assets/media/personnel_boy.png') }}");

        $(".gambar2").attr("src", "{{ $record->coverPhoto ? url('/public/uploads/'.$record->coverPhoto) : url('/public/assets/media/personnel_boy.png') }}");
        
        var $uploadCrop,
            tempFilename,
            rawImg,
            imageId,
            $uploadCrop2,
            tempFilename2,
            rawImg2,
            imageId2;
        
        function readFile(input){
            if (input.files && input.files[0]){
                var reader = new FileReader();

                reader.onload = function (e){
                    $('.upload-demo').addClass('ready');

                    $('#cropImagePop').modal('show');

                    rawImg = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }else{
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: 300,
                height: 300
            },
            boundary: {
                width: 400, 
                height: 400
            },
            enforceBoundary: false,
            enableExif: true
        });

        $('#cropImagePop').on('shown.bs.modal', function(){
            $uploadCrop.croppie('bind', {
                url: rawImg
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        $('.item-img').on('change', function (){
            imageId = $(this).data('id');

            tempFilename = $(this).val();    

            $('#cancelCropBtn').data('id', imageId);

            readFile(this);
        });
        
        $('#cropImageBtn').on('click', function (ev){
            $uploadCrop.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                size: {
                    width: 300, 
                    height: 300
                }
            }).then(function (resp){
                $('#item-img-output').attr('src', resp);

                $('#imagebase64').val(resp);

                $('#cropImagePop').modal('hide');
            });
        });

        function readFile2(input){
            if (input.files && input.files[0]){
                var reader = new FileReader();

                reader.onload = function (e){
                    $('.upload-demo2').addClass('ready');

                    $('#cropImagePop2').modal('show');

                    rawImg2 = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }else{
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop2 = $('#upload-demo2').croppie({
            viewport: {
                width: 300,
                height: 300
            },
            boundary: {
                width: 400, 
                height: 400
            },
            enforceBoundary: false,
            enableExif: true
        });

        $('#cropImagePop2').on('shown.bs.modal', function(){
            $uploadCrop2.croppie('bind', {
                url: rawImg2
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        $('.item-img2').on('change', function (){
            imageId2 = $(this).data('id');

            tempFilename2 = $(this).val();   

            $('#cancelCropBtn2').data('id', imageId2);

            readFile2(this);
        });
        
        $('#cropImageBtn2').on('click', function (ev){
            $uploadCrop2.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                size: {
                    width: 300, 
                    height: 300
                }
            }).then(function (resp){
                $('#item-img-output2').attr('src', resp);

                $('#imagebase642').val(resp);

                $('#cropImagePop2').modal('hide');
            });
        });
    </script>
@endsection