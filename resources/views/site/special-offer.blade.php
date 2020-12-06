@extends('site.layouts.index')
@section('content')
<section class="mein__offer__sec">
	<div class="offer__banner__sec">
		<div class="offer__banner__box">
			<img class="img-fluid" src="images/offer-banner.png" alt="" />
			<div class="offer__caption">
				<div class="offer__search__box">
					<form action="" method="">
						<input type="" class="form-control" name="" placeholder="What are you looking for?">
					</form>
				</div>
				<div class="inner__offer__box">
					<h1>25% OFF</h1>
					<h2>Dental Services</h2>
					<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>
			</div>
		</div>
	</div>
</section>
@if(count($offers_by_categories)>0)
<section class="offer__category__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading text-center">
					<h1>Offers by Category</h1>
					<h2 class="black">Find the best offers by category or Check out the trending offers</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 offset-1">
				<div class="category__box">
					<div class="category__figure">
                        <a href="{!! url('offers/category_id/'.$offers_by_categories[0]->id) !!}">
						    <img class="img-fluid" src="{!! $offers_by_categories[0]->image !!}" alt="" />
                        </a>
						<div class="category_caption">
							<h3>{!! $offers_by_categories[0]->name !!}</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
                @foreach($offers_by_categories as $key => $cat)
                    <?php if($key == 0){ continue; } ?>
                <?php if($key%2 == 1){ ?>
				<div class="right__category__box">
                    <?php } ?>

					<div class="inner__category__box">
						<div class="category__figure">
                            <a href="{!! url('offers/category_id/'.$cat->id) !!}">
							<img class="img-fluid" src="{!! $cat->image !!}" alt="" />
                            </a>
							<div class="category_caption">
                                <h3><a href="{!! url('offers/category_id/'.$cat->id) !!}">{!! $cat->name !!}</a></h3>
							</div>
						</div>
					</div>

                        <?php if($key%2 == 0 || $key == (count($offers_by_categories)-1)){ ?>
				            </div>
                    <?php } ?>
                @endforeach
			</div>
		</div>
	</div>
</section>
@endif
@if(count($featured_offers)>0)
<section class="offer__category__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading text-center">
					<h1>Trending Now</h1>
					<h2 class="black">Contrary to popular belief, Lorem Ipsum is not simply random text.</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 offset-1">
				<div class="mein__trending__sec">
					<div class="trending__slider">

                            @foreach($featured_offers as $offer)
						<div>
							<div class="category__figure">
								<span class="offer__rate">{!! $offer->discount.$offer->discount_unit !!} OFF</span>
                                <a href="{!! url('offer/'.$offer->id) !!}">
                                @if (!empty($offer->banner) && @getimagesize(asset('public/uploads/images/'.$offer->banner)))
                                    <img class="img-fluid" src="{!! asset('public/uploads/images/'.$offer->banner) !!}" alt="" />
                                @else
                                    <img class="img-fluid" src="images/inner-offer-banner.png" alt="" />
                                @endif
                                </a>
                                <div class="category_caption">
									<h3>{!! $offer->short_description !!}</h3>
								</div>
							</div>
						</div>
                            @endforeach

					</div>
					<div class="slick-container">
						<a href="javascript:;" class="custom__arrow__btn carousel-prev"><i class="fa fa-angle-left"></i></a>
						<a href="javascript:;" class="custom__arrow__btn carousel-next"><i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endif
@if(count($new_offers)>0)
<section class="offer__category__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading text-center">
					<h1>New Offers</h1>
					<h2 class="black">Contrary to popular belief, Lorem Ipsum is not simply random text.</h2>
				</div>
			</div>
		</div>
    <?php $size = count($new_offers); ?>
        @foreach($new_offers as $key => $cats)

            <?php
            if($key%3 == 0){ ?>
            <?php if($key != 0 && $key != $size-1){ ?>
                </div>
                <?php } ?>
		        <div class="row">
            <?php } ?>
			<div class="col-lg-4">
				<div class="category__figure new__offer__figure">
                    <a href="{!! url('offer/'.$cats->id) !!}">
                        @if(!empty($cats->image))
                        <img class="img-fluid" src="{!! asset('public/uploads/images/'.$cats->image) !!}" alt="" />
                        @else
                            <img class="img-fluid" src="images/cate1.png" alt="" />
                            @endif
                    </a>
					<div class="category_caption">
						<h3>{!! $cats->name !!}</h3>
					</div>
				</div>
			</div>
                <?php if($key == ($size-1)){ ?>
		            </div>
                <?php } ?>
        @endforeach

	</div>
</section>
    @endif
@endsection
{{-- @section('scripts')
@endsection --}}
