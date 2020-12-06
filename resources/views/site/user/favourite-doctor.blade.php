@extends('site.layouts.index')
@section('content')
<section class="select__doctor__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="doctor__search__box heading__favourite__sec text-center">
					<h2>Favourite Doctors</h2>
				</div>
				<div class="row favourite__doctor__field">
					<div class="col-lg-6">
						<div class="doctor__search__box text-center">
							<div class="presc__search__form">
								<div class="form-group">
									<input type="text" name="" class="form-control search__field" placeholder="Search">
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="add__detail__form">
							<div class="form-group">
								<select class="selectpicker" name="fav-select" id="fav-select">
                                    <option value="doctor"><a href="{!! url('favourite-doctor') !!}" >Favorite Doctor</a></option>
                                    <option value="hospital">Favorite Hospital</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
            @if(count($doctors)>0)
                @foreach($doctors as $key => $doc)
			<div class="col-lg-4">
				<div class="inner__affiliated affiliated__select__doctor">
                    <div class="affiliated__heart">
                        <?php $style = (isset($doc->is_fav) && $doc->is_fav == 1) ? 'style="color:#F11122"' : 'style="color: #B7B7B7;font-size: 14px;"'; ?>
                        <a href="javascript:;" id="add-fav-doctor"><i {!! $style !!} class="fa fa-heart"></i></a>
                    </div>
                    <div class="affiliated__figure">
                        <a href="{!! url('doctor-profile/'.$doc->id) !!}">
                            @if (@getimagesize(asset('public/uploads/images/'.$doc->image)))
                                <img src="{!! asset('public/uploads/images/'.$doc->image) !!}" alt="" />
                            @else
                                <img src="images/profile-img.png" alt="" />
                            @endif
                        </a>

                    </div>
                    <div class="affiliated__doctor__bio">
                        <h4>{!! $doc->first_name.' '.$doc->last_name !!}</h4>
                        <span>{!! $doc->speciality !!}</span>
                        <a href="javascript:;">{!! $doc->hospital !!}</a>
                    </div>
                    <div class="custom__btn">
                        @if(isset($login_user->id))
                            <?php $url = url('book-appointment/doctor/'.$doc->id) ?>
                        @else
                            <?php $url = url('sign-in') ?>
                        @endif
                        <a href="{!! $url !!}">Book Me</a>
                    </div>
				</div>
			</div>
                @endforeach
            @endif

        </div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
