@extends('hospital.layouts.main')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">

    <style type="text/css">
        .pac-container { 
            z-index: 10000 !important; 
        }
    </style>
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(count($errors) > 0)
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
                        {{ Form::hidden('user_type', 'user') }}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('name', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Google Map Address *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" name="address" id="googleAddress" class="form-control" value="{{ old('address', $record->address) ?? '' }}" placeholder="Google Map Address" required />
                                                <input type="hidden" name="latitude" value="{{ old('latitude', $record->latitude ?? '') }}" />
                                                <input type="hidden" name="longitude" value="{{ old('longitude', $record->longitude ?? '') }}" />
                                            </div>
                                            <span class="form-text text-muted">Please enter address</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Country *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('country_id', $countries, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'country_id', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">City *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <select class="form-control kt-selectpicker" name="city_id" id="city_id" required>
                                                <option>Select city</option>
                                                @if(isset($cities) && count($cities) > 0)
                                                    @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ $record->city_id == $city->id ? 'selected' : "" }}>{{ $city->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="form-text text-muted cityError">Please select an option</span>
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
                                        <label class="col-form-label col-lg-3 col-sm-12">Confirm Password *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::password('confirmPassword', ['placeholder' => '', 'class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your confirm password</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Phone *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('phone', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                                <div class="input-group-append"><a class="btn btn-brand btn-icon"><i class="la la-phone"></i></a></div>
                                            </div>
                                            <span class="form-text text-muted">Please enter your phone number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Description</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('description', null, ['class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please write description</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Hospitals Specialities *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('specialities_hospitals[]', $specialities_hospitals, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'specialities_hospitals', 'required', 'multiple']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Hospitals Certifications *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('certifications_hospitals[]', $certifications_hospitals, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'certifications_hospitals', 'required', 'multiple']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Hospitals Awards *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('awards_hospitals[]', $awards_hospitals, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'awards_hospitals', 'required', 'multiple']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Hospitals Insurances *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('hospital_insurances[]', $hospital_insurances, null, ['placeholder' => '', 'class' => 'form-control kt-selectpicker', 'id' => 'hospital_insurances', 'required', 'multiple']) }}
                                            <span class="form-text text-muted">Please select an option</span>
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
                                        <label class="col-form-label col-lg-3 col-sm-12">Logo *</label>
                                        <div class="col-xs-12">
                                            <label class="cabinet center-block">
                                                <figure>
                                                    <img src="" class="gambar2 img-responsive img-thumbnail" id="item-img-output2" width="200px"/>
                                                </figure>
                                                <input type="file" class="item-img2 file center-block" name="logo"/>
                                                <input type="hidden" id="imagebase642" name="file_photo2">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Opening Time</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-small bootstrap-timepicker" name="openingTime" value="{{ $record->opening_time ? date('g:i A', strtotime($record->opening_time)) : '' }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted cityError">Please select opening time</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Closing Time</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-small bootstrap-timepicker" name="closingTime" value="{{ $record->closing_time ? date('g:i A', strtotime($record->closing_time)) : '' }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted cityError">Please select closing time</span>
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
                                <div class="alert-text alertMessages">
                                    <div class="alert alert-danger" style="display: none;">
                                        <ul class="errorMessages">
                                        </ul>
                                    </div>
                                    <div class="alert alert-success" style="display: none;">
                                        <ul class="successMessages">
                                            
                                        </ul>
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb6fdTdIaic7zM9K5GTKdUVsVWyPLt738&libraries=places"></script>

    <script type="text/javascript">    
        $(".gambar").attr("src", "{{ $record->image ? url('/public/uploads/hospitals/'.$record->image) : url('/public/assets/media/personnel_boy.png') }}");
        $(".gambar2").attr("src", "{{ $record->logo ? url('/public/uploads/hospitals/'.$record->logo) : url('/public/assets/media/personnel_boy.png') }}");
        
        var $uploadCrop,
            tempFilename,
            rawImg,
            imageId,
            $uploadCrop2,
            tempFilename2,
            rawImg2,
            imageId2;
        
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

        function readFile2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.upload-demo2').addClass('ready');
                    $('#cropImagePop2').modal('show');
                    rawImg2 = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
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
        
        $('#cropImageBtn2').on('click', function (ev) {
            $uploadCrop2.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                size: {width: 300, height: 300}
            }).then(function (resp) {
                $('#item-img-output2').attr('src', resp);
                $('#imagebase642').val(resp);
                $('#cropImagePop2').modal('hide');
            });
        });
        
        $(document).on('change', '#country_id', function (e){
            e.preventDefault();

            $('#city_id').empty();

            $.ajax({
                url : "{{ route('admin.ajax.getCity') }}",
                method : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    countryId : $(this).val(),
                },

                success: function (response){
                    if(response.status == 1){
                        $('#city_id').prepend('<option>Select city</option>');

                        $.each(response.data, function (index, value){
                            $('#city_id').prepend('<option value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }else{
                        $('#city_id').prepend('<option>No city found</option>');

                        $('.cityError').text(response.message);
                    }
                },
            });
        });

        $('#roles, #kt_select2_3_validate').select2({
            placeholder: "Select User Roles",
        });

       $('#country_id, #kt_select2_3_validate').select2({
            placeholder: "Select Country",
        });
        
        $('#city_id, #kt_select2_3_validate').select2({
            placeholder: "Select City",
        });
        
        $('#specialities_hospitals, #kt_select2_3_validate').select2({
            placeholder: "Select Hospital Specialities",
        });
        
        $('#certifications_hospitals, #kt_select2_3_validate').select2({
            placeholder: "Select Hospital Certifications",
        });
        
        $('#awards_hospitals, #kt_select2_3_validate').select2({
            placeholder: "Select Hospital Awards",
        });

        $('#hospital_insurances, #kt_select2_3_validate').select2({
            placeholder: "Select Insurances",
        });

        $('.bootstrap-timepicker').timepicker();

        var places = new google.maps.places.Autocomplete(document.getElementById('googleAddress'));

        google.maps.event.addListener(places, 'place_changed', function (){
            var place = places.getPlace();
            $('input[name="latitude"]').val(place.geometry.location.lat());
            $('input[name="longitude"]').val(place.geometry.location.lng());
        });
    </script>
@endsection