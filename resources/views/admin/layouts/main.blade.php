<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

	<!-- begin::Head -->
	<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		<base href="../../../">

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>{{ (isset($title)) ? ucwords($title) : "Home" }} | {{ env('APP_NAME') }}</title>
		<meta name="description" content="Page with empty content">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="{{ asset('public/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="{{ asset('public/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<link href="{{ asset('public/assets/vendors/general/tether/dist/css/tether.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/nouislider/distribute/nouislider.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/animate.css/animate.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/sweetalert2/dist/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/socicon/css/socicon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/custom/vendors/flaticon/flaticon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/custom/vendors/flaticon2/flaticon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{ asset('public/assets/css/demo1/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{ asset('public/assets/css/demo1/skins/header/base/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/css/demo1/skins/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/css/demo1/skins/brand/dark.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/assets/css/demo1/skins/aside/dark.css') }}" rel="stylesheet" type="text/css" />


		<script src="{{ asset('public/assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{ asset('public/assets/logo.png') }}" />

		@yield('styles')

        <style>
		.dn
		{
			display: none !important;
		}

		.kt-header--fixed.kt-subheader--fixed.kt-subheader--enabled .kt-wrapper {
			padding-top: 90px;
		}
		</style>

	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="demo1/index.html">
					<img alt="Logo" src="{{ asset('public/assets/logo.png') }}" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->

		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

					<!-- begin:: Aside -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<div class="kt-aside__brand-logo">
							<a href="{{ route('admin.home') }}">
								<img alt="Logo" src="{{ asset('public/assets/logo.png') }}" width="50px"/>
							</a>
						</div>
						<div class="kt-aside__brand-tools">
							<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
								<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon id="Shape" points="0 0 24 0 24 24 0 24" />
											<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
											<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
										</g>
									</svg></span>
								<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon id="Shape" points="0 0 24 0 24 24 0 24" />
											<path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" />
											<path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
										</g>
									</svg></span>
							</button>

							<!--
			<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
			-->
						</div>
					</div>

					<!-- end:: Aside -->

					<!-- begin:: Aside Menu -->
					<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
							<ul class="kt-menu__nav ">
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Home</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
								<li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'dashboard') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.home') }}" class="kt-menu__link"><i class="kt-menu__link-icon flaticon-dashboard"></i><span class="kt-menu__link-text">Dashboard</span></a></li>
                                <li class="dn kt-menu__section">
                                    <h4 class="kt-menu__section-text">Gym Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                @can('view sessions')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'sessions') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-stopwatch"></i><span class="kt-menu__link-text">Manage Sessions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">View Sessions</span></span></li>
                                                @can('view sessions')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.sessions.get_sessions') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Sessions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view amenities')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'amenities') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-apps"></i><span class="kt-menu__link-text">Manage Amenities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Amenities</span></span></li>
                                                @can('view amenities')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.amenities.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Amenities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create amenities')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.amenities.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Amenities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view gyms')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'gym') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-home"></i><span class="kt-menu__link-text">Gym Management</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Listings</span></span></li>
                                                @can('view gyms')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.gym.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Gym's</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create gym')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.gym.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Add New Gym</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view commissions')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.commissions.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Commissions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                <li class="dn kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Customer Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                @can('view customers')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'customer') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Customers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Users</span></span></li>
                                                @can('create customer')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.customer.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Customer</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view customers')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.customer.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Customers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view blocked customers')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.customer.block') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Blocked Customers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Hospital Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                           		@can('view hospitals')
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'hospital') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Hospitals</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Hospitals</span></span></li>
                                            @can('view hospitals')
                                                <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.hospital.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Hospitals</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create hospital')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.hospital.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Hospital</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'speciality_hospital') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Hospital Specialities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Hospital Specialities</span></span></li>
                                            @can('view specialities_hospitals')
                                                <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.speciality_hospital.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Hospital Specialities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create speciality_hospital')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.speciality_hospital.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Hospital Speciality</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'certification_hospital') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Hospital Certifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Hospital Certifications</span></span></li>
                                            @can('view certifications_hospitals')
                                                <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.certification_hospital.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Hospital Certifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create certification_hospital')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.certification_hospital.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Hospital Certification</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'award_hospital') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Hospital Awards</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Hospital Awards</span></span></li>
                                            @can('view awards_hospitals')
                                                <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.award_hospital.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Hospital Awards</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create award_hospital')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.award_hospital.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Hospital Award</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                @endcan
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Doctor Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                @can('view doctors')
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'doctor') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Doctors</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Doctors</span></span></li>
                                            @can('view doctors')
                                                <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">All Doctors</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('view doctors')
                                                <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.index', ['isApproved' => 0]) }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Unapproved Doctors</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('view doctors')
                                                <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.index', ['isApproved' => 1]) }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Approved Doctors</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create doctor')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Doctor</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'claim profile') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.doctor.claimProfiles.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Claim Profiles</span></a></li>
                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'review') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.doctors.reviews.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Doctors Reviews</span></a></li>
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'speciality') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Specialities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Specialities</span></span></li>
                                                @can('view speciality')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.speciality.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Specialities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create speciality')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.speciality.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Speciality</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'certification') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Certifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Certifications</span></span></li>
                                                @can('view certification')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.certification.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Certifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create certification')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.certification.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Certification</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view cms')
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'cms') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">CMS</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            @can('create cms')
                                                <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.cms.privacy') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Privacy & Policy</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create cms')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.cms.terms') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Terms & Conditions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create cms')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.cms.faqs') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Faqs</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                                @can('create cms')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.doctor.cms.home') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Home</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                        </ul>
                                    </div>
                                </li>
                                @endcan
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Patient Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                @can('view patients')
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'patient') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Patients</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Doctors</span></span></li>
                                                @can('view patients')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Patients</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create patient')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Patient</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'relation') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Relations</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Relations</span></span></li>
                                                <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.familyMembers.relations.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Relations</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.familyMembers.relations.add') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Relation</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'family_member') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Family Members</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Family Members</span></span></li>
                                                @can('view family_members')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.family_member.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Family Members</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create family_member')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.family_member.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Family Member</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'medical_info') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Medical Infos</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Medical Infos</span></span></li>
                                                @can('view medical_info')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.medical_info.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Medical Infos</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create medical_info')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.medical_info.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Medical Info</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                @can('view cms')
                                <li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'patient cms') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">CMS</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            @can('create cms')
                                                <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.cms.privacy') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Privacy & Policy</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create cms')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.cms.terms') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Terms & Conditions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                            @can('create cms')
                                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.patient.cms.faqs') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Faqs</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                @endcan

                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'patients appointments') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.patient.appointments.index') }}" class="kt-menu__link ">
				                    <i class="kt-menu__link-icon flaticon-location"></i>
				                    <span class="kt-menu__link-text">Appointments</span></a>
				                </li>
				                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'callbackRequest') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.patient.appointments.callbackRequest.view') }}" class="kt-menu__link ">
				                    <i class="kt-menu__link-icon flaticon-location"></i>
				                    <span class="kt-menu__link-text">Callback Request</span></a>
				                </li>
				                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Pharmacy Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'pharmacy') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.user.pharmacy.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Pharmacies</span></a></li>
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Offers Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'offer') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.offers.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Offers</span></a></li>
                                <li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">General Attributes</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'country') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.country.index') }}" class="kt-menu__link ">
				                    <i class="kt-menu__link-icon flaticon-location"></i>
				                    <span class="kt-menu__link-text">Countries & Phone Codes</span></a>
				                </li>
				                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'language') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.language.index') }}" class="kt-menu__link ">
				                    <i class="kt-menu__link-icon flaticon-location"></i>
				                    <span class="kt-menu__link-text">Languages</span></a>
				                </li>
				                <li class="kt-menu__item {{ (isset($menu_active) && $menu_active == 'insurance') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true"><a href="{{ route('admin.insurances.index') }}" class="kt-menu__link ">
				                    <i class="kt-menu__link-icon flaticon2-hospital"></i>
				                    <span class="kt-menu__link-text">Insurances</span></a>
				                </li>
				                <!--<li class="kt-menu__section ">
                                    <h4 class="kt-menu__section-text">CMS Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>-->

                                @can('view cms')
                                    <!--<li class="kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'customer') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Countries</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Countries</span></span></li>
                                                @can('view countries')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.country.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Countries</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create country')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.country.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Country</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>-->
                                @endcan

                               	<li class="dn kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Pharmacy Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>

                                @can('view pharmacy')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'pharmacy') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">Manage Pharmacies</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Pharmacies</span></span></li>
                                                @can('view pharmacies')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.pharmacy.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Pharmacies</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create pharmacy')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.pharmacy.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Pharmacy</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                @can('view admins')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'admin') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-network"></i><span class="kt-menu__link-text">Manage Admins</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Users</span></span></li>
                                                @can('view admins')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.admin.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Admins</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create admin')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.admin.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Admin</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view gym owners')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'gym_owners') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-users"></i><span class="kt-menu__link-text">Manage Gym Owners</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Users</span></span></li>
                                                @can('view gym owners')
                                                    <li class="kt-menu__item    kt-menu__item--active" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.gym_owner.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Gym Owners</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create gym owners')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.user.gym_owner.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Gym Owner</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view roles')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'role') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-list-3"></i><span class="kt-menu__link-text">Manage Roles</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Roles</span></span></li>
                                                @can('view roles')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.role.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Roles</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create role')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.role.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Roles</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                <li class="dn kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Reward Management</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
								@can('view offers')
								<li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'offer') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-coins"></i><span class="kt-menu__link-text">Manage Offers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Offers</span></span></li>
											@can('view offers')
											<li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.offers.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Offers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
											@can('create offer')
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.offers.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Offers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcan
								@can('view vouchers')
								<li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'voucher') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-gift"></i><span class="kt-menu__link-text">Manage Vouchers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Vouchers</span></span></li>
											@can('view vouchers')
											<li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.vouchers.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Vouchers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
											@can('create voucher')
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.vouchers.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Vouchers</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcan
                                @can('view milestones')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'milestones') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-trophy"></i><span class="kt-menu__link-text">Manage Milestones</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Vouchers</span></span></li>
                                                @can('view milestones')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.milestones.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Milestones</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create milestone')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.milestones.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Milestone</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                <li class="dn kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Notifications</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
								@can('view notifications')
								<li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'notifications') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-alarm"></i><span class="kt-menu__link-text">Manage Notifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Notifications</span></span></li>
											@can('view notifications')
											<li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.notifications.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Notification's</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
											@can('create gym')
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.notification.send') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Send Notifications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcan
                                <li class="dn kt-menu__section ">
                                    <h4 class="kt-menu__section-text">Settings</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                @can('view countries')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'country') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-placeholder-3"></i><span class="kt-menu__link-text">Manage Countries & Cities</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Countries</span></span></li>
                                                @can('view countries')
                                                    <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.country.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">View Countries</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('create country')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.country.create') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Create Countries</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view banners')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'city') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-image-file"></i><span class="kt-menu__link-text">Manage Header Banners</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Banners</span></span></li>
                                                @can('view banners')
                                                    <li class="kt-menu__item    kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.banners.index', ['module_type' => 'general']) }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Header Banners</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view banner')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.banners.index', ['module_type' => 'offers']) }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Offer Banners</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('Manage Pages')
                                    <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'pages') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-paper"></i><span class="kt-menu__link-text">Manage Pages</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Pages</span></span></li>
                                                @can('edit privacy_policy')
                                                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.pages.get_privacy_policy') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Privacy Policy</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('edit toc')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.pages.get_toc') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Terms & Conditions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view faqs')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.faq.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Manage FAQ</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view feedback')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.feedback.index') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Feedback</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                                @can('view newsletters')
                                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.pages.newsletters') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Newsletter</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan
                                @can('view finance|view vouchers_usage')
                                <li class="dn kt-menu__item  kt-menu__item--submenu {{ (isset($menu_active) && $menu_active == 'reports') ? 'kt-menu__item--open kt-menu__item--here' : '' }}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-graphic-1"></i><span class="kt-menu__link-text">Manage Reports</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent " aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Manage Reports</span></span></li>
                                            @can('view finance')
                                            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.reports.get_finance') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Finance Report</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                            @endcan
                                            @can('view vouchers_usage')
                                                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ route('admin.reports.get_vouchers') }}" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Vouchers Report</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                            @endcan
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endcan
                                @can('Manage Settings')
                                <li class="dn kt-menu__item " aria-haspopup="true"><a href="{{ route('admin.settings.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-dashboard"></i><span class="kt-menu__link-text">General Settings</span></a></li>
                                @endcan
                                @can('View Logs')
                                <li class="dn kt-menu__item " aria-haspopup="true"><a href="{{ route('admin.logs.index') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-dashboard"></i><span class="kt-menu__link-text">View Logs</span></a></li>
                                @endcan
							</ul>
						</div>
					</div>

					<!-- end:: Aside Menu -->
				</div>

				<!-- end:: Aside -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

						<!-- begin:: Header Menu -->
						<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
								<!-- <ul class="kt-menu__nav ">
									<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Quick Links</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
										<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
											<ul class="kt-menu__subnav">
												<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect id="bound" x="0" y="0" width="24" height="24" />
																	<path d="M8,17 C8.55228475,17 9,17.4477153 9,18 L9,21 C9,21.5522847 8.55228475,22 8,22 L3,22 C2.44771525,22 2,21.5522847 2,21 L2,18 C2,17.4477153 2.44771525,17 3,17 L3,16.5 C3,15.1192881 4.11928813,14 5.5,14 C6.88071187,14 8,15.1192881 8,16.5 L8,17 Z M5.5,15 C4.67157288,15 4,15.6715729 4,16.5 L4,17 L7,17 L7,16.5 C7,15.6715729 6.32842712,15 5.5,15 Z" id="Mask" fill="#000000" opacity="0.3" />
																	<path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" id="Combined-Shape" fill="#000000" />
																</g>
															</svg></span><span class="kt-menu__link-text">Reporting</span></a></li>
												<li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true"><a href="demo1/components/datatable_v1.html" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect id="bound" x="0" y="0" width="24" height="24" />
																	<path d="M3,13.5 L19,12 L3,10.5 L3,3.7732928 C3,3.70255344 3.01501031,3.63261921 3.04403925,3.56811047 C3.15735832,3.3162903 3.45336217,3.20401298 3.70518234,3.31733205 L21.9867539,11.5440392 C22.098181,11.5941815 22.1873901,11.6833905 22.2375323,11.7948177 C22.3508514,12.0466378 22.2385741,12.3426417 21.9867539,12.4559608 L3.70518234,20.6826679 C3.64067359,20.7116969 3.57073936,20.7267072 3.5,20.7267072 C3.22385763,20.7267072 3,20.5028496 3,20.2267072 L3,13.5 Z" id="Combined-Shape" fill="#000000" />
																</g>
															</svg></span><span class="kt-menu__link-text">Social Presence</span><i class="kt-menu__hor-arrow la la-angle-right"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
													<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right">
														<ul class="kt-menu__subnav">
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Reached Users</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">SEO Ranking</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">User Dropout Points</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Market Segments</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Opportunity Growth</span></a></li>
														</ul>
													</div>
												</li>
												<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect id="bound" x="0" y="0" width="24" height="24" />
																	<path d="M11.575,21.2 C6.175,21.2 2.85,17.4 2.85,12.575 C2.85,6.875 7.375,3.05 12.525,3.05 C17.45,3.05 21.125,6.075 21.125,10.85 C21.125,15.2 18.825,16.925 16.525,16.925 C15.4,16.925 14.475,16.4 14.075,15.65 C13.3,16.4 12.125,16.875 11,16.875 C8.25,16.875 6.85,14.925 6.85,12.575 C6.85,9.55 9.05,7.1 12.275,7.1 C13.2,7.1 13.95,7.35 14.525,7.775 L14.625,7.35 L17,7.35 L15.825,12.85 C15.6,13.95 15.85,14.825 16.925,14.825 C18.25,14.825 19.025,13.725 19.025,10.8 C19.025,6.9 15.95,5.075 12.5,5.075 C8.625,5.075 5.05,7.75 5.05,12.575 C5.05,16.525 7.575,19.1 11.575,19.1 C13.075,19.1 14.625,18.775 15.975,18.075 L16.8,20.1 C15.25,20.8 13.2,21.2 11.575,21.2 Z M11.4,14.525 C12.05,14.525 12.7,14.35 13.225,13.825 L14.025,10.125 C13.575,9.65 12.925,9.425 12.3,9.425 C10.65,9.425 9.45,10.7 9.45,12.375 C9.45,13.675 10.075,14.525 11.4,14.525 Z" id="@" fill="#000000" />
																</g>
															</svg></span><span class="kt-menu__link-text">Sales & Marketing</span></a></li>
												<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect id="bound" x="0" y="0" width="24" height="24" />
																	<polygon id="Path-75" fill="#000000" opacity="0.3" points="5 15 3 21.5 9.5 19.5" />
																	<path d="M16,10 L16,9.5 C16,8.11928813 14.8807119,7 13.5,7 C12.1192881,7 11,8.11928813 11,9.5 L11,10 C10.4477153,10 10,10.4477153 10,11 L10,14 C10,14.5522847 10.4477153,15 11,15 L16,15 C16.5522847,15 17,14.5522847 17,14 L17,11 C17,10.4477153 16.5522847,10 16,10 Z M13.5,21 C8.25329488,21 4,16.7467051 4,11.5 C4,6.25329488 8.25329488,2 13.5,2 C18.7467051,2 23,6.25329488 23,11.5 C23,16.7467051 18.7467051,21 13.5,21 Z M13.5,8 L13.5,8 C14.3284271,8 15,8.67157288 15,9.5 L15,10 L12,10 L12,9.5 C12,8.67157288 12.6715729,8 13.5,8 Z" id="Combined-Shape" fill="#000000" />
																</g>
															</svg></span><span class="kt-menu__link-text">Campaigns</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--success kt-badge--rounded">3</span></span></a></li>
												<li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect id="bound" x="0" y="0" width="24" height="24" />
																	<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" id="Combined-Shape" fill="#000000" />
																	<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" id="Combined-Shape" fill="#000000" opacity="0.3" />
																</g>
															</svg></span><span class="kt-menu__link-text">Deployment Center</span><i class="kt-menu__hor-arrow la la-angle-right"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
													<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right">
														<ul class="kt-menu__subnav">
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Merge Branch</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--danger kt-badge--rounded">3</span></span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Version Controls</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Database Manager</span></a></li>
															<li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">System Settings</span></a></li>
														</ul>
													</div>
												</li>
											</ul>
										</div>
									</li>
								</ul> -->
							</div>
						</div>

						<!-- end:: Header Menu -->

						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">


							<!--end: Language bar -->

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
									<div class="kt-header__topbar-user">
										<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
										<span class="kt-header__topbar-username kt-hidden-mobile">{{ Auth::user()->first_name }}</span>
										<img class="kt-hidden" alt="Pic" src="{{ asset('public/uploads/'. Auth::user()->image) }}" />

										<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
										<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

									<!--begin: Head -->
									<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ asset('public/assets/media/misc/bg-1.jpg') }})">
										<div class="kt-user-card__avatar">
											<img class="kt-hidden" alt="Pic" src="{{ asset('public/uploads/'. Auth::user()->image) }}" />

											<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
											<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
										</div>
										<div class="kt-user-card__name">
											{{ Auth::user()->first_name }}
										</div>
									</div>

									<!--end: Head -->

									<!--begin: Navigation -->
									<div class="kt-notification">
                                        @can('admin')
                                            @php
                                            $profile_url = route('admin.user.admin.edit', \Auth::user()->id);
                                            @endphp
                                        @elsecan('gym owner')
                                            @php
                                                $profile_url = route('admin.user.gym_owner.edit', \Auth::user()->id);
                                            @endphp
                                        @endcan
										<a href="{{ isset($profile_url) ? $profile_url : '' }}" class="kt-notification__item dn">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-calendar-3 kt-font-success"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													My Profile
												</div>
												<div class="kt-notification__item-time">
													Account settings and more
												</div>
											</div>
										</a>
										<a href="{{ route('admin.logs.index') }}" class="kt-notification__item dn">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-rocket-1 kt-font-danger"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													Activities
												</div>
												<div class="kt-notification__item-time">
													Activity Logs
												</div>
											</div>
										</a>
										<div class="kt-notification__custom kt-space-between">
											<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" target="_blank" class="btn btn-label btn-label-brand btn-sm btn-bold">{{ __('Logout') }}</a>
										</div>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                                        @csrf
	                                    </form>
									</div>

									<!--end: Navigation -->
								</div>
							</div>

							<!--end: User Bar -->
						</div>

						<!-- end:: Header Topbar -->
					</div>

					<!-- end:: Header -->
					<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

						<!-- begin:: Subheader -->
						<div class="kt-subheader   kt-grid__item" id="kt_subheader" style="display: none;">
							<div class="kt-container  kt-container--fluid ">
								<div class="kt-subheader__main">
									<h3 class="kt-subheader__title">
										{{ isset($title) ? ucwords($title) : env('APP_NAME') }} </h3>
									<span class="kt-subheader__separator kt-hidden"></span>
									<div class="kt-subheader__breadcrumbs">
										<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="{{ route('home') }}" class="kt-subheader__breadcrumbs-link">
											Dashboard </a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="" class="kt-subheader__breadcrumbs-link">
											{{ isset($title) ? ucwords($title) : env('APP_NAME') }} </a>

										<!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
									</div>
								</div>
							</div>
						</div>

						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

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
							@yield('content')
						 </div>

						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin:: Global Mandatory Vendors -->
		<script src="{{ asset('public/assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/wnumb/wNumb.js') }}" type="text/javascript"></script>

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<script src="{{ asset('public/assets/vendors/general/jquery-form/dist/jquery.form.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/block-ui/jquery.blockUI.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/bootstrap-switch.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/select2/dist/js/select2.full.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/typeahead.js/dist/typeahead.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/handlebars/dist/handlebars.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/nouislider/distribute/nouislider.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/owl.carousel/dist/owl.carousel.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/autosize/dist/autosize.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/clipboard/dist/clipboard.min.js') }}" type="text/javascript"></script>
		<!-- <script src="{{ asset('public/assets/vendors/general/dropzone/dist/dropzone.js') }}" type="text/javascript"></script> -->
		<script src="{{ asset('public/assets/vendors/general/summernote/dist/summernote.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/markdown/lib/markdown.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/bootstrap-markdown.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/bootstrap-notify.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/jquery-validation/dist/jquery.validate.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/jquery-validation/dist/additional-methods.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/jquery-validation.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/raphael/raphael.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/morris.js/morris.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/chart.js/dist/Chart.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/waypoints/lib/jquery.waypoints.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/counterup/jquery.counterup.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/es6-promise-polyfill/promise.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/sweetalert2/dist/sweetalert2.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/custom/js/vendors/sweetalert2.init.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/jquery.repeater/src/lib.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/jquery.repeater/src/jquery.input.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/jquery.repeater/src/repeater.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/assets/vendors/general/dompurify/dist/purify.js') }}" type="text/javascript"></script>
		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="{{ asset('public/assets/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="{{ asset('public/assets/js/demo1/pages/crud/forms/widgets/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		@yield('scripts')

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>
