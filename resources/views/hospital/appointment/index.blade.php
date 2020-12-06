@extends('hospital.layouts.main')

@section('styles')
    <style type="text/css">
        .yellow {
            color: #0044cc;
        }

        .white {
            color: white;
        }

        .select2 {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @if(Session::has('success'))
        <div class="alert alert-light alert-elevate" role="alert">
            <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
            <div class="alert-text">
                {{ Session::get('success') }}
            </div>
        </div>
        @endif
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon2-line-chart"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        {{ ucwords($title) }}
                        <small></small>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <div class="dropdown dropdown-inline">
                                <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="la la-download"></i> Export
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__section kt-nav__section--first">
                                            <span class="kt-nav__section-text">Choose an option</span>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon la la-print"></i>
                                                <span class="kt-nav__link-text">Print</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon la la-copy"></i>
                                                <span class="kt-nav__link-text">Copy</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                                <span class="kt-nav__link-text">Excel</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon la la-file-text-o"></i>
                                                <span class="kt-nav__link-text">CSV</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon la la-file-pdf-o"></i>
                                                <span class="kt-nav__link-text">PDF</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm addAppointment"><i class="la la-plus"></i>Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden">
                                <i class="la la-cart-plus"></i> New Order
                            </a>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg d-xl-none"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body kt-portlet__body--fit">
                <table class="kt-datatable" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="Field #1">ID</th>
                            <th title="Field #2">Doctor</th>
                            <th title="Field #4">Patient</th>
                            <th title="Field #6">Appointment Date & Time</th>
                            <th title="Field #8">Appointment Status</th>
                            <th title="Field #9">Created At</th>
                            <th title="Field #10">Action</th>
                            <th title="Field #11"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td align="right">{{ ($appointment->doctor->first_name ?? "").' '.($appointment->doctor->last_name ?? "") }}</td>
                            <td align="right">{{ ($appointment->patient->first_name ?? "").' '.($appointment->patient->last_name ?? "") }}</td>
                            </td>
                            <td align="right">{{ date('d-m-Y g:i A', strtotime($appointment->appointment_date.' '.$appointment->appointment_time)) }}</td>
                            <td align="right">{{ $appointment->status }}</td>
                            <td align="right">{{ date('d M Y H:i:s', strtotime($appointment->created_at)) }}</td>
                            <td align="right">
                                <a href="{{ route('hospital.appointments.update.status', ['id' => $appointment->id, 'status' => 'cancelled']) }}" title="Cancel Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-remove"></i>
                                </a>
                                <a href="{{ route('hospital.appointments.update.status', ['id' => $appointment->id, 'status' => 'rejected']) }}" title="Reject Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-ban"></i>
                                </a>
                                <a href="{{ route('hospital.appointments.update.status', ['id' => $appointment->id, 'status' => 'pending']) }}" title="Pending Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-question-circle"></i>
                                </a>
                                <a href="{{ route('hospital.appointments.update.status', ['id' => $appointment->id, 'status' => 'completed']) }}" title="Complete Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-check"></i>
                                </a>
                                <a href="javascript:void(0)" title="Edit Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md editAppointment" data-id="{{ $appointment->id }}">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="{{ route('hospital.appointments.update.status', ['id' => $appointment->id, 'status' => 'scheduled']) }}" title="Schedule Appointment" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-plus"></i>
                                </a>
                            </td>
                            <td align="right"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="saveAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModal" aria-hidden="true">
        <div class="modal-dialog full_modal-dialog modal-lg">
            <div class="modal-content full_modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="appointmentModal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('hospital.appointments.saveAppointment') }}" method="post" id="saveAppointment">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="alert-text alertMessages">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                            @elseif(session()->has('error'))
                            <div class="alert alert-success">
                                {{ session()->get('error') }}
                            </div>
                            @endif
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Doctor *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <select class="form-control select2" name="doctor">
                                            <option>Select Doctor</option>
                                            @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->first_name.' '.$doctor->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="form-text text-muted">Please Select Doctor.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Patient *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <select class="form-control select2" name="patient">
                                            <option>Select Patient</option>
                                            @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->first_name.' '.$patient->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="form-text text-muted">Please Select Patient.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Family Member *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <select class="form-control select2" name="familyMember">
                                            <option value="0">Select Family Member</option>
                                        </select>
                                    </div>
                                    <span class="form-text text-muted">Please Select Patient Family Member.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Insurance</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <select class="form-control select2" name="insurance">
                                            <option value="0">Select Insurance</option>
                                        </select>
                                    </div>
                                    <span class="form-text text-muted">Please Select Patient Family Member.</span>
                                </div>
                            </div>
                            <div class="form-group row dateInput" style="display: none;">
                                <label class="col-form-label col-lg-3 col-sm-12">Appointment Date *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control datePicker" name="appointmentDate">
                                    </div>
                                    <span class="form-text text-muted">Please Choose Appointment Date.</span>
                                </div>
                            </div>
                            <div class="form-group row timeInput" style="display: none;">
                                <label class="col-form-label col-lg-3 col-sm-12">Appointment Time *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <select class="form-control select2" name="appointmentTime">
                                            <option>Select Appointment Time</option>
                                        </select>
                                    </div>
                                    <span class="form-text text-muted">Please Select Appointment Time.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">On Waiting</label>
                                <div class="col-lg-4 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        {{ Form::checkbox('onWaiting', null, null, ['data-switch' => 'true', 'data-on-text' => 'Yes', 'data-handle-width' => '70', 'data-off-text' => 'No']) }}
                                    </div>
                                    <span class="form-text text-muted">Please select waiting status</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Extra Details *</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <div class="input-group">
                                        <textarea class="form-control" name="extraDetails" rows="8"></textarea>
                                    </div>
                                    <span class="form-text text-muted">Please enter extra details.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="recordId" value="">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-brand" id="submit_form">Submit</button>
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/user-table.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    var availableDays = [];
    var cancelledDays = [];
    var appointmentData = [];

    $(document).on('click', '.addAppointment', function (e){
        e.preventDefault();

        $('option:selected', 'select[name="doctor"]').attr('selected', false);
        $('select[name="doctor"] option[value="0"]').attr('selected', false).change();
        $('select[name="hospital"]').html('').html('<option value="0">Select Hospital</option>');
        $('select[name="patient"]').prepend('<option value="0">Select Patient</option>');
        $('option:selected', 'select[name="patient"]').attr('selected', false);
        $('select[name="patient"] option[value="0"]').attr('selected', false);
        $('select[name="familyMember"]').html('').html('<option value="0">Select Family Member</option>');
        $('option:selected', 'select[name="insurance"]').html('').html('<option value="0">Select insurance</option>');
        $('textarea[name="extraDetails"]').val('');
        $('.dateInput').hide();
        $('.timeInput').hide();
        
        $('#appointmentModal').text('Add Appointment');
        $('#saveAppointmentModal').modal('show');

        appointmentData = [];
    });

    $(document).on('click', '.editAppointment', function (e){
        e.preventDefault();

        $('#appointmentModal').text('Edit Appointment');
        $('#saveAppointmentModal').modal('show');

        $.ajax({
            url : "{{ route('hospital.appointments.edit') }}",
            method : "post",
            data : {
                _token : "{{ csrf_token() }}",
                recordId : $(this).data('id'),
            },

            success:function(response){
                if(response.status == 1){
                    $('select[name="doctor"] option[value='+response['data']['doctor_id']+']').attr('selected', true).change();
                    $('select[name="patient"] option[value='+response['data']['patient_id']+']').attr('selected', true).change();
                    $('input[name="recordId"]').val(response['data']['id']);
                    $('textarea[name="extraDetails"]').val(response['data']['extraDetails']);

                    appointmentData = response['data'];
                }else if(response.status == 0){
                    $('.alertMessages').append('\
                        <div class="alert alert-danger">\
                            <ul>\
                                <li>'+response.message+'</li>\
                            </ul>\
                        </div>\
                    ');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $('.alertMessages').append('\
                    <div class="alert alert-danger">\
                        <ul class="multiErrorMessages">\
                        </ul>\
                    </div>\
                ');

                $.each(result.errors, function(key, value){
                    $('.multiErrorMessages').prepend('<li>'+value+'</li>');
                });
            }
        });
    });

    $(document).on('change', 'select[name="doctor"]', function (e){
        e.preventDefault();

        availableDays = [];
        cancelledDays = [];

        $('select[name="insurance"]').empty();

        $('.alertMessages').empty();

        $('.dateInput').hide();

        $('.alertMessages').empty();

        $.ajax({
            url : "{{ route('hospital.appointments.getDoctorInsurances') }}",
            method : "post",
            data : {
                _token : "{{ csrf_token() }}",
                doctorId : $(this).val(),
            },

            success:function(response){
                if(response.status == 1){
                    $('select[name="insurance"]').append('<option value="0">Select Insurance</option>');

                    $.each(response.data, function (key, value){
                        $('select[name="insurance"]').append('<option value="'+value['id']+'">'+(value['name']['en'] ? value['name']['en'] : "")+'</option>');
                    });

                    if(appointmentData['insurance_id']){
                        $('select[name="insurance"] option[value='+appointmentData['insurance_id']+']').attr('selected', true).change();
                    }
                }else if(response.status == 0){
                    $('select[name="insurance"]').append('<option value="0">No Insurance Found</option>');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $.each(result.errors, function(key, value){
                    toastr.error(value);
                });
            }
        });

        $.ajax({
            url : "{{ route('hospital.appointments.getAvailableDays') }}",
            method : "post",
            data : {
                _token : "{{ csrf_token() }}",
                doctorId : $('select[name="doctor"] :selected').val(),
            },

            success:function(response){
                if(response.status == 1){
                    $('.dateInput').show();

                    availableDays = response['data']['availableDays'];

                    cancelledDays = response['data']['cancelledDays'];

                    $('.datePicker').datepicker("refresh");
                }else if(response.status == 0){
                    $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul>\
                                                <li>'+response.message+'</li>\
                                                </ul>\
                                            </div>\
                    ');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul class="multiErrorMessages">\
                                                </ul>\
                                            </div>\
                    ');
                $.each(result.errors, function(key, value){
                    $('.multiErrorMessages').prepend('<li>'+value+'</li>');
                });
            }
        });
    });

    $(document).on('change', 'select[name="patient"]', function (e){
        e.preventDefault();

        $('select[name="familyMember"]').empty();

        $('.alertMessages').empty();

        $.ajax({
            url : "{{ route('hospital.appointments.getPatientFamilyMembers') }}",
            method : "post",
            data : {
                _token : "{{ csrf_token() }}",
                patientId : $(this).val(),
            },

            success:function(response){
                if(response.status == 1){
                    $('select[name="familyMember"]').append('<option value="0">Select Family Member</option>');

                    $.each(response.data, function (key, value){
                        $('select[name="familyMember"]').append('<option value="'+value['id']+'">'+value['name']+'</option>');
                    });

                    if(appointmentData['family_member_id']){
                        $('select[name="familyMember"] option[value='+appointmentData['family_member_id']+']').attr('selected', true).change();
                    }
                }else if(response.status == 0){
                    $('select[name="familyMember"]').append('<option value="0">No Family Member Found</option>');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul class="multiErrorMessages">\
                                                </ul>\
                                            </div>\
                    ');
                $.each(result.errors, function(key, value){
                    $('.multiErrorMessages').prepend('<li>'+value+'</li>');
                });
            }
        });
    });

    $(document).on('change', '.datePicker', function (e){
        e.preventDefault();

        $('select[name="appointmentTime"]').empty();

        $('.alertMessages').empty();

        $('.timeInput').hide();

        $.ajax({
            url : "{{ route('hospital.appointments.getAvailableTimeSlots') }}",
            method : "post",
            data : {
                _token : "{{ csrf_token() }}",
                doctorId : $('select[name="doctor"] :selected').val(),
                appointmentDate : $(this).val(),
            },

            success:function(response){
                if(response.status == 1){
                    $('.timeInput').show();

                    $.each(response['data'], function (key, value){
                        $('select[name="appointmentTime"]').append('<option value="'+value['time']+'">'+value['time']+'</option>');
                    });
                }else if(response.status == 0){
                    $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul>\
                                                <li>'+response.message+'</li>\
                                                </ul>\
                                            </div>\
                    ');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul class="multiErrorMessages">\
                                                </ul>\
                                            </div>\
                    ');
                $.each(result.errors, function(key, value){
                    $('.multiErrorMessages').prepend('<li>'+value+'</li>');
                });
            }
        });
    });

    $(document).on('submit', '#saveAppointment', function (e){
        e.preventDefault();

        $('.alertMessages').empty();

        $.ajax({
            url : $(this).attr('action'),
            method : "post",
            data : new FormData($(this)[0]),
            contentType:false,
            processData:false,

            success:function(response){
                if(response.status == 1){
                    $('.alertMessages').append('<div class="alert alert-success">\
                                                    <ul>\
                                                    <li>'+response.message+'</li>\
                                                    </ul>\
                                                </div>\
                    ');

                    location.reload();
                }else if(response.status == 0){
                    $('.alertMessages').append('<div class="alert alert-danger">\
                                                    <ul>\
                                                    <li>'+response.message+'</li>\
                                                    </ul>\
                                                </div>\
                    ');
                }
            },
            error: function(response, status, error){
                result = $.parseJSON(response.responseText);

                $('.alertMessages').append('<div class="alert alert-danger">\
                                                <ul class="multiErrorMessages">\
                                                </ul>\
                                            </div>\
                    ');
                $.each(result.errors, function(key, value){
                    $('.multiErrorMessages').prepend('<li>'+value+'</li>');
                });
            }
        });
    });

    $('.select2, #kt_select2_3_validate').select2();

    $('.datePicker').datepicker({
        todayHighlight: true,
        autoclose: false,
        pickerPosition: 'bottom-left',
        todayBtn: true,
        format: 'dd-mm-yyyy',
        beforeShowDay: function(date){
            if(availableDays.length !== 0 || cancelledDays.length !== 0){
                if((availableDays[date.getDay()] == date.getDay()) == true || (cancelledDays[date.getDay()] == date.getDay()) == true){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }
    });
</script>
@endsection