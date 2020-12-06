@extends('site.layouts.index')
@section('content')
<section class="select__doctor__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 offset-3">
				<div class="doctor__search__box text-center">
					<h2>Add Details</h2>
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
								<option>Select Family</option>
								<option>Ketchup</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
								<option>Barbecue</option>
							</select>
						</div>
						<div class="form-group">
							<select class="selectpicker">
								<option>Select Insurance</option>
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
							<label class="additional__label">Additional Remarks</label>
							<textarea class="form-control" placeholder="Please write here..."></textarea>
						</div>
						<div class="form-group">
							<div class="custom__btn">
								<button type="submit">Proceed</button>
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