@extends('site.layouts.index')
@section('content')
<section class="privacy__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading">
					<h1>Login</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
                <div class="btn__box">
                <div class="custom__btn">
                    <a href="{!! url('signup') !!}">Create Account</a>
                </div>
                <div class="custom__btn">
                    <a href="{{url('login/facebook')}}">Login with Facebook</a>
                </div>
                    <div class="custom__btn">
                        <a href="{{url('login/google')}}">Login with Google</a>
                    </div>
                </div>
				<div class="contactus__form">
                    @include('site.layouts.partials.messages')
					<form method="post" action="{!! url('login-submit') !!}">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control" name="email">
						</div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password">
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
