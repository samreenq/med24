@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
            <div class="col-lg-12">
                <div class="custom__heading">
                    <h1>Register</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="contactus__form">
                    @include('site.layouts.partials.messages')
						<form method="post" action="{!! url('signup-submit') !!}">
                            <?php echo csrf_field(); ?>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label>First Name</label>
										<input type="text" class="form-control" name="first_name">
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label>Last Name</label>
										<input type="text" class="form-control" name="last_name">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label>Email Address</label>
										<input type="text" class="form-control" name="email">
									</div>
								</div>
							    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" name="phone_code">
                                        <input type="text" class="form-control" name="phone_no">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="selectpicker">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<div class="custom__btn">
                                            <button type="submit">Submit</button>
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
