@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.sidebar')
			</div>
			<div class="col-lg-9">
				<div class="left__panel__box change__password">
				  <div class="custom__heading">
				  	<h2>Change Password</h2>
				  </div>
					<div class="form__sec__box change__password__inner">
                        @include('site.layouts.partials.messages')
						<form method="post" action="{!! url('update-password') !!}">
                            <?php echo csrf_field(); ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label>Current Password</label>
										<input type="password" class="form-control" name="current_password">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>New Password</label>
										<input type="password" class="form-control" name="new_password">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label>Re-Type New Password</label>
										<input type="password" class="form-control" name="confirm_password">
									</div>
								</div>
							</div>
							<div class="row">
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
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
