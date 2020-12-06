@extends('admin.layouts.main')

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
                                            <label class="col-form-label col-lg-3 col-sm-12">Day *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <select class="form-control kt-selectpicker select2" name="hospital" required>
                                                    @foreach($hospitals as $hospital)
                                                    <option value="{{ $hospital->id }}" {{ old('hospital', old('hospital', $record->hospital_id)) == $hospital->id ? 'selected' : '' }}>{{ $hospital->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-muted cityError">Please select hospital</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Day *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <select class="form-control kt-selectpicker select2" name="day" required>
                                                    <option value="Monday" {{ old('day', old('day', $record->day)) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                                    <option value="Tuesday" {{ old('day', old('day', $record->day)) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                    <option value="Wednesday" {{ old('day', $record->day) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                    <option value="Thursday" {{ old('day', $record->day) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                                    <option value="Friday" {{ old('day', $record->day) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                                    <option value="Saturday" {{ old('day', $record->day) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                                    <option value="Sunday" {{ old('day', $record->day) == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                                </select>
                                                <span class="form-text text-muted cityError">Please select day</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Opening Time</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-small bootstrap-timepicker" name="openingTime" value="{{ $record->from ? date('g:i A', strtotime($record->from)) : '' }}">
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
                                                    <input type="text" class="form-control input-small bootstrap-timepicker" name="closingTime" value="{{ $record->to ? date('g:i A', strtotime($record->to)) : '' }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-clock-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="form-text text-muted cityError">Please select closing time</span>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Appointment Interval *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="appointmentInterval" value="{{ old('appointmentInterval', $record->appointment_interval ? $record->appointment_interval : '') }}">
                                                </div>
                                                <span class="form-text text-muted">Please enter appointment interval</span>
                                            </div>
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                            <div class="col-lg-4 col-md-9 col-sm-12">
                                                <div class="input-group">
                                                    {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($record->status == 1) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled']) }}
                                                </div>
                                                <span class="form-text text-muted">Please select active status</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $record->id ?? "" }}">                                    </div>
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
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $('.select2').select2();

    $('.bootstrap-timepicker').timepicker();
</script>
@endsection