@extends('admin.layouts.main')

@section('styles')

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
                        {{ $title }}
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
                        </div>
                    </div>
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
                        <th title="Field #1">User</th>
                        <th title="Field #2">Gym</th>
                        <th title="Field #2">Session Started At</th>
                        <th title="Field #2">Session Ended At</th>
                        <th title="Field #3">Total Amount</th>
                        <th title="Field #4">Commission</th>
                        <th title="Field #5">Created At</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($commissions as $commission)
                        <tr>
                            <td><a href="{{ ($commission->session->user) ? route('admin.user.customer.edit', $commission->session->user_id) : '' }}">{{ ($commission->session->user) ? $commission->session->user->first_name : '' }}</a></td>
                            <td>{{ $commission->gym->name }}</td>
                            <td>{{ $commission->session->start_datetime }}</td>
                            <td>{{ $commission->session->end_datetime }}</td>
                            <td>{{ \App\Settings::get_value('currency') . ' ' . $commission->total_amount }}</td>
                            <td>{{ ($commission->commission_amount) ? \App\Settings::get_value('currency') . ' ' . $commission->commission_amount . ' (' . $commission->commission_percent . '%)' : '0' }}</td>
                            <td align="right">{{ date('d M Y H:i:s', strtotime($commission->created_at)) }}</td>
                            <td></td>
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
    <script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/faq-table.js') }}" type="text/javascript"></script>
@endsection
