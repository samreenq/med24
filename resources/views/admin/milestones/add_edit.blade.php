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
                            {{ Form::model($milestone, ['enctype' => 'multipart/form-data']) }}

                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Milestone Name *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Milestone Name', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter milestone name.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="discount_head" style="{{ ($milestone->discount_unit == 'free_trainings') ? 'display:none' : '' }}">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('discount', null, ['id' => 'discount', 'class' => 'form-control', 'placeholder' => 'Enter Discount', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter milestone discount.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Discount Unit *</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('discount_unit', ['%' => 'Percentage (%)', 'amount' => 'Fixed Amount', 'free_trainings' => 'Free Trainings'], null, ['id' => 'discount_unit', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Discount Unit', 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Expires In Days *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('expires_in_days', null, ['id' => 'expires_in_days', 'class' => 'form-control', 'placeholder' => 'Enter Number of days code will expire', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please Enter Number of days code will expire</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Session Count *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('no_of_sessions', null, ['id' => 'session_count', 'class' => 'form-control', 'placeholder' => 'Enter number of session required to generate this code', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter number of session required to generate this code</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Count *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::number('count', null, ['class' => 'form-control', 'placeholder' => 'Enter Count', 'required']) }}
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
