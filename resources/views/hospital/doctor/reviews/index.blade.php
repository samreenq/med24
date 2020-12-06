@extends('admin.layouts.main')

@section('styles')
    <style type="text/css">
        .yellow {
            color: #0044cc;
        }

        .white {
            color: white;
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
                            <th title="Field #1">ID</th>
                            <th title="Field #2">Doctor</th>
                            <th title="Field #4">Patient</th>
                            <th title="Field #5">Rating</th>
                            <th title="Field #6">Review</th>
                            <th title="Field #7">Created At</th>
                            <th title="Field #8"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td align="right">{{ ($review->doctor->first_name ?? "").' '.($review->doctor->last_name ?? "") }}</td>
                            <td align="right">{{ ($review->patient->first_name ?? "").' '.($review->patient->last_name ?? "") }}</td>
                            <td align="right">
                                @if($review->rating == 5)
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                @elseif($review->rating == 4)
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                @elseif($review->rating == 3)
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                @elseif($review->rating == 2)
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star yellow"></i>
                                <i class="fa fa-star yellow"></i>
                                @elseif($review->rating == 1)
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star yellow"></i>
                                @else
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                <i class="fa fa-star white"></i>
                                @endif
                            </td>
                            <td align="right">
                            	<a href="javascript:;" onclick="viewReview('{{ $review->review }}', '{{ ($review->doctor->first_name ?? "asd").' '.($review->doctor->last_name ?? "asd") }}', '{{ ($review->patient->first_name ?? "").' '.($review->patient->last_name ?? "") }}')" id="viewReview" data-id="{{ $review->id }}" title="View Review" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-eye"></i>
                                </a>
                            </td>
                            <td align="right">{{ date('d M Y H:i:s', strtotime($review->created_at)) }}</td>
                            <td align="right"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewReviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog full_modal-dialog">
            <div class="modal-content full_modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                        	<div class="col-lg-12 col-md-12 col-sm-12">
                            	<textarea id="reviewTextArea" class="form-control" rows="10" readonly></textarea>
                        	</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/user-table.js') }}" type="text/javascript"></script>

<script type="text/javascript">
	function viewReview(review, doctorName, patientName) {
        $('#myModalLabel').text('Review for '+doctorName+' from '+patientName);
        $('#reviewTextArea').val(review);
        $('#viewReviewModal').modal('show');
    }
</script>
@endsection