@extends('site.layouts.index')
@section('content')
<section class="privacy__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading">
					<h1>Verify OTP</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="contactus__form">
                    @include('site.layouts.partials.messages')
					<form method="post" action="{!! url('verify-email') !!}">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="user_id" value="{!! $user_id !!}">
						<div class="form-group">
							<label>Enter Code</label>
							<input type="text" class="form-control" name="otp">
						</div>
						<div class="form-group">
						  <div class="custom__btn">
						  	<button type="submit">Submit</button>
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
