@extends('hospital.layouts.main')

@section('content')
        
	<style>
        .dn{
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
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Doctors</h3>
                                    <span class="kt-widget1__desc">Total Number of Doctors</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{ $totalDoctors }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-4">
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Appointments</h3>
                                    <span class="kt-widget1__desc">Total Number of Appointments</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{ $totalAppointments }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection