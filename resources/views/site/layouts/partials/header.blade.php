<header class="{{ \Route::current()->getName() == "site.index" ? "home__header" : "internal__header" }}">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="header__box">
					<div class="logo__box">
						<a href="javascript:;">
							<img src="images/logo.svg" alt="" />
						</a>
					</div>
					<div class="logo__box inner__logo__box">
						<a href="{!! url('/') !!}">
							<img src="images/inner-logo.png" alt="" />
						</a>
					</div>
					<div class="main__menu">
						<ul>
                            @if(isset($login_user->id))
                                <?php $appointment_url = url('current-appointment') ?>
                            @else
                                <?php $appointment_url = url('sign-in') ?>
                            @endif
							<li><a href="{!! $appointment_url !!}"><i class="menu-icon icon-my-appointment"></i> My Appointments</a></li>
							{{--<li><a href="{!! url('e-prescriptions') !!}"><i class="menu-icon icon-e-prescription"></i> My e-Prescriptions</a></li>--}}
							<li><a href="{!! url('offers') !!}"><i class="menu-icon icon-special-offers"></i> Special Offers</a></li>
							<li><a href="{!! url('about-us') !!}"><i class="menu-icon icon-about-us"></i> About Us</a></li>
                                <li><a href="{!! url('contact-us') !!}"><i class="menu-icon icon-e-prescription"></i>Contact Us</a></li>
						</ul>
					</div>
                    @if(isset($login_user->id))
					<div class="dropdown__box">
						<div class="profile__box">
							<div class="profile__thumb">
                                @if(empty($login_user->image))
								<img src="images/profile-img1.png" alt="" />
                                @else
                                <img class="img-fluid" width="45px" height="45px" src="{!! asset('public/uploads/images/'.$login_user->image) !!}" alt="" />
                                @endif
                            </div>
							<div class="profile__title">
								<h4>{!! $login_user->first_name.' '.$login_user->last_name !!} <i class="fa fa-angle-down"></i></h4>
								<ul class="dropdown__menu">
									<li><a href="{!! url('edit-profile') !!}">My Profile</a></li>
									{{--<li><a href="{!! url('favourite-doctor') !!}">Offers</a></li>--}}
									<li><a href="{!! url('favourite-doctor') !!}">Favorites</a></li>
									<li><a href="{{url('faq')}}">FAQS</a></li>
									<li><a href="{{url('term-and-condition')}}">Terms &amp; Conditions</a></li>
									<li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
									<li><a href="{!! url('logout') !!}">Sign Out <i class="fa fa-sign-out"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					@else
							<div class="btn__box">
						<div class="flex__btn__box">
							<a href="{!! url('sign-in') !!}">Sign Up or Login</a>
                                @endif
						</div>
						<div class="flex__btn__box">
							<a href="javascript:;">Partner With Us</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<style>
    ul.pagination {float:right}
</style>
