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
                            {{ Form::open(['id' => 'form-sessions']) }}
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">From</label>
                                        <div class=" col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group date">
                                                {{ Form::text('from', (isset($from)) ? $from : '', ['id' => 'from', 'readonly', 'class' => 'form-control', 'placeholder' => 'From']) }}
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
                                                {{ Form::text('to', (isset($to)) ? $to : '', ['id' => 'to', 'readonly', 'class' => 'form-control', 'placeholder' => 'To']) }}
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
                                                {{ Form::select('gym_id', $listings, (isset($gym_id)) ? $gym_id : '', ['id' => 'gym_id', 'class' => 'form-control', 'placeholder' => 'Select Gym']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Offers</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::select('offer_id', $offers, (isset($offer_id)) ? $offer_id : '', ['id' => 'offer_id', 'class' => 'form-control', 'placeholder' => 'Select Offer']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Users</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::select('user_id', $users, (isset($user_id)) ? $user_id : '', ['id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'Select User']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Payment Status</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::select('payment_status', $list_payment_status, (isset($payment_status)) ? $payment_status : '', ['id' => 'payment_status', 'class' => 'form-control', 'placeholder' => 'Select Payment Status']) }}
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

                    @if(isset($sessions))


                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon">
                                <i class="kt-font-brand flaticon2-line-chart"></i>
                            </span>
                            <h3 class="kt-portlet__head-title">
                                Manage Notifications
                                <small></small>
                            </h3>
                        </div>
                        @can('export sessions')
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
                                                    <a id="export_sessions" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                                        <span class="kt-nav__link-text">Excel</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>

                    <script>
                        $('#export_sessions').click(function(){
                            $('#form-sessions').attr('action', '{{ route('admin.sessions.export_sessions') }}');
                            $('#form-sessions').submit();
                        });
                    </script>

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
                                    <th title="Field #1">Session ID</th>
                                    <th title="Field #1">Gym</th>
                                    <th title="Field #2">User</th>
                                    <th title="Field #3">Spend Minutes</th>
                                    <th title="Field #4">Total Amount</th>
                                    <th title="Field #5">Session Status</th>
                                    <th title="Field #5">Session Start Time</th>
                                    <th title="Field #5">Session End Time</th>
                                    <th title="Field #6">Created At</th>
                                    <th title="Field #8">Actions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ ($session->gym) ? $session->gym->name : '' }}</td>
                                    <td>{{ ($session->user) ? $session->user->first_name : '' }}</td>
                                    <td>{{ $session->time_spend_minutes }}</td>
                                    <td>{{ $session->total_amount }}</td>
                                    <td align="right">{{ $session->status }}</td>
                                    <td align="right">{{ $session->start_datetime }}</td>
                                    <td align="right">{{ $session->end_datetime }}</td>
                                    <td align="right">{{ date('d M Y H:i:s', strtotime($session->created_at)) }}</td>
                                    <td align="right">
                                        <a href="{{ route('admin.sessions.detail', $session->id) }}" title="View Session" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                            <i class="la la-eye"></i>
                                        </a>
                                        @can('scanout')
                                            @if($session->status == 'on-going')
                                            <a id="scan_out" data-id="{{ $session->id }}" title="Scan Out" class="btn btn-icon-md">
                                                <i class="la la-close"></i> Scan Out
                                            </a>
                                            @endif
                                        @endcan
                                    </td>
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

    <div class="modal fade" id="cards_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Card for Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="la la-remove"></span>
                    </button>
                </div>
                {{ Form::open(['url' => route('admin.sessions.scan_out')]) }}
                    {{ Form::hidden('session_id', null, ['id' => 'modal_session_id']) }}
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Select Card</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <div class="input-group">
                                    {{ Form::select('card_id', [], null, ['id' => 'card_id', 'class' => 'form-control', 'placeholder' => 'Select Card']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </div>
                {{ Form::close() }}
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

    // $('table').on('click','.delete_vendor',function(e){
    //     var _this = $(this);
    //     var url = _this.attr('data-url');

    //     if (url == "") {
    //         return;
    //     }

    //     swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes, delete it!',
    //         cancelButtonText: 'No, cancel!',
    //         reverseButtons: true
    //     }).then(function (result) {
    //         if (result.value) {
    //             setTimeout(async function () {
    //                 _this.attr('href', url);
    //                 url = "";
    //                 _this.attr('data-url', url);
    //                 _this[0].click();
    //             }, 1000);
    //         } else if (result.dismiss === 'cancel') {
    //             swal.fire(
    //                 'Cancelled',
    //                 'Your record is safe :)',
    //                 'error'
    //             )
    //         }
    //     });
    // });

    $('table').on('click','#scan_out',function(e){
        var session_id = $(this).attr('data-id');
        $('#card_id').html('');
        $('#card_id').append($("<option></option>").val('').html('Select User Card'));
        $.get("{{ route('admin.sessions.get_cards') }}", {session_id: session_id}, function(res) {
            if(res) {

                if(res['status'] == 1) {
                    var data = res['data'];
                    // data = data;
                    for(var i = 0; i < data.length; i++)
                    {
                        $('#card_id').append($("<option></option>").val(data[i]['id']).html(data[i]['customer_email']));
                    }

                    $('#modal_session_id').val(session_id);
                    $('#cards_modal').modal('show');
                }
            }
        });

    });


</script>
@endsection
