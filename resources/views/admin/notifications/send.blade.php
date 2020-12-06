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

                    <div class="row">
                        <div class="col-lg-12">
                            <!--begin::Form-->
                            {{ Form::open() }}
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Trigger Type *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::select('trigger_type', $trigger_types, null, ['id' => 'trigger_type','class' => 'form-control kt-select2', 'placeholder' => 'Select Trigger Type']) }}
                                        </div>
                                        <span class="form-text text-muted">Please select trigger type.</span>
                                    </div>
                                </div>
                                <div class="form-group row div_trigger_id" >
                                    <label class="col-form-label col-lg-3 col-sm-12">Trigger *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::select('trigger_id', [], null, ['id' => 'trigger_id','class' => 'form-control kt-select2', 'placeholder' => 'Select Trigger']) }}
                                        </div>
                                        <span class="form-text text-muted">Please select trigger.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Users</label>
                                    <div class=" col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::select('user_id[]', $users, null, ['id' => 'user_id','class' => 'form-control kt-select2', 'multiple']) }}
                                            
                                        </div>
                                        <span class="form-text text-muted">Do not select any user to send all</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Device Type</label>
                                    <div class=" col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::select('device_type[]', $device_types, null, ['id' => 'device_type', 'class' => 'form-control kt-select2', 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Title</label>
                                    <div class=" col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control kt-select2', 'placeholder' => 'Enter Notification Title']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Message</label>
                                    <div class=" col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::textarea('message', null, ['id' => 'message', 'class' => 'form-control kt-select2', 'placeholder' => 'Enter Notification Text']) }}
                                        </div>
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
                            </form>
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

    <script type="text/javascript">
        $('#trigger_type').change(function () {
            var trigger_type = $(this).val();
            $('#trigger_id').html('');
            if(trigger_type == 'offer') {
                $('.div_trigger_id').show(200);
                $('#trigger_id').append($("<option></option>").val('').html('Select Offer'));
            } else if(trigger_type == 'gym') {
                $('.div_trigger_id').show(200);
                $('#trigger_id').append($("<option></option>").val('').html('Select Gym'));
            } else {
                $('.div_trigger_id').hide(200);
                return false;
            }
            $.get("{{ route('admin.notification.get_trigger_data') }}", {trigger_type: trigger_type}, function(res) {
                if(res) {

                    if(res['status'] == 1) {
                        var data = res['data'];
                        // console.log(data[1]);
                        var keys = Object.keys(data);
                        // alert(keys.length);
                        for(var i = 0; i < keys.length; i++)
                        {
                            $('#trigger_id').append($("<option></option>").val(keys[i]).html(data[keys[i]]));
                        }
                    }
                }
            });
        });

        $('#trigger_type, #kt_select2_3_validate').select2({
            placeholder: "Select Trigger Type",
        });

        $('#trigger_id, #kt_select2_3_validate').select2();

        $('#user_id, #kt_select2_3_validate').select2({
            placeholder: "Select User",
        });

        $('#device_type, #kt_select2_3_validate').select2({
            placeholder: "Select Device Type",
        });
    </script>
@endsection
