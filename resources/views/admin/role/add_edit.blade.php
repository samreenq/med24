@extends('admin.layouts.main')

@section('styles')

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
                                {{ $title }}
                            </h3>
                        </div>
                    </div>

                    {{ Form::model($role) }}

                    <div class="row">
                        <div class="col-lg-6">
                            <!--begin::Form-->
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Role Name *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('name', null, ['placeholder' => 'Enter your Role Name', 'class' => 'form-control']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter your role name.</span>
                                        </div>
                                    </div>
                                    @if(\Auth::user()->can('admin'))
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Role Type *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            {{ Form::select('role_type', $role_types, (isset($role_type)) ? $role_type : '', ['placeholder' => 'Select Role Type', 'class' => 'form-control kt-selectpicker', 'id' => 'role_type', 'required']) }}
                                            <span class="form-text text-muted">Please select an option</span>
                                        </div>
                                    </div>
                                    @elseif(\Auth::user()->can('gym owner'))
                                    {{ Form::hidden('role_type', 'branch manager') }}
                                    @endif
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


                        <div class="col-lg-6 permissions_admin" style="display: {{ (isset($role) && $role->hasPermissionTo('admin')) ? 'block' : 'none' }}">
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
                                        <td>{{ Form::checkbox('permissions[view users]', 'view users', $role->hasPermissionTo('view users')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create user]', 'create user', $role->hasPermissionTo('create user')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit user]', 'edit user', $role->hasPermissionTo('edit user')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete user]', 'delete user', $role->hasPermissionTo('delete user')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Roles</td>
                                        <td>{{ Form::checkbox('permissions[view roles]', 'view roles', $role->hasPermissionTo('view roles')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create role]', 'create role', $role->hasPermissionTo('create role')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit role]', 'edit role', $role->hasPermissionTo('edit role')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete role]', 'delete role', $role->hasPermissionTo('delete role')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Countries</td>
                                        <td>{{ Form::checkbox('permissions[view countries]', 'view countries', $role->hasPermissionTo('view countries')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create country]', 'create country', $role->hasPermissionTo('create country')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit country]', 'edit country', $role->hasPermissionTo('edit country')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete country]', 'delete country', $role->hasPermissionTo('delete country')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Cities</td>
                                        <td>{{ Form::checkbox('permissions[view cities]', 'view cities', $role->hasPermissionTo('view cities')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create city]', 'create city', $role->hasPermissionTo('create city')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit city]', 'edit city', $role->hasPermissionTo('edit city')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete city]', 'delete city', $role->hasPermissionTo('delete city')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Amenities</td>
                                        <td>{{ Form::checkbox('permissions[view amenities]', 'view amenities', $role->hasPermissionTo('view amenities')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create amenity]', 'create amenity', $role->hasPermissionTo('create amenity')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit amenity]', 'edit amenity', $role->hasPermissionTo('edit amenity')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete amenity]', 'delete amenity', $role->hasPermissionTo('delete amenity')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gyms</td>
                                        <td>{{ Form::checkbox('permissions[view gyms]', 'view gyms', $role->hasPermissionTo('view gyms')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym]', 'create gym', $role->hasPermissionTo('create gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym]', 'edit gym', $role->hasPermissionTo('edit gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym]', 'delete gym', $role->hasPermissionTo('delete gym')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Classes</td>
                                        <td>{{ Form::checkbox('permissions[view gym classes]', 'view gym classes', $role->hasPermissionTo('view gym classes')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create gym class]', 'create gym class', $role->hasPermissionTo('create gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit gym class]', 'edit gym class', $role->hasPermissionTo('edit gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete gym class]', 'delete gym class', $role->hasPermissionTo('delete gym class')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Offers</td>
                                        <td>{{ Form::checkbox('permissions[view offers]', 'view offers', $role->hasPermissionTo('view offers')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create offer]', 'create offer', $role->hasPermissionTo('create offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit offer]', 'edit offer', $role->hasPermissionTo('edit offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete offer]', 'delete offer', $role->hasPermissionTo('delete offer')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Vouchers</td>
                                        <td>{{ Form::checkbox('permissions[view vouchers]', 'view vouchers', $role->hasPermissionTo('view vouchers')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create voucher]', 'create voucher', $role->hasPermissionTo('create voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit voucher]', 'edit voucher', $role->hasPermissionTo('edit voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete voucher]', 'delete voucher', $role->hasPermissionTo('delete voucher')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage FAQ</td>
                                        <td>{{ Form::checkbox('permissions[view faqs]', 'view faqs', $role->hasPermissionTo('view faqs')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create faq]', 'create faq', $role->hasPermissionTo('create faq')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit faq]', 'edit faq', $role->hasPermissionTo('edit faq')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete faq]', 'delete faq', $role->hasPermissionTo('delete faq')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Banners</td>
                                        <td>{{ Form::checkbox('permissions[view banners]', 'view banners', $role->hasPermissionTo('view banners')) }}</td>
                                        <td>{{ Form::checkbox('permissions[create banner]', 'create banner', $role->hasPermissionTo('create banner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit banner]', 'edit banner', $role->hasPermissionTo('edit banner')) }}</td>
                                        <td>{{ Form::checkbox('permissions[delete banner]', 'delete banner', $role->hasPermissionTo('delete banner')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Notifications</td>
                                        <td>{{ Form::checkbox('permissions[view notifications]', 'view banners', $role->hasPermissionTo('view notifications')) }}</td>
                                        <td>{{ Form::checkbox('permissions[send notification]', 'create banner', $role->hasPermissionTo('send notification')) }}</td>
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
                                        <td>{{ Form::checkbox('permissions[view sessions]', 'view sessions', $role->hasPermissionTo('view sessions')) }}</td>
                                        <td>{{ Form::checkbox('permissions[scanout]', 'scanout', $role->hasPermissionTo('scanout')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit toc]', 'edit toc', $role->hasPermissionTo('edit toc')) }}</td>
                                        <td>{{ Form::checkbox('permissions[edit privacy_policy]', 'edit privacy_policy', $role->hasPermissionTo('edit privacy_policy')) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6 permissions_gym_owner" style="display: {{ (isset($role) && $role->hasPermissionTo('gym owner')) ? 'block' : 'none' }}">
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
                                        <td>{{ Form::checkbox('permissions_gym[view users]', 'view users', $role->hasPermissionTo('view users')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create user]', 'create user', $role->hasPermissionTo('create user')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit user]', 'edit user', $role->hasPermissionTo('edit user')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete user]', 'delete user', $role->hasPermissionTo('delete user')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gyms</td>
                                        <td>{{ Form::checkbox('permissions_gym[view gyms]', 'view gyms', $role->hasPermissionTo('view gyms')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create gym]', 'create gym', $role->hasPermissionTo('create gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit gym]', 'edit gym', $role->hasPermissionTo('edit gym')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete gym]', 'delete gym', $role->hasPermissionTo('delete gym')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Gym Classes</td>
                                        <td>{{ Form::checkbox('permissions_gym[view gym classes]', 'view gym classes', $role->hasPermissionTo('view gym classes')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create gym class]', 'create gym class', $role->hasPermissionTo('create gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit gym class]', 'edit gym class', $role->hasPermissionTo('edit gym class')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete gym class]', 'delete gym class', $role->hasPermissionTo('delete gym class')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Offers</td>
                                        <td>{{ Form::checkbox('permissions_gym[view offers]', 'view offers', $role->hasPermissionTo('view offers')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create offer]', 'create offer', $role->hasPermissionTo('create offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit offer]', 'edit offer', $role->hasPermissionTo('edit offer')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete offer]', 'delete offer', $role->hasPermissionTo('delete offer')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Manage Vouchers</td>
                                        <td>{{ Form::checkbox('permissions_gym[view vouchers]', 'view vouchers', $role->hasPermissionTo('view vouchers')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[create voucher]', 'create voucher', $role->hasPermissionTo('create voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[edit voucher]', 'edit voucher', $role->hasPermissionTo('edit voucher')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[delete voucher]', 'delete voucher', $role->hasPermissionTo('delete voucher')) }}</td>
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
                                        <td>{{ Form::checkbox('permissions_gym[view sessions]', 'view sessions', $role->hasPermissionTo('view sessions')) }}</td>
                                        <td>{{ Form::checkbox('permissions_gym[scanout]', 'scanout', $role->hasPermissionTo('scanout')) }}</td>
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

<script>
    $('#role_type').change(function(){
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
