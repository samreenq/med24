@extends('site.layouts.index')
@section('content')
<section class="frontend__slider__sec">
    <div class="advance__search__box">
        @include('site.search-form')
    </div>

	<div class="home__top__slider">
		<div>
			<div class="home__banner__slide">
				<div class="banner__figure">
					<img class="img-fluid" src="images/slide-01.png" alt="" />
				</div>
				<div class="slide__caption">
					<p>{!! $options->search_description !!}</p>
					<div class="banner__app__download">
						<h4>Download the App Today</h4>
						<ul>
							<li><a href="javascript:;"><img src="images/app-icon-footer.svg" alt="" /></a></li>
							<li><a href="javascript:;"><img src="images/appstore_black.svg" alt="" /></a></li>
							<li><a href="javascript:;"><img src="images/playstore_black.svg" alt="" /></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="home__banner__slide">
				<div class="banner__figure">
					<img class="img-fluid" src="images/slide-03.png" alt="" />
				</div>
				<div class="slide__caption">
					<p>{!! $options->search_description2 !!}</p>
					<div class="booking__box">
						<div class="custom__btn">
							<a href="javascript:;">Book Now</a>
						</div>
						<div class="banner__app__download">
							<ul>
								<li><a href="javascript:;"><img class="img-fluid" width="220px" src="images/banner-logo.png" alt="" /></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="home__banner__slide">
				<div class="banner__figure">
					<img class="img-fluid" src="images/slide-03.png" alt="" />
				</div>
				<div class="slide__caption">
					<h4>{!! $options->search_title3 !!}</h4>
					<p>{!! $options->search_description3 !!}</p>
					<div class="booking__box">
						<div class="custom__btn">
							<a href="javascript:;">See Offers</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wave__box">
		<img src="images/banner-wave.svg" alt="" />
	</div>
	<div class="slick-container">
		<button class="custom__arrow__btn carousel-prev"><img src="images/left-blue.svg" alt="" /></button>
		<button class="custom__arrow__btn carousel-next"><img src="images/right-blue.svg" alt="" /></button>
	</div>
</section>
<section class="health__provider custom__padding">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading text-center">
					<h1>{!! $options->search_title4 !!}</h1>
					<h2 class="black">{!! $options->search_description4 !!}</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="partner__logo__box">
					<div class="partner__box">
						<img src="images/partner1.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner2.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner3.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner4.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner5.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner6.png" alt="" />
					</div>
					<div class="partner__box">
						<img src="images/partner7.png" alt="" />
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="app__slider__sec">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading text-center">
					<h1>{!! $options->download_title !!}</h1>
					<h2 class="black custom__padding__heading">{!! $options->download_description !!}</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<div class="service__box">
					<div class="service__figure">
						<img class="img-fluid" src="images/service1.png" alt="" />
					</div>
					<div class="service__caption">
						<h4>Search for <br> healthcare providers</h4>
						<p>covered by your <br> insurance plan.</p>
					</div>
				</div>
				<div class="service__box">
					<div class="service__figure">
						<img class="img-fluid" src="images/service2.png" alt="" />
					</div>
					<div class="service__caption">
						<h4>Book <br> appointments</h4>
						<p>quickly and easily.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="app__slider">
					<div class="app__slide__box">
						<div class="app__slide">
							<img class="img-fluid" src="images/screen.png" alt="" />
						</div>
						<div class="app__slide">
							<img class="img-fluid" src="images/screen1.png" alt="" />
						</div>
						<div class="app__slide">
							<img class="img-fluid" src="images/Screen6.png" alt="" />
						</div>
						<div class="app__slide">
							<img class="img-fluid" src="images/Screen7.png" alt="" />
						</div>
					</div>
					<div class="slick-container">
						<button class="custom__arrow__btn carousel-prev"><img src="images/left.svg" alt="" /></button>
						<button class="custom__arrow__btn carousel-next"><img src="images/right.svg" alt="" /></button>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="service__box">
					<div class="service__figure">
						<img class="img-fluid" src="images/service3.png" alt="" />
					</div>
					<div class="service__caption">
						<h4>Send prescriptions</h4>
						<p>to your pharmacist.</p>
					</div>
				</div>
				<div class="service__box">
					<div class="service__figure">
						<img class="img-fluid" src="images/service4.png" alt="" />
					</div>
					<div class="service__caption">
						<h4>Read reviews and rate</h4>
						<p>Healthcare providers.</p>
					</div>
				</div>
				<div class="banner__app__download app__download__box">
					<h4>Download <span>the App Today</span></h4>
					<ul>
						<li><a href="javascript:;"><img src="images/app-icon-footer.svg" alt="" /></a></li>
						<li><a href="javascript:;"><img src="images/appstore_black.svg" alt="" /></a></li>
						<li><a href="javascript:;"><img src="images/playstore_black.svg" alt="" /></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="wave__box">
		<img src="images/app-blue-wave.svg" alt="" />
	</div>
