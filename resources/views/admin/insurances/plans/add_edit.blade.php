@extends('admin.layouts.main')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/croppie/croppie.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/demo1/pages/upload.css') }}">
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(count( $errors) > 0)
                <div class="alert alert-light alert-elevate" role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i>
                            <div class="alert-text">{{ $error }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ ucwords(str_replace('_', ' ', $title)) }}
                            </h3>
                        </div>
                    </div>
                    {{ Form::model($record, ['enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">{{ ucwords(str_replace('_', ' ', $title)) }} *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::text('name', null, ['placeholder' => '', 'class' => 'form-control', 'required']) }}
                                        </div>
                                        <span class="form-text text-muted">Please enter {{ ucwords(str_replace('_', ' ', $title)) }}.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Status *</label>
                                    <div class="col-lg-4 col-md-9 col-sm-12">
                                        <div class="input-group">
                                            {{ Form::checkbox('status', null, null, ['data-switch' => 'true', ($record->status) ? 'checked' : '', 'data-on-text' => 'Enabled', 'data-handle-width' => '70', 'data-off-text' => 'Disabled', 'data-on-color' => 'brand']) }}
                                        </div>
                                        <span class="form-text text-muted">Please select active status.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-lg-9 ml-lg-auto">
                                            <button type="submit" class="btn btn-brand btn-submit">Submit</button>
                                            <button type="reset" class="btn btn-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/validation/form-controls.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo1/pages/components/croppie/croppie.min.js') }}" type="text/javascript"></script>
{{--<script src="{{ asset('public/assets/js/demo1/pages/upload.js') }}" type="text/javascript"></script>--}}

<script type="text/javascript">
</script>
@endsection