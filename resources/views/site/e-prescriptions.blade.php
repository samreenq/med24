@extends('site.layouts.index')
@section('content')
<section class="prescriptions__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="inner__presc__box">
					<div class="presc__search__box">
						<h4>E-Prescriptions</h4>
						<div class="add__new">
							<a href="javascript:;"><i class="fa fa-plus"></i> Add New</a>
						</div>
						<div class="presc__search__form">
							<form method="" action="">
								<div class="form-group">
									<input type="text" name="" class="form-control search__field" placeholder="Search by Prescription">
								</div>
								<div class="form-group date__field">
									<input type="text" name="" class="form-control calendar" placeholder="Date Select">
								</div>
							</form>
						</div>
						<div class="mein__prescriptions__group">
							<div class="prescriptions__group">
								<div class="prescriptions__left">
									<div class="prescriptions__figure">
										<img src="images/presc-img.png" alt="" />
									</div>
									<div class="prescriptions__bio">
										<h5>Prescription</h5>
										<a href="javascript:;">Diabetes Medicine</a>
										<p>Mediclinic Pharma</p>
									</div>
								</div>
								<div class="prescriptions__right">
									<div class="prefix__date">
										<span><i class="fa fa-calendar"></i> 23/09/2020</span>
									</div>
									<div class="delivered__btn">
										<a href="javascript:;">Delivered</a>
									</div>
								</div>
							</div>
							<div class="prescriptions__group">
								<div class="prescriptions__left">
									<div class="prescriptions__figure">
										<img src="images/presc-img.png" alt="" />
									</div>
									<div class="prescriptions__bio">
										<h5>Prescription</h5>
										<a href="javascript:;">Diabetes Medicine</a>
										<p>Mediclinic Pharma</p>
									</div>
								</div>
								<div class="prescriptions__right">
									<div class="prefix__date">
										<span><i class="fa fa-calendar"></i> 23/09/2020</span>
									</div>
									<div class="delivered__btn">
										<a href="javascript:;">Delivered</a>
									</div>
								</div>
							</div>
						</div>
						<div class="mein__prescriptions__group">
							<div class="prescriptions__group">
								<div class="prescriptions__left">
									<div class="prescriptions__figure">
										<img src="images/presc-img.png" alt="" />
									</div>
									<div class="prescriptions__bio">
										<h5>Prescription</h5>
										<a href="javascript:;">Diabetes Medicine</a>
										<p>Mediclinic Pharma</p>
									</div>
								</div>
								<div class="prescriptions__right">
									<div class="prefix__date">
										<span><i class="fa fa-calendar"></i> 23/09/2020</span>
									</div>
									<div class="delivered__btn">
										<a href="javascript:;">Delivered</a>
									</div>
								</div>
							</div>
							<div class="prescriptions__group">
								<div class="prescriptions__left">
									<div class="prescriptions__figure">
										<img src="images/presc-img.png" alt="" />
									</div>
									<div class="prescriptions__bio">
										<h5>Prescription</h5>
										<a href="javascript:;">Diabetes Medicine</a>
										<p>Mediclinic Pharma</p>
									</div>
								</div>
								<div class="prescriptions__right">
									<div class="prefix__date">
										<span><i class="fa fa-calendar"></i> 23/09/2020</span>
									</div>
									<div class="delivered__btn">
										<a href="javascript:;">Delivered</a>
									</div>
								</div>
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