</section>
@if(count($featured_doctors)>0)
<section class="recommended__sec custom__padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading custom__heading__margin text-center">
					<h1 class="black">Recommended Health Practitioners</h1>
				</div>
			</div>
		</div>
		<div class="row">
            @foreach($featured_doctors as $doctor)
			<div class="col-lg-3">
				<div class="recommended__box text-center">
					<div class="doctor__figure">
                        @if (@getimagesize($doctor['image']))
                            <a href="{!! url('doctor-profile/'.$doctor['id']) !!}" ><img src="{!! $doctor['image'] !!}" alt="" /></a>
                        @else
                            <a href="{!! url('doctor-profile/'.$doctor['id']) !!}" ><img src="images/doctor.jpg" alt="" /></a>
                        @endif
					</div>
					<div class="doctor__bio">
						<h3>{!! $doctor['first_name'].' '.$doctor['last_name'] !!}</h3>
						<span>{!! ucfirst(strtolower($doctor['specialist'])) !!}</span>
						<p>{!! limitStr($doctor['about']) !!}</p>
						<div class="rating__star">
							<fieldset class="rating">

                                <?php $style = " "; ?>
                                @for($i = 1; $i<=5; $i++)
                                    @if($doctor['avg_rating'] != '')
                                        @if($doctor['avg_rating'] <= $i)
                                            <?php
                                                $style = 'style="color:#ffc107"';
                                            ?>
                                        @endif
                                    @endif
                                    <input  type="radio" id="star{!! $i !!}" name="rating" value="{!! $i !!}" /><label {!! $style !!} class = "full" for="star{!! $i !!}" title="{!! $i !!} stars"></label>
                                    @endfor

								<!--<input type="radio" id="star5" name="rating" value="5" /><label class = "half" for="star5" title="Awesome - 5 stars"></label>
								<input type="radio" id="star4half" name="rating" value="4 and a half" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
								<input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
								<input type="radio" id="star3half" name="rating" value="3 and a half" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
								<input  type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
								<input type="radio" id="star2half" name="rating" value="2 and a half" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
								<input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
								<input type="radio" id="star1half" name="rating" value="1 and a half" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
								<input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
								<input type="radio" id="starhalf" name="rating" value="half" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
							--></fieldset>
							<div class="custom__btn">
                                @if(isset($login_user->id))
                                    <?php $url = url('book-appointment/doctor/'.$doctor['id']) ?>
                                @else
                                    <?php $url = url('sign-in') ?>
                                @endif
								<a href="{!! $url !!}">Book Appointment</a>
							</div>
						</div>
					</div>
				</div>
			</div>
            @endforeach
		</div>
	</div>
	<div class="wave__box">
		<img src="images/wave.svg" alt="" />
	</div>
