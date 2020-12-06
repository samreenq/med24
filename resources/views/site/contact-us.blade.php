@extends('site.layouts.index')
@section('content')
<section class="privacy__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading">
					<h1>Contact Us</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="contactus__form">
                    @include('site.layouts.partials.messages')
					<form method="post" action="{!! url('submit-contact-us') !!}">
                        {!! csrf_field() !!}
						<div class="form-group">
							<label>First Name</label>
							<input type="text" class="form-control" name="first_name" />
						</div>
						<div class="form-group">
							<label>Last Name</label>
							<input type="text" class="form-control" name="last_name" />
						</div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" />
                        </div>
						<div class="form-group">
							<label>Subject</label>
							<input type="text" class="form-control" name="subject" />
						</div>
						<div class="form-group">
							<label>additional information</label>
							<textarea class="form-control" name="description"></textarea>
						</div>
						<div class="form-group">
						  <div class="custom__btn">
						  	<button value="">Submit</button>
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
