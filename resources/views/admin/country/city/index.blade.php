@extends('admin.layouts.main')

@section('styles')

@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon2-line-chart"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Manage Cities of {{ $country->name }}
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
                            &nbsp;
                            @can('create city')
                            <a href="javascript:;" id="create_city" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                                Add New City
                            </a>
                            @endcan
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
                            <th title="Field #1">City ID</th>
                            <th title="Field #2">Name</th>
                            <th title="Field #4">Country</th>
                            <th title="Field #5">Created At</th>
                            <th title="Field #6">Updated At</th>
                            <th title="Field #7">Actions</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $country->name }}</td>
                            <td align="right">{{ date('d M Y H:i:s', strtotime($city->created_at)) }}</td>
                            <td align="right">{{ date('d M Y H:i:s', strtotime($city->updated_at)) }}</td>
                            <td align="right">
                                @can('edit city')
                                <a href="javascript:;" onclick="editCity({{ $city->id }}, '{{ $city->name }}')" id="edit_city" data-id="{{ $city->id }}" title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-edit"></i>
                                </a>
                                @endcan
                                @can('delete city')
                                <a href="{{ route('admin.city.destroy', [$country->code, $city->id]) }}" title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                    <i class="la la-trash"></i>
                                </a>
                                @endcan
                            </td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEditCity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog full_modal-dialog">
            <div class="modal-content full_modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Save City</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                {{ Form::open(['url' => route('admin.city.save', $country_id)]) }}
                <div class="modal-body">
                    <div class="alert-text">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
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
                    {{ Form::hidden('cityId', '', ['id' => 'city_id']) }}
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">City Name *</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <div class="input-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter City Name', 'id' => 'city_name', 'required']) }}
                                </div>
                                <span class="form-text text-muted">Please enter city name.</span>
                            </div>
                            {{ Form::hidden('country_id', "$country->code") }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-brand" id="submit_form">Submit</button>
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/city-table.js') }}" type="text/javascript"></script>

<script>
    @if ($errors->any())
    $('#addEditCity').modal('show');
    @endif

    $('#create_city').click(function(){
        $('#addEditCity').modal('show');
    });

    function editCity(city_id, city_name) {
        $('#city_id').val(city_id);
        $('#city_name').val(city_name);
        $('#addEditCity').modal('show');
    }

    $('#edit_city').on('click', 'a', function(){
        var city_id = $(this).attr('data-id');
        var city_name = $('#name'+city_id).text();
        $('#addEditCity').modal('show');
    });
</script>
@endsection
