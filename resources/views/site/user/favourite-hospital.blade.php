@extends('site.layouts.index')
@section('content')
<section class="select__doctor__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="doctor__search__box heading__favourite__sec text-center">
					<h2>Favourite Hospitals</h2>
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
								<select class="selectpicker">
									<option>Favorite Hospitals</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
            @if(count($hospitals)>0)
                @foreach($hospitals as $key => $hospital)
			<div class="col-lg-4">
				<div class="inner__affiliated favourite__hospital__list">
                    <div class="affiliated__heart">
                        <?php $style = (isset($hospital->is_fav) && $hospital->is_fav == 1) ? 'style="color:#F11122"' : 'style="color: #B7B7B7;font-size: 14px;"'; ?>
                        <a href="javascript:;" id="add-fav-doctor"><i {!! $style !!} class="fa fa-heart"></i></a>
                    </div>
                    <div class="affiliated__figure">
                        <a href="{!! url('hospital/'.$hospital->id) !!}">
                            @if (@getimagesize(asset('public/uploads/images/'.$hospital->image)))
                                <img src="{!! asset('public/uploads/images/'.$hospital->image) !!}" alt="" />
                            @else
                                <img src="images/profile-img.png" alt="" />
                            @endif
                        </a>
                    </div>
                    <div class="affiliated__doctor__bio">
                        <h4>{!! $hospital->name !!}</h4>
                        <span>{!! $hospital->speciality !!}</span>
                        {{--<a href="javascript:;">Mediclinic Welcare Hospital</a>--}}
                        <div class="timing__box">
                            <ul>
                                <li><span>Open</span> {!! date('h:i a',strtotime($hospital->opening_time)) !!}</li>
                                <li><span>Close</span> {!! date('h:i a',strtotime($hospital->closing_time)) !!}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="custom__btn">
                        @if(isset($login_user->id))
                            <?php $url = url('book-appointment/hospital/'.$hospital->id) ?>
                        @else
                            <?php $url = url('sign-in') ?>
                        @endif
                        <a href="{!! $url !!}">Schedule appointment</a>
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
