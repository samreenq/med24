@extends('admin.layouts.main')

@section('styles')
@endsection

@section('content')
        
	<style>
    .dn
    {
        display: none !important;
    }
    
    .kt-header--fixed.kt-subheader--fixed.kt-subheader--enabled .kt-wrapper {
        padding-top: 90px;
    }
    </style>

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-md-12 col-lg-12 col-xl-4">

                        <!--begin:: Widgets/Stats2-1 -->
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Doctors</h3>
                                    <span class="kt-widget1__desc">Total Number of Active Doctors</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{ $total_active_doctors }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Specialities</h3>
                                    <span class="kt-widget1__desc">Total No of Active Specialities</span>
                                </div>
                                <span class="kt-widget1__number kt-font-danger">{{ $total_active_specialities }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Certifications</h3>
                                    <span class="kt-widget1__desc">Total No of Active Certifications</span>
                                </div>
                                <span class="kt-widget1__number kt-font-success">{{ $total_active_certifications }}</span>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-1 -->
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-4 dn">

                        <!--begin:: Widgets/Stats2-2 -->
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Total Today's Sessions</h3>
                                    <span class="kt-widget1__desc">Completed Sessions today</span>
                                </div>
                                <span class="kt-widget1__number kt-font-success">{{ $total_todays_sessions }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Total Revenue</h3>
                                    <span class="kt-widget1__desc">Total Revenue for this month</span>
                                </div>
                                <span class="kt-widget1__number kt-font-primary">{{ \App\Settings::get_value('currency') . ' ' . number_format($total_monthly_revenue, 2) }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Total Active Sessions</h3>
                                    <span class="kt-widget1__desc">Total Number of Active Sessions</span>
                                </div>
                                <span class="kt-widget1__number kt-font-warning">{{ $total_active_sessions }}</span>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-2 -->
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-4 dn">

                        <!--begin:: Widgets/Stats2-3 -->
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Total Active Offers</h3>
                                    <span class="kt-widget1__desc">Total number of active offers</span>
                                </div>
                                <span class="kt-widget1__number kt-font-success">{{ $total_active_offers }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Customer's Trained This Month</h3>
                                    <span class="kt-widget1__desc">Total Customers Trained this month</span>
                                </div>
                                <span class="kt-widget1__number kt-font-danger">{{ $total_customers_trained }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Total Hours</h3>
                                    <span class="kt-widget1__desc">Total Number of Hours Spend In Gym</span>
                                </div>
                                <span class="kt-widget1__number kt-font-primary">{{ $total_monthly_hours }}</span>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-3 -->
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet dn">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-xl-6">

                        <!--begin:: Widgets/Daily Sales-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header kt-margin-b-30">
                                    <h3 class="kt-widget14__title">
                                        New Users
                                    </h3>
                                    <span class="kt-widget14__desc">
                                        New User Registered for each month
                                    </span>
                                </div>
                                <div class="kt-widget14__chart" style="height:120px;">
                                    <canvas id="kt_revenue"></canvas>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Daily Sales-->
                    </div>
                    <div class="col-xl-6">

                        <!--begin:: Widgets/Daily Sales-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header kt-margin-b-30">
                                    <h3 class="kt-widget14__title">
                                        Annual Revenue
                                    </h3>
                                    <span class="kt-widget14__desc">
                                        Annual Revenue for each month
                                    </span>
                                </div>
                                <div class="kt-widget14__chart" style="height:120px;">
                                    <canvas id="kt_count"></canvas>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Daily Sales-->
                    </div>
                </div>
            </div>
        </div>
        <div class="row dn">
            <div class="col-xl-4">

                <!--begin:: Widgets/New Users-->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Recent Sessions
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_widget4_tab1_content" role="tab">
                                        Active
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget4_tab2_content" role="tab">
                                        Unpaid
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget4_tab1_content">
                                <div class="kt-widget4">
                                    @foreach($active_sessions as $active)
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <a href="{{ (\Auth::user()->can('view sessions')) ? route('admin.sessions.detail', $active->id) : '' }}" class="kt-widget4__username">
                                                {{ $active->user->first_name . ' ' . $active->user->last_name }}
                                            </a>
                                            <p class="kt-widget4__text">
                                                {{ $active->gym->name }}
                                            </p>
                                        </div>
                                        @can('scanout')
                                        <a id="scan_out" data-id="{{ $active->id }}" class="btn btn-sm btn-label-brand btn-bold">Scan Out</a>
                                        @endcan
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_widget4_tab2_content">
                                <div class="kt-widget4">
                                    @foreach($unpaid_sessions as $unpaid)
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <a href="{{ (\Auth::user()->can('view sessions')) ? route('admin.sessions.detail', $unpaid->id) : '' }}" class="kt-widget4__username">
                                                {{ $unpaid->user->first_name . ' ' . $unpaid->user->last_name }}
                                            </a>
                                            <p class="kt-widget4__text">
                                                {{ $unpaid->gym->name }}
                                            </p>
                                        </div>
                                        <a class="btn btn-sm btn-label-brand btn-bold">AED {{ $unpaid->total_amount }}</a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/New Users-->
            </div>
            <div class="col-xl-4">

                <!--begin:: Widgets/Sales States-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Today's Sessions
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget6">
                            <div class="kt-widget6__head">
                                <div class="kt-widget6__item">
                                    <span>Gym Name</span>
                                    <span>Count</span>
                                    <span>Amount</span>
                                </div>
                            </div>
                            <div class="kt-widget6__body">
                                @foreach($todays_sessions as $session)
                                <div class="kt-widget6__item">
                                    <span>{{ $session['gym_name'] }}</span>
                                    <span>{{ $session['count'] }}</span>
                                    <span class="kt-font-success kt-font-bold">AED {{ $session['total'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Sales States-->
            </div>
            <div class="col-xl-4">

                <!--Begin::Portlet-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Recent Activities
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <!--Begin::Timeline 3 -->
                        <div class="kt-timeline-v2">
                            <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
                                @foreach($recent_activities as $activity)
                                <div class="kt-timeline-v2__item">
                                    <span class="kt-timeline-v2__item-time">{{ date('H:i', strtotime($activity->created_at)) }}</span>
                                    <div class="kt-timeline-v2__item-cricle">
                                        <i class="fa fa-genderless kt-font-danger"></i>
                                    </div>
                                    <div class="kt-timeline-v2__item-text  kt-padding-top-5">
                                        {{ $activity->description }}<br>
                                        @if($user = \App\User::permission('admin')->find($activity->causer_id))
                                            <a href="{{ route('admin.user.admin.edit', $user->id) }}">{{ $user->first_name }}</a>
                                        @elseif($user = \App\User::permission('gym owner')->find($activity->causer_id))
                                            <a href="{{ route('admin.user.gym_owner.edit', $user->id) }}">{{ $user->first_name }}</a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!--End::Timeline 3 -->
                    </div>
                </div>

                <!--End::Portlet-->
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
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
{{--<script src="{{ asset('public/assets/js/demo1/pages/dashboard.js') }}" type="text/javascript"></script>--}}

<script type="text/javascript">
    $('#scan_out').click(function(){
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

    var revenue = function () {
        var chartContainer = KTUtil.getByID('kt_revenue');

        if (!chartContainer) {
            return;
        }
        var chartData = {
            labels: [
                @foreach($month_display as $month)
                    "{{ $month }}",
                @endforeach
            ],
            datasets: [{
                //label: 'Dataset 1',
                backgroundColor: KTApp.getStateColor('success'),
                data: [
                    {{ implode(',', $user_count) }}
                ],
            }]
        };

        var chart = new Chart(chartContainer, {
            type: 'bar',
            data: chartData,
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    intersect: false,
                    mode: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                barRadius: 4,
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        stacked: true
                    }],
                    yAxes: [{
                        display: false,
                        stacked: true,
                        gridLines: false
                    }]
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                }
            }
        });
    }

    var count = function () {
        var chartContainer = KTUtil.getByID('kt_count');

        if (!chartContainer) {
            return;
        }
        var chartData = {
            labels: [
                @foreach($month_display as $month)
                    "{{ $month }}",
                @endforeach
            ],
            {{--labels: ["{{ $month_display[0] }}", "{{ $month_display[1] }}", "{{ $month_display[2] }}", "{{ $month_display[3] }}", "{{ $month_display[4] }}", "{{ $month_display[5] }}", "{{ $month_display[6] }}", "{{ $month_display[7] }}", "{{ $month_display[8] }}", "{{ $month_display[9] }}", "{{ $month_display[10] }}", "{{ $month_display[11] }}",],--}}
            datasets: [{
                //label: 'Dataset 1',
                backgroundColor: KTApp.getStateColor('success'),
                data: [
                    {{ implode(',', $revenue) }}
                    // 15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20
                ]
            }]
        };

        var chart = new Chart(chartContainer, {
            type: 'bar',
            data: chartData,
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    intersect: false,
                    mode: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                barRadius: 4,
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        stacked: true
                    }],
                    yAxes: [{
                        display: false,
                        stacked: true,
                        gridLines: false
                    }]
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                }
            }
        });
    }

    $(function () {
        revenue();
        count();
    });
</script>

@endsection
