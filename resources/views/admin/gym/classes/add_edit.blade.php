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
                            {{ Form::model($class, ['enctype' => 'multipart/form-data']) }}
                                
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Class Type *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('class_type', null, ['class' => 'form-control', 'placeholder' => 'Enter Class Type', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter class type.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Days</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            {{ Form::select('day', $days, null, ['id' => 'days', 'class' => 'form-control kt-selectpicker', 'placeholder' => 'Select Days', 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Start Time</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group timepicker">
                                                {{ Form::text('start_time', null, ['class' => 'form-control', 'id' => 'kt_timepicker_2', 'placeholder' => 'Select Start Time']) }}
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted">Please select start time</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">End Time</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group timepicker">
                                                {{ Form::text('end_time', null, ['class' => 'form-control', 'id' => 'kt_timepicker_2', 'placeholder' => 'Select End Time']) }}
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="form-text text-muted">Please select end time</span>
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
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-timepicker.js') }}" type="text/javascript"></script>

<script>
    $('#days').select2();
</script>
@endsection