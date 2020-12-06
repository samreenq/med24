@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.insurance-sidebar')
			</div>
			<div class="col-lg-9">
				<div class="main__doctor__profile">
					<div class="custom__heading">
						<h2>Associate Doctors</h2>
					</div>
					<div class="row">
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure">
									<img class="img-fluid" src="images/doctor1.jpg" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure">
									<img class="img-fluid" src="images/doctor1.jpg" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure">
									<img class="img-fluid" src="images/doctor1.jpg" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="main__doctor__profile">
					<div class="custom__heading">
						<h2>Associate Hospitals</h2>
					</div>
					<div class="row">
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure hospital__figure__sec">
									<img class="img-fluid" src="images/hospital1.png" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure hospital__figure__sec">
									<img class="img-fluid" src="images/hospital1.png" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="doctor__box__sec">
								<div class="profile__doctor__figure hospital__figure__sec">
									<img class="img-fluid" src="images/hospital1.png" alt="" />
								</div>
								<div class="doctor__caption">
									<h2>Dr John Kasiak</h2>
									<span>Paediatrician</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="main__doctor__profile">
					<div class="custom__heading">
						<h2>Locations</h2>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div id="map" class="hospital__figure__sec">
								<img class="img-fluid" src="images/map.png" alt="" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}