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
                            {{ Form::model($content) }}
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Title *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('search_title', null, ['placeholder' => 'Search Title', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search title.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Description 1 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('search_description', null, ['class' => 'form-control', 'placeholder' => 'Search Description 1', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search description 1.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Description 2 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('search_description2', null, ['class' => 'form-control', 'placeholder' => 'Search Description 2', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search description 2.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Title 3*</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('search_title3', null, ['placeholder' => 'Search Title 3', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search title3.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Description 3 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('search_description3', null, ['class' => 'form-control', 'placeholder' => 'Search Description 3', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search description 3.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Title 4 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('search_title4', null, ['placeholder' => 'Search Title 4', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search title 4.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Search Description 4 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('search_description4', null, ['class' => 'form-control', 'placeholder' => 'Search Description 4', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter search description 4.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Logo Title *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('logo_title', null, ['placeholder' => 'Logo Title', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter logo title.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Logo Description *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('logo_description', null, ['class' => 'form-control', 'placeholder' => 'Logo Description', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter logo description.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Download Title *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::text('download_title', null, ['placeholder' => 'Download Title', 'class' => 'form-control', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter download title.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Download Description *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <div class="input-group">
                                                {{ Form::textarea('download_description', null, ['class' => 'form-control', 'placeholder' => 'Download Description', 'required']) }}
                                            </div>
                                            <span class="form-text text-muted">Please enter download description.</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-9 ml-lg-auto">
                                                <button type="submit" class="btn btn-brand">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{ Form::close() }}
                            <!--end::Form-->
                        </div>
                    </div>

                </div>

                <!--end::Portlet-->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="./assets/js/demo1/pages/crud/forms/validation/form-controls.js" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard-all/ckeditor.js"></script>

<script type="text/javascript">
    CKEDITOR.replace('content', {

    });
</script>
@endsection
