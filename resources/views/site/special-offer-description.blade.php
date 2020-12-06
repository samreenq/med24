@extends('site.layouts.index')
@section('content')
<section class="mein__offer__sec inner__offer__banner">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="inner__offer__figure">
                    @if (!empty($offer->banner) && @getimagesize(asset('public/uploads/images/'.$offer->banner)))
                        <img class="img-fluid" src="{!! asset('public/uploads/images/'.$offer->banner) !!}" alt="" />
                    @else
                        <img class="img-fluid" src="images/inner-offer-banner.png" alt="" />
                    @endif

					<div class="over__effect"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="inner__offer__bio">
					<h2>{!! $offer->name !!}</h2>
					<h1>{!! $offer->discount.$offer->discount_unit !!} OFF</h1>
					<p>{!! $offer->short_description !!}</p>
					<span>Valid till: {!! date('F j, Y',strtotime($offer->end_datetime)) !!}</span>
				</div>
				<div class="offer__description__sec">
					<h3>Description</h3>
					<p>{!! $offer->description !!}</p>
					<span>PROMO CODE <strong>{!! $offer->code !!}</strong></span>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
