@extends('admin.layouts.main')

@section('styles')
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-xl-4">

                        <!--begin:: Widgets/Daily Sales-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header kt-margin-b-30">
                                    <h3 class="kt-widget14__title">
                                        Session Count
                                    </h3>
                                    <span class="kt-widget14__desc">
                                        Session count for last 1 year
                                    </span>
                                </div>
                                <div class="kt-widget14__chart" style="height:120px;">
                                    <canvas id="monthly_session_count"></canvas>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Daily Sales-->
                    </div>
                    <div class="col-xl-4">

                        <!--begin:: Widgets/Profit Share-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header">
                                    <h3 class="kt-widget14__title">
                                        Session Count By Gym
                                    </h3>
                                    <span class="kt-widget14__desc">
                                        Frequently used Gyms
                                    </span>
                                </div>
                                <div class="kt-widget14__content">
                                    <div class="kt-widget14__chart">
                                        <div class="kt-widget14__stat">{{ $top_gyms['total_count'] }}</div>
                                        <canvas id="kt_chart_profit_share" style="height: 140px; width: 140px;"></canvas>
                                    </div>
                                    <div class="kt-widget14__legends">
                                        @foreach($top_gyms['gym_name'] as $index => $gym_name)
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-{{ $top_gyms['colours'][$index] }}"></span>
                                                <span class="kt-widget14__stats"><strong>{{ \App\Settings::get_value('currency') }} {{ number_format($top_gyms['amount'][$index] , 0, ".", ",") }}</strong> - {{ $gym_name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Profit Share-->
                    </div>
                    <div class="col-xl-4">

                        <!--begin:: Widgets/Revenue Change-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header">
                                    <h3 class="kt-widget14__title">
                                        Time Spend
                                    </h3>
                                    <span class="kt-widget14__desc">
                                        Time spend by gym
                                    </span>
                                </div>
                                <div class="kt-widget14__content">
                                    <div class="kt-widget14__chart">
                                        <div id="kt_chart_revenue_change" style="height: 150px; width: 150px;"></div>
                                    </div>
                                    <div class="kt-widget14__legends">
                                        @foreach($time_spend_minutes as $index => $tsm)
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-{{ $top_gyms['colours'][$index] }}"></span>
                                            <span class="kt-widget14__stats">{{ $tsm['total_time'] . ' mins - ' . $tsm['gym_name'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Revenue Change-->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">

                    <!--begin:: Widgets/New Users-->
                    <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Session Count By Day
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_widget4_tab1_content">
                                    <div class="kt-widget4">
                                        @foreach($session_count_by_day as $key => $row)
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <p class="kt-widget4__username">
                                                        {{ $key }}
                                                    </p>
                                                </div>
                                                <p id="scan_out" data-id="ads" class="btn btn-sm btn-label-brand btn-bold">{{ $row }}</p>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end:: Widgets/New Users-->
                </div>
                <div class="col-xl-8">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                User Sessions
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <!--begin: Search Form -->
                        <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                            <div class="kt-form__group kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Select Month:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    {{ Form::month('month_2', isset($month_2) ? $month_2 : null, ['class' => 'form-control', 'id' => 'month_2', 'placeholder' => 'Select Month']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Search Form -->
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit" width="100%">

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                        <table class="table table-bordered " width="100%">
                            <thead>
                            <tr>
                                <th>Gym</th>
                                <th>Time Spent</th>
                                <th>No of Visits</th>
                                <th>Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gym_ids as $gym_id)
                                <tr>
                                    <td>{{ $gym_id->gym->name }}</td>
                                    <td>{{ $user->sessions->where('gym_id', $gym_id->gym_id)->sum('time_spend_minutes') }}</td>
                                    <td>{{ $user->sessions->where('gym_id', $gym_id->gym_id)->count('id') }}</td>
                                    <td>{{ $user->sessions->where('gym_id', $gym_id->gym_id)->sum('total_amount') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>

                        <!--end: Datatable -->
                    </div>
                </div>
            </div>

            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        User Sessions
                    </h3>
                </div>
            </div>
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
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">
                                            <label>Select Month:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            {{ Form::month('month', isset($month) ? $month : null, ['class' => 'form-control', 'id' => 'month']) }}
                                        </div>
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

                <!--end: Search Form -->
            </div>
            <div class="kt-portlet__body kt-portlet__body--fit">

                <!--begin: Datatable -->
                <table class="kt-datatable" id="html_table" width="100%">
                    <thead>
                    <tr>
                        <th title="Field #1">Gym</th>
                        <th title="Field #2">Offer / Voucher</th>
                        <th title="Field #3">Session Start</th>
                        <th title="Field #3">Session End</th>
                        <th title="Field #3">Time Spend</th>
                        <th title="Field #3">Initial Amount</th>
                        <th title="Field #3">Additional Amount</th>
                        <th title="Field #3">Discount</th>
                        <th title="Field #3">Total Amount</th>
                        <th title="Field #3"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->gym->name }}</td>
                            <td>
                            @if($session->offer)
                                <a href="{{ route('admin.offers.edit', $session->offer_id) }}">{{ $session->offer->name }}</a>
                            @elseif($session->voucher)
                                <a href="{{ route('admin.vouchers.edit', $session->voucher_id) }}">{{ $session->voucher->code }}</a>
                            @endif
                            </td>
                            <td>{{ date('d M, Y H:i:s', strtotime($session->start_datetime)) }}</td>
                            <td>{{ date('d M, Y H:i:s', strtotime($session->end_datetime)) }}</td>
                            <td>{{ $session->time_spend_minutes }}</td>
                            <td>{{ $session->initial_amount }}</td>
                            <td>{{ $session->additional_amount }}</td>
                            <td>{{ $session->discount }}</td>
                            <td>{{ $session->total_amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/user-table.js') }}" type="text/javascript"></script>
{{--    <script src="{{ asset('public/assets/js/demo1/pages/dashboard.js') }}" type="text/javascript"></script>--}}

    <script>

        var dailySales = function () {
            var chartContainer = KTUtil.getByID('monthly_session_count');

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
                        {{ implode(',', $monthly_sessions_count) }}
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

        var profitShare = function() {
            if (!KTUtil.getByID('kt_chart_profit_share')) {
                return;
            }

            var randomScalingFactor = function() {
                return Math.round(Math.random() * 100);
            };

            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [
                            {{ implode(',', $top_gyms['counts']) }}
                        ],
                        backgroundColor: [
                            KTApp.getStateColor('success'),
                            KTApp.getStateColor('danger'),
                            KTApp.getStateColor('brand'),
                            KTApp.getStateColor('warning'),
                        ]
                    }],
                    labels: [
                        @foreach($top_gyms['gym_name'] as $gym_name)
                        "{{ $gym_name }}",
                        @endforeach
                    ]
                },
                options: {
                    cutoutPercentage: 75,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Technology'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    tooltips: {
                        enabled: true,
                        intersect: false,
                        mode: 'nearest',
                        bodySpacing: 5,
                        yPadding: 10,
                        xPadding: 10,
                        caretPadding: 0,
                        displayColors: false,
                        backgroundColor: KTApp.getStateColor('brand'),
                        titleFontColor: '#ffffff',
                        cornerRadius: 4,
                        footerSpacing: 0,
                        titleSpacing: 0
                    }
                }
            };

            var ctx = KTUtil.getByID('kt_chart_profit_share').getContext('2d');
            var myDoughnut = new Chart(ctx, config);
        }

        var revenueChange = function() {
            if ($('#kt_chart_revenue_change').length == 0) {
                return;
            }

            Morris.Donut({
                element: 'kt_chart_revenue_change',
                data: [
                    @foreach($time_spend_minutes as $tsm)
                    {
                        label: "{{ $tsm['gym_name'] }}",
                        value: "{{ $tsm['total_time'] }}"
                    },
                    @endforeach
                ],
                colors: [
                    KTApp.getStateColor('success'),
                    KTApp.getStateColor('danger'),
                    KTApp.getStateColor('brand'),
                    KTApp.getStateColor('warning'),
                ],
            });
        }

        $(function () {
            dailySales();
            profitShare();
            revenueChange();
        });

        $('#month').change(function(){
            var month = $(this).val();
            var month_2 = $('#month_2').val();
            window.location.replace("{{ route('admin.user.customer.detail', $user->id) }}?month="+month+"&month_2="+month_2, 'refresh');
        });

        $('#month_2').change(function(){
            var month = $('#month').val();
            var month_2 = $(this).val();
            window.location.replace("{{ route('admin.user.customer.detail', $user->id) }}?month="+month+"&month_2="+month_2, 'refresh');
        });
    </script>
@endsection
