@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
            <div class="col-lg-4">
                <div class="form__sec__box">
                    @include('site.search-form')
                </div>
            </div>
			<div class="col-lg-8">
				<div class="overview__tabs__sec">
                    @if(count($hospitals)>0)
                        <?php $i = 1; ?>
                        @foreach($hospitals as $key => $hospital)
                            @if($i%2 != 0)
                                <div class="affiliated__doctor__box">
                                    @endif
                                    <div class="inner__affiliated">
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
                                    @if($i%2 == 0 || $i == count($hospitals))
                                </div>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    @endif

                    {{ $paginator->appends($_GET)->links() }}

				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
