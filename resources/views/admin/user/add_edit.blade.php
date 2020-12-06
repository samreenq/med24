@extends('admin.layouts.main')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
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

                    {{ Form::model($user, ['enctype' => 'multipart/form-data']) }}

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

                                    @if(\Auth::user()->can('admin'))
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Roles</label>
                                        <div class=" col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('roles[]', $roles, null, ['placeholder' => 'Select User Roles', 'class' => 'form-control kt-selectpicker', 'id' => 'roles', 'multiple']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">User Type *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('user_type', $user_types, (isset($user_type)) ? $user_type : '', ['placeholder' => 'Select User Type', 'class' => 'form-control kt-selectpicker', 'id' => 'user_type', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    @elseif(\Auth::user()->can('gym owner'))
                                    {{ Form::hidden('user_type', 'branch manager') }}
                                    @endif

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
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div></div>
                                            <div class="custom-file">
                                                {{ Form::file('image', ['id' => 'customFile', 'class' => 'custom-file-input']) }}
                                                <label style="text-align: left;" class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                            <img style="{{ ($user->image) ? '' : 'display: none' }}" id="blah" src="{{ 'public/uploads/'.$user->image }}" alt="your image" class="custom_input_img" />
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


                        <div class="col-lg-6 permissions_admin" style="display: {{ (isset($user) && $user->can('admin')) ? 'block' : 'none' }}">
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
                                        <td>Manage Users</td>
                                        <td>{{ Form::checkbox('permissions[view users]', 'view users', $user->hasPermissionTo('view users')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create user]', 'create user', $user->hasPermissionTo('create user')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit user]', 'edit user', $user->hasPermissionTo('edit user')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete user]', 'delete user', $user->hasPermissionTo('delete user')) }}</td>
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
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6 permissions_gym_owner" style="display: {{ (isset($user) && $user->can('gym owner') && !$user->can('admin')) ? 'block' : 'none' }}">
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
                                        <td>Manage Users</td>
                                        <td>{{ Form::checkbox('permissions_gym[view users]', 'view users', $user->hasPermissionTo('view users')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create user]', 'create user', $user->hasPermissionTo('create user')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit user]', 'edit user', $user->hasPermissionTo('edit user')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete user]', 'delete user', $user->hasPermissionTo('delete user')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gyms</td>
                                        <td>{{ Form::checkbox('permissions_gym[view gyms]', 'view gyms', $user->hasPermissionTo('view gyms')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create gym]', 'create gym', $user->hasPermissionTo('create gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit gym]', 'edit gym', $user->hasPermissionTo('edit gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete gym]', 'delete gym', $user->hasPermissionTo('delete gym')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Classes</td>
                                        <td>{{ Form::checkbox('permissions_gym[view gym classes]', 'view gym classes', $user->hasPermissionTo('view gym classes')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create gym class]', 'create gym class', $user->hasPermissionTo('create gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit gym class]', 'edit gym class', $user->hasPermissionTo('edit gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete gym class]', 'delete gym class', $user->hasPermissionTo('delete gym class')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Offers</td>
                                        <td>{{ Form::checkbox('permissions_gym[view offers]', 'view offers', $user->hasPermissionTo('view offers')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create offer]', 'create offer', $user->hasPermissionTo('create offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit offer]', 'edit offer', $user->hasPermissionTo('edit offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete offer]', 'delete offer', $user->hasPermissionTo('delete offer')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Vouchers</td>
                                        <td>{{ Form::checkbox('permissions_gym[view vouchers]', 'view vouchers', $user->hasPermissionTo('view vouchers')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create voucher]', 'create voucher', $user->hasPermissionTo('create voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit voucher]', 'edit voucher', $user->hasPermissionTo('edit voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete voucher]', 'delete voucher', $user->hasPermissionTo('delete voucher')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>More Permissions</th>
                                        <th>View Sessions</th>
                                        <th>Scan Out</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>{{ Form::checkbox('permissions_gym[view sessions]', 'view sessions', $user->hasPermissionTo('view sessions')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[scanout]', 'scanout', $user->hasPermissionTo('scanout')) }}</td>
                                        <td></td>
                                        <td></td>
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
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/components/croppie/croppie.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#blah').attr('src', e.target.result).show(200);
        //   $('#blah').croppie({
        //     viewport: {
        //         width: 150,
        //         height: 200
        //     }
        //   });
          $('#image_text').hide(200);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#customFile").change(function() {
      readURL(this);
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
