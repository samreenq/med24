@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.sidebar')
			</div>
			<div class="col-lg-8 offset-1">
				<div class="left__panel__box change__password">
					<div class="custom__heading">
						<h2>Add Family</h2>
					</div>
					<div class="form__sec__box">
                        @include('site.layouts.partials.messages')
						<form method="post" action="{!! url('add-family-member') !!}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
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
										<label>Relation</label>
                                        <select name="relation" class="selectpicker">
                                            @if(count($relations)>0)
                                                @foreach($relations as $relation)
                                                    <option value="{!! $relation['name'] !!}">{!! $relation['name'] !!}</option>
                                                @endforeach
                                                    @endif
                                        </select>
									</div>
								</div>

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


							{{--<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
									 <img class="img-fluid" src="images/attacment-img.png" alt="" />
									</div>
								</div>
							</div>--}}

                            {{--Emirates Card--}}
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Emirate Card Front</label>
                                        {{--@if(!empty($login_user->id_card_front))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->id_card_front) !!}" width="170px" height="170px" alt="" />
                                        @endif--}}
                                        <input type="file" name="id_card_front" id="id_card_front" value="Upload" />
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Emirate Card Back</label>
                                        {{--@if(!empty($login_user->id_card_back))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->id_card_back) !!}" width="170px" height="170px" alt="" />
                                        @endif--}}
                                        <input type="file" name="id_card_back" id="id_card_back" value="Upload" />
                                    </div>
                                </div>
                            </div>
                                {{--Insurance Card--}}
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Insurance Card Front</label>
                                       {{--@if(!empty($login_user->insurance_id_front))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->insurance_id_front) !!}" width="170px" height="170px" alt="" />
                                        @endif--}}
                                        <input type="file" name="insurance_id_front" id="insurance_id_front" value="Upload" />
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Insurance Card Back</label>
                                        {{--@if(!empty($login_user->insurance_id_back))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->insurance_id_back) !!}" width="170px" height="170px" alt="" />
                                        @endif--}}
                                        <input type="file" name="insurance_id_back" id="insurance_id_back" value="Upload" />
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
