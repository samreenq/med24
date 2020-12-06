@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.doctor-sidebar')
			</div>
			<div class="col-lg-9">
				<div class="overview__tabs__sec">
					<div class="tab__body__sec">
						<div class="inner__tab__body">
							<h2>About</h2>
							<p>{!! $doctor->about !!}</p>
						</div>
						<div class="inner__tab__body">
							<h2>Specialities</h2>
							<ul>
                                @if(count($doctor->specialities)>0)
                                    @foreach($doctor->specialities as $sp)
								        <li>{!! $sp->name !!}</li>
                                    @endforeach
                                @endif
							</ul>
						</div>
						<div class="inner__tab__body certification__sec">
							<h2>Board Certifications</h2>
							<ul>
                                @if(count($doctor->certifications)>0)
                                    @foreach($doctor->certifications as $cert)
                                        <li>{!! $cert->name !!}</li>
                                    @endforeach
                                @endif
							</ul>
						</div>
						{{--<div class="inner__tab__body">
							<h2>Awards</h2>
							<ul>
								<li>Lorem Ipsum dolor sit</li>
								<li>Lorem Ipsum dolor sit</li>
								<li>Lorem Ipsum dolor sit</li>
								<li>Lorem Ipsum dolor sit</li>
								<li>Lorem Ipsum dolor sit</li>
								<li>Lorem Ipsum dolor sit</li>
							</ul>
						</div>--}}
						{{--<div class="inner__tab__body">
							<h2>Locations</h2>
							<div id="map">
								<img class="img-fluid" src="images/map.png" alt="" />
							</div>
						</div>--}}
                        @if(count($doctor->doctors_insurances)>0)
						<div class="inner__tab__body insurance__list">
							<h2>Insurance Accepted</h2>
							<ul>
                                @foreach($doctor->doctors_insurances as $ins)
                                    <li><img src="{!! asset('public/uploads/'.$ins->insurance->profilePhoto) !!}" alt="" /></li>
                                @endforeach
							</ul>
						</div>
                        @endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
