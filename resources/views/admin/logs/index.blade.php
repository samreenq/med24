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
                                    <label class="col-form-label col-lg-3 col-sm-12">From</label>
                                    <div class=" col-lg-4 col-md-9 col-sm-12">
                                        <div class="input-group date">
                                            {{ Form::text('from', (isset($from)) ? $from : '', ['id' => 'from', 'readonly', 'class' => 'form-control', 'placeholder' => 'From', 'required']) }}
                                            <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">To</label>
                                    <div class=" col-lg-4 col-md-9 col-sm-12">
                                        <div class="input-group date">
                                            {{ Form::text('to', (isset($to)) ? $to : '', ['id' => 'to', 'readonly', 'class' => 'form-control', 'placeholder' => 'To', 'required']) }}
                                            <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Gym</label>
                                    <div class="col-lg-4 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::select('module_name', $modules, (isset($module_name)) ? $module_name : '', ['id' => 'module_name', 'class' => 'form-control', 'placeholder' => 'Select Module']) }}
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
                        {{ Form::close() }}
                        <!--end::Form-->
                        </div>
                    </div>

                    @if(isset($logs))

                        <div class="kt-portlet__body">

                            <!--begin: Search Form -->
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
                                </div>
                            </div>

                            <!--end: Search Form -->
                        </div>
                        <div class="kt-portlet__body kt-portlet__body--fit">

                            <!--begin: Datatable -->
                            <table class="kt-datatable" id="html_table" width="100%">
                                <thead>
                                <tr>
                                    <th title="Field #1">Log</th>
                                    <th title="Field #1">Module ID</th>
                                    <th title="Field #2">Module Type</th>
                                    <th title="Field #3">User ID</th>
                                    <th title="Field #6">Created At</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->subject_id }}</td>
                                        <td>{{ $log->subject_type }}</td>
                                        <td>{{ \App\User::getUserName($log->causer_id) }}</td>
                                        <td align="right">{{ date('d M Y H:i:s', strtotime($log->created_at)) }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <!--end: Datatable -->
                        </div>

                    @endif

                </div>

                <!--end::Portlet-->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/html-table.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('#gym_id').select2();
        $('#offer_id').select2();
        $('#user_id').select2();
        $('#payment_status').select2();
        $('#card_id').select2();

        $('#from').datetimepicker({
            rtl: KTUtil.isRTL(),
            todayBtn: "linked",
            clearBtn: true,
            todayHighlight: true,
        });

        $('#to').datetimepicker({
            rtl: KTUtil.isRTL(),
            todayBtn: "linked",
            clearBtn: true,
            todayHighlight: true,
        });


    </script>
@endsection
