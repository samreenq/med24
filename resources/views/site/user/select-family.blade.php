@extends('site.layouts.index')
@section('content')
<section class="select__doctor__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 offset-3">
				<div class="doctor__search__box text-center">
					<h2>Select Family</h2>
					<p>Make your appointment feasible select your Doctor from here !!!</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 offset-3">
				<div class="add__detail__form">
					<form method="" action="">
						<div class="form-group">
							<select class="selectpicker">
								<option>John Doe - Son</option>
								<option>Ketchup</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
							</select>
						</div>
						<div class="form-group">
							<select class="selectpicker">
								<option>John Doe - Son</option>
								<option>Ketchup</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
							</select>
						</div>
						<div class="form-group">
							<label class="save__future"><input type="checkbox" name=""> Save for future</label>
						</div>
						<div class="form-group">
							<div class="custom__btn">
								<button type="submit">Schedule Appointment</button>
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