</section>
@endif
@if(count($featured_hospitals)>0)
<section class="featured__sec custom__padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading custom__heading__margin text-center">
					<h1 class="black">Featured Hospitals</h1>
				</div>
			</div>
		</div>
		<div class="row">
            @foreach($featured_hospitals as $hospital)
			<div class="col-lg-3">
				<div class="recommended__box text-center">
					<div class="hospital__figure">
                        <a href="{!! url('hospital/'.$hospital['id']) !!}">
                        @if (@getimagesize($hospital['image']))
                            <img class="img-fluid" src="{!! $hospital['image'] !!}" alt="" />
                        @else
                            <img class="img-fluid" src="images/hospital.png" alt="" />
                        @endif
                        </a>
					</div>
					<div class="doctor__bio">
						<h3>{!! $hospital['name'] !!}</h3>
						<span>{!! $hospital['specialist'] !!}</span>
						<p>{!! limitStr($hospital['description']) !!}</p>
						<div class="rating__star">
							<fieldset class="rating">
                                <?php $style = " "; ?>
                                @for($j = 1; $j<=5; $j++)
                                    @if($hospital['AverageRating'] != '')
                                        @if($hospital['AverageRating'] <= $j)
                                            <?php
                                            $style = 'style="color:#ffc107"';
                                            ?>
                                        @endif
                                    @endif
                                    <input  type="radio" id="star{!! $j !!}" name="rating" value="{!! $j !!}" /><label {!! $style !!} class = "full" for="star{!! $j !!}" title="{!! $j !!} stars"></label>
                                @endfor
								<!--<input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
								<input type="radio" id="star4half" name="rating" value="4 and a half" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
								<input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
								<input type="radio" id="star3half" name="rating" value="3 and a half" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
								<input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
								<input type="radio" id="star2half" name="rating" value="2 and a half" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
								<input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
								<input type="radio" id="star1half" name="rating" value="1 and a half" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
								<input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
								<input type="radio" id="starhalf" name="rating" value="half" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
							-->
                            </fieldset>
							<div class="custom__btn">
                                @if(isset($login_user->id))
                                    <?php $url = url('book-appointment/hospital/'.$hospital['id']) ?>
                                @else
                                    <?php $url = url('sign-in') ?>
                                @endif
								<a href="{!! $url !!}">Book Appointment</a>
							</div>
						</div>
					</div>
				</div>
			</div>
            @endforeach
		</div>
	</div>
	<div class="wave__box">
		<img src="images/wave1.svg" alt="" />
	</div>
</section>
@endif
@if(count($featured_offers))
<section class="recommended__sec custom__padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading custom__heading__margin text-center">
					<h1>Special Offers</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="offer__slider">
                    @foreach($featured_offers as $offer)
                        <?php $offer_image = asset('public/uploads/images/'.$offer->image);  ?>
					<div class="offer__box">
						<div class="offer__figure">
                            @if (@getimagesize($offer_image))
							<img class="img-fluid" src="" alt="{!! $offer_image !!}" />
                            @else
							<img class="img-fluid" src="images/offer-img.png" alt="" />
                                @endif
						</div>
						<div class="offer__bio">
							<h3>{!! $offer->discount.$offer->discount_unit !!} Off</h3>
							<span>{!! $offer->name !!}</span>
							<p>{!! $offer->short_description !!}</p>
							<div class="custom__btn offer__btn">
								<a href="{!! url('offer/'.$offer->id) !!}">View More</a>
							</div>
						</div>
					</div>
                    @endforeach
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__btn see__all__btn">
					<a href="{!! url('special-offers') !!}">See All Offers</a>
				</div>
			</div>
		</div>
	</div>
	<div class="slick-container">
		<button class="custom__arrow__btn carousel-prev"><img src="images/left.svg" alt="" /></button>
		<button class="custom__arrow__btn carousel-next"><img src="images/right.svg" alt="" /></button>
	</div>
</section>
@endsection
@endif
{{-- @section('scripts')
@endsection --}}
