@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.sidebar')
			</div>
			<div class="col-lg-9">
				<div class="left__panel__box">
                    <form method="post" action="{!! url('update-profile') !!}" enctype="multipart/form-data">
                        {{ csrf_field() }}
					<div class="edit__profile__box">
                        @if(empty($login_user->image))
                            <img class="img-fluid" src="images/profile-img.png" alt="" />
                       @else
                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->image) !!}" width="170px" height="170px" alt="" />
                            @endif
                            <input type="file" name="image" id="image" value="Change Picture" />
						{{--<a href="javascript:;">Change Picture</a>--}}
					</div>
					<div class="form__sec__box">
                        @include('site.layouts.partials.messages')

                            <input type="hidden" name="patient_id" value="{!! $login_user->id !!}">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label>First Name</label>
										<input type="text" class="form-control" name="first_name" value="{!! $login_user->first_name !!}" >
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label>Last Name</label>
										<input type="text" class="form-control" name="last_name" value="{!! $login_user->last_name !!}">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label>Email Address</label>
										<input type="text" class="form-control" name="email" value="{!! $login_user->email !!}">
									</div>
								</div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" name="phone_code" value="{!! $login_user->phone_code !!}">
                                        <input type="text" class="form-control" name="phone_no" value="{!! $login_user->phone_no !!}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="selectpicker">
                                            <option @if($login_user->gender == 'male') selected @endif value="male">Male</option>
                                            <option @if($login_user->gender == 'female') selected @endif value="female">Female</option>
                                        </select>
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
