@extends('site.layouts.index')
@section('content')
<div class="modal__box">
	<div class="modal__body">
		<div class="inner__modal__body">
			<img src="images/modal-icon.svg" alt="" />
			<h1>Congratulations!</h1>
			<p>Your Prescription has Sent to Pharmacy.</p>
			<div class="custom__btn">
				<a href="javascript:;">GO BACK</a>
			</div>
		</div>
	</div>
</div>
<section class="prescriptions__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-2">
				<div class="inner__presc__box mein__add__Prescription">
					<div class="presc__search__box">
						<h4>Add E-Prescription</h4>
					</div>
					<form action="" method="">
						<div class="row add__Prescriptions">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="" placeholder="E-prescription name">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Select Pharmacy</label>
									<div class="location">
										<input type="text" class="form-control" name="" placeholder="Pharmacy">
									</div>
								</div>
							</div>
						</div>
						<div class="row add__Prescriptions">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Pharma ABC</label>
									<input type="text" class="form-control" name="" placeholder="Ut enim ad minim veniam, quis nostrud">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Pharma ABC</label>
									<input type="text" class="form-control" name="" placeholder="Ut enim ad minim veniam, quis nostrud">
								</div>
							</div>
						</div>
						<div class="row add__Prescriptions">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Pharma ABC</label>
									<input type="text" class="form-control" name="" placeholder="Ut enim ad minim veniam, quis nostrud">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Attachment</label>
									<input type="text" class="form-control" name="" placeholder="Ut enim ad minim veniam, quis nostrud">
								</div>
							</div>
						</div>
						<div class="row add__Prescriptions">
							<div class="col-lg-6">
								<div class="form-group">
									<label>&nbsp;</label>
									<div class="mein__label__card">
										<div class="label__card__box">
											<span>Emirates ID <i class="fa fa-check-circle"></i></span>
										</div>
										<div class="label__card__box">
											<span>Insurance ID <i class="fa fa-check-circle"></i></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>&nbsp;</label>
									<div class="mein__checkbox__sec">
										<div class="checkbox__sec">
											<label><input type="checkbox" class="checkbox" name=""> Pick item by myself</label>
										</div>
										<div class="checkbox__sec">
											<label><input type="checkbox" class="checkbox" name=""> Get it Delivered</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row add__Prescriptions">
							<div class="col-lg-12">
								<div class="form-group">
									<div class="custom__btn">
										<button type="submit">Save Changes</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
