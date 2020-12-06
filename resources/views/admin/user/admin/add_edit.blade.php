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
                                Create User
                            </h3>
                        </div>
                    </div>

                    @if($user->status == 0 || $user->status)
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <div class="col-lg-12 ml-lg-auto">
                                    @if($user->status == 1)
                                        <a data-id="2" class="update-status btn btn-danger">Block</a>
                                        <a data-id="0" class="update-status btn btn-warning">De-Active</a>
                                    @elseif($user->status == 2)
                                        <a data-id="1" class="update-status btn btn-brand" style="color: white">Un-Block</a>
                                    @elseif($user->status == 0)
                                        <a data-id="1" class="update-status btn btn-primary" style="color: white">Active</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{ Form::model($user, ['enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('user_type', 'admin') }}

                    <div class="row">
                        <div class="col-lg-6">
                            <!--begin::Form-->
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">First Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('first_name', null, ['placeholder' => 'Enter your First Name', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your first name.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Last Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('last_name', null, ['placeholder' => 'Enter your Last Name', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your last name.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Email *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::email('email', null, ['placeholder' => 'Enter your Email Name', 'class' => 'form-control', 'required']) }}
                                            <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Phone</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('phone', null, ['placeholder' => 'Enter your Phone Number', 'class' => 'form-control', 'required']) }}
                                                <div class="input-group-append"><a class="btn btn-brand btn-icon"><i class="la la-phone"></i></a></div>
                                            </div>
                                            <span class="form-text text-muted">Please enter your phone number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Password *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::password('password', ['placeholder' => 'Enter your Password', 'class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your password.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Gender *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('gender', $genders, null, ['placeholder' => 'Select Gender', 'class' => 'form-control kt-selectpicker', 'id' => 'gender']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Roles</label>
                                        <div class=" col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('roles[]', $roles, null, ['placeholder' => 'Select User Roles', 'class' => 'form-control kt-selectpicker', 'id' => 'roles', 'multiple']) }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($user->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
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
                            <!--end::Form-->
                        </div>


                        <div class="col-lg-6 permissions_admin">
                            <h1>Permissions</h1>
                            <table class="table-checkable table-bordered" id="html_table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>View</th>
                                        <th>Create</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Manage Admin</td>
                                        <td>{{ Form::checkbox('permissions[view admins]', 'view admins', $user->hasPermissionTo('view admins')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create admin]', 'create admin', $user->hasPermissionTo('create admin')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit admin]', 'edit admin', $user->hasPermissionTo('edit admin')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete admin]', 'delete admin', $user->hasPermissionTo('delete admin')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Owners</td>
                                        <td>{{ Form::checkbox('permissions[view gym owners]', 'view gym owners', $user->hasPermissionTo('view gym owners')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym owner]', 'create gym owner', $user->hasPermissionTo('create gym owner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym owner]', 'edit gym owner', $user->hasPermissionTo('edit gym owner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym owner]', 'delete gym owner', $user->hasPermissionTo('delete gym owner')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Customers</td>
                                        <td>{{ Form::checkbox('permissions[view customers]', 'view customers', $user->hasPermissionTo('view customers')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create customer]', 'create customer', $user->hasPermissionTo('create customer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit customer]', 'edit customer', $user->hasPermissionTo('edit customer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete customer]', 'delete customer', $user->hasPermissionTo('delete customer')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Roles</td>
                                        <td>{{ Form::checkbox('permissions[view roles]', 'view roles', $user->hasPermissionTo('view roles')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create role]', 'create role', $user->hasPermissionTo('create role')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit role]', 'edit role', $user->hasPermissionTo('edit role')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete role]', 'delete role', $user->hasPermissionTo('delete role')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Countries</td>
                                        <td>{{ Form::checkbox('permissions[view countries]', 'view countries', $user->hasPermissionTo('view countries')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create country]', 'create country', $user->hasPermissionTo('create country')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit country]', 'edit country', $user->hasPermissionTo('edit country')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete country]', 'delete country', $user->hasPermissionTo('delete country')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Cities</td>
                                        <td>{{ Form::checkbox('permissions[view cities]', 'view cities', $user->hasPermissionTo('view cities')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create city]', 'create city', $user->hasPermissionTo('create city')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit city]', 'edit city', $user->hasPermissionTo('edit city')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete city]', 'delete city', $user->hasPermissionTo('delete city')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Amenities</td>
                                        <td>{{ Form::checkbox('permissions[view amenities]', 'view amenities', $user->hasPermissionTo('view amenities')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create amenity]', 'create amenity', $user->hasPermissionTo('create amenity')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit amenity]', 'edit amenity', $user->hasPermissionTo('edit amenity')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete amenity]', 'delete amenity', $user->hasPermissionTo('delete amenity')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gyms</td>
                                        <td>{{ Form::checkbox('permissions[view gyms]', 'view gyms', $user->hasPermissionTo('view gyms')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym]', 'create gym', $user->hasPermissionTo('create gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym]', 'edit gym', $user->hasPermissionTo('edit gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym]', 'delete gym', $user->hasPermissionTo('delete gym')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Classes</td>
                                        <td>{{ Form::checkbox('permissions[view gym classes]', 'view gym classes', $user->hasPermissionTo('view gym classes')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym class]', 'create gym class', $user->hasPermissionTo('create gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym class]', 'edit gym class', $user->hasPermissionTo('edit gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym class]', 'delete gym class', $user->hasPermissionTo('delete gym class')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Images</td>
                                        <td>{{ Form::checkbox('permissions[view gym images]', 'view gym images', $user->hasPermissionTo('view gym images')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym image]', 'create gym image', $user->hasPermissionTo('create gym image')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym image]', 'edit gym image', $user->hasPermissionTo('edit gym image')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym image]', 'delete gym image', $user->hasPermissionTo('delete gym image')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Offers</td>
                                        <td>{{ Form::checkbox('permissions[view offers]', 'view offers', $user->hasPermissionTo('view offers')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create offer]', 'create offer', $user->hasPermissionTo('create offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit offer]', 'edit offer', $user->hasPermissionTo('edit offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete offer]', 'delete offer', $user->hasPermissionTo('delete offer')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Vouchers</td>
                                        <td>{{ Form::checkbox('permissions[view vouchers]', 'view vouchers', $user->hasPermissionTo('view vouchers')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create voucher]', 'create voucher', $user->hasPermissionTo('create voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit voucher]', 'edit voucher', $user->hasPermissionTo('edit voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete voucher]', 'delete voucher', $user->hasPermissionTo('delete voucher')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage FAQ</td>
                                        <td>{{ Form::checkbox('permissions[view faqs]', 'view faqs', $user->hasPermissionTo('view faqs')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create faq]', 'create faq', $user->hasPermissionTo('create faq')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit faq]', 'edit faq', $user->hasPermissionTo('edit faq')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete faq]', 'delete faq', $user->hasPermissionTo('delete faq')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Banners</td>
                                        <td>{{ Form::checkbox('permissions[view banners]', 'view banners', $user->hasPermissionTo('view banners')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create banner]', 'create banner', $user->hasPermissionTo('create banner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit banner]', 'edit banner', $user->hasPermissionTo('edit banner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete banner]', 'delete banner', $user->hasPermissionTo('delete banner')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Notifications</td>
                                        <td>{{ Form::checkbox('permissions[view notifications]', 'view banners', $user->hasPermissionTo('view notifications')) }}</td>
                                        <td>{{ Form::checkbox('permissions[send notification]', 'create banner', $user->hasPermissionTo('send notification')) }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>More Permissions</th>
                                        <th>View Sessions</th>
                                        <th>Scan Out</th>
                                        <th>Terms & Conditions</th>
                                        <th>Privacy Policy</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>{{ Form::checkbox('permissions[view sessions]', 'view sessions', $user->hasPermissionTo('view sessions')) }}</td>
                                        <td>{{ Form::checkbox('permissions[scanout]', 'scanout', $user->hasPermissionTo('scanout')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit toc]', 'edit toc', $user->hasPermissionTo('edit toc')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit privacy_policy]', 'edit privacy_policy', $user->hasPermissionTo('edit privacy_policy')) }}</td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>View Logs</th>
                                        <th>Feedback</th>
                                        <th>Commission</th>
                                        <th>Newsletter</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>{{ Form::checkbox('permissions[view logs]', 'view logs', $user->hasPermissionTo('view logs')) }}</td>
                                        <td>{{ Form::checkbox('permissions[view feedback]', 'view feedback', $user->hasPermissionTo('view feedback')) }}</td>
                                        <td>{{ Form::checkbox('permissions[view commissions]', 'view commissions', $user->hasPermissionTo('view commissions')) }}</td>
                                        <td>{{ Form::checkbox('permissions[view newsletters]', 'view newsletters', $user->hasPermissionTo('view newsletters')) }}</td>
                                    </tr>
                                </tbody>
                            </table>
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

<script type="text/javascript">
    @if($user->status != null)
    $('.update-status').click(function() {
        var status = $(this).attr('data-id');
        window.location.replace("{{ route('admin.user.admin.update_status', $user->id) }}?status="+status);
    });
    @endif

    $(".gambar").attr("src", "{{ $user->image ? url('/public/uploads/'.$user->image) : url('/public/assets/media/personnel_boy.png') }}");
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

    $('#user_type').click(function(){
        if($(this).val() == 'admin') {
            $('.permissions_admin').show(200);
            $('.permissions_gym_owner').hide(200);
        } else if($(this).val() == 'gym owner') {
            $('.permissions_admin').hide(200);
            $('.permissions_gym_owner').show(200);
        } else {
            $('.permissions_admin').hide(200);
            $('.permissions_gym_owner').hide(200);
        }
    });
</script>

@endsection
