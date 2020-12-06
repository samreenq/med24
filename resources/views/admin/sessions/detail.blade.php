@extends('admin.layouts.main')

@section('styles')
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Portlet-->
        <div class="row">
            <div class="col-xl-6 col-lg-6">

                <!--begin:: Widgets/Daily Sales-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
                            <h3 class="kt-widget14__title">
                                User Details
                            </h3>
                        </div>
                        <div class="kt-widget14__chart">
                            <div class="form-group row">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">User Name </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->user->first_name . ' ' . $session->user->last_name }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Email </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->user->email }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Phone </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->user->phone }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Gender </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->user->gender }}</label>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Daily Sales-->
            </div>
            <div class="col-xl-6 col-lg-6">

                <!--begin:: Widgets/Daily Sales-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
                            <h3 class="kt-widget14__title">
                                Session Details
                            </h3>
                        </div>
                        <div class="kt-widget14__chart">
                            <div class="form-group row">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Gym Name </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->gym->name }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Total Time Spend </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->time_spend_minutes }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Session Status </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->status }}</br>
                                            @can('scanout')
                                                @if($session->status == 'on-going')
                                                <a id="scan_out" data-id="{{ $session->id }}" title="Scan Out" class="btn btn-icon-md">
                                                    <i class="la la-close"></i> Scan Out
                                                </a>
                                                @endif
                                            @endcan
                                            </label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Offer </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ ($session->offer) ? $session->offer->name . ' (' . $session->offer->discount . $session->offer->discount_unit . ')' : '' }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Voucher </label>
                                        </th>
                                        <th>
                                            @if($session->voucher && $session->voucher->discount_unit == 'free_trainings')
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->voucher->name . ' (' . ($session->voucher->count - $session->voucher->usage_count) . ' Free Trainings left out of ' . $session->voucher->count . ')' }}</label>
                                            @else
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ ($session->voucher) ? $session->voucher->name . ' (' . $session->voucher->discount . $session->voucher->discount_unit . ')' : '' }}</label>
                                            @endif
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Daily Sales-->
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6">

                <!--begin:: Widgets/Daily Sales-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
                            <h3 class="kt-widget14__title">
                                User History
                            </h3>
                            <span class="kt-widget14__desc">
                                Last 5 Sessions
                            </span>
                        </div>
                        <div class="kt-widget14__chart">
                            <div class="form-group row">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Gym Name</th>
                                            <th>Spend Minutes</th>
                                            <th>Total Amount</th>
                                            <th>Session Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_history as $history)
                                        <tr>
                                            <td>{{ $history->gym->name }}</td>
                                            <td>{{ $history->time_spend_minutes }}</td>
                                            <td>{{ $history->total_amount }}</td>
                                            <td>{{ date('d M Y', strtotime($history->created_at)) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Daily Sales-->
            </div>
            <div class="col-xl-6 col-lg-6">

                <!--begin:: Widgets/Daily Sales-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
                            <h3 class="kt-widget14__title">
                                Session Payment Details
                            </h3>
                        </div>
                        <div class="kt-widget14__chart">
                            <div class="form-group row">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Payment Status </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ ($session->payments) ? $session->payments->status : '' }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Initial Amount </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->initial_amount }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Additional Amount </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->additional_amount }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Sub Total </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->additional_amount + $session->initial_amount }}</label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">Discount </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ $session->discount }}</br>
                                            </label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">VAT </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12">{{ number_format($session->vat, 2) }}</br>
                                            </label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12"><strong>Total Amount</strong> </label>
                                        </th>
                                        <th>
                                            <label class="col-form-label col-lg-12 col-sm-12"><strong>{{ $session->total_amount }}</strong></label>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Daily Sales-->
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
</script>
@endsection
