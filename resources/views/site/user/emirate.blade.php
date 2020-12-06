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
					<div class="edit__profile__box emirate__id">
						<img class="img-fluid" src="images/emirate-img.jpg" alt="" />
					</div>
                    <div class="form__sec__box change__password__inner">
                        @include('site.layouts.partials.messages')
                        <form method="post" action="{!! url('upload-emirate') !!}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Upload Card Front</label>
                                        @if(!empty($login_user->id_card_front))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->id_card_front) !!}" width="170px" height="170px" alt="" />
                                        @endif
                                        <input type="file" name="id_card_front" id="id_card_front" value="Upload" />
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Upload Card Back</label>
                                        @if(!empty($login_user->id_card_back))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$login_user->id_card_back) !!}" width="170px" height="170px" alt="" />
                                        @endif
                                        <input type="file" name="id_card_back" id="id_card_back" value="Upload" />
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="custom__btn">
                                            <button type="submit">Save Changes</button>
                                            {{--<a href="javascript:;">Save Changes</a>--}}
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
