@extends('admin.layouts.main')

@section('styles')
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
                            {{ Form::model($voucher, ['enctype' => 'multipart/form-data']) }}

                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Voucher Name *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Voucher Name', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter voucher name.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Gym</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('gym_id[]', $listings, $voucher->gym->pluck('id'), ['id' => 'gym_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Gym', 'multiple']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">User</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('user_id[]', $users, $voucher->user->pluck('id'), ['id' => 'user_id', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select User', 'multiple']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row" id="discount_head" style="{{ ($voucher->discount_unit == 'free_trainings') ? 'display:none' : '' }}">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('discount', null, ['id' => 'discount', 'class' => 'form-control', 'placeholder' => 'Enter Discount', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter voucher discount.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount Unit *</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('discount_unit', ['%' => 'Percentage (%)', 'amount' => 'Fixed Amount', 'free_trainings' => 'Free Trainings'], null, ['id' => 'discount_unit', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Discount Unit', 'required']) }}
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
                                                {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($voucher->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                            </div>
                                            <span class="form-text text-muted">Please select active status.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Count *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('count', null, ['class' => 'form-control', 'placeholder' => 'Enter Count']) }}
                                            </div>
                                            <span class="form-text text-muted">Please specify the times this code can be used.</span>
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
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>

<script>
    $('#gym_id').select2();
    $('#user_id').select2();
    $('#discount_unit').select2();

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

    $('#discount_unit').change(function(){
        var unit = $(this).val();
        if(unit == 'free_trainings') {
            $('#discount').val('0');
            $('#discount_head').hide(200);
        } else {
            $('#discount_head').show(200);
        }
    });
</script>
@endsection
