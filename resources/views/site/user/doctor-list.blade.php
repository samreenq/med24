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
                    @if(count($doctors)>0)
                        <?php $i = 1; ?>
                        @foreach($doctors as $key => $doc)
                            @if($i%2 != 0)
                                <div class="affiliated__doctor__box">
                                    @endif
                                    <div class="inner__affiliated">
                                        <div class="affiliated__heart">
                                            <?php $style = (isset($doc->is_fav) && $doc->is_fav == 1) ? 'style="color:#F11122"' : 'style="color: #B7B7B7;font-size: 14px;"'; ?>
                                            <a href="javascript:;" id="add-fav-doctor"><i {!! $style !!} class="fa fa-heart"></i></a>
                                        </div>
                                        <div class="affiliated__figure">
                                            <a href="{!! url('doctor-profile/'.$doc->id) !!}">
                                                @if (@getimagesize($doc->image))
                                                    <img src="{!! asset($doc->image) !!}" alt="" />
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

                                    @if($i%2 == 0 || $i == count($doctors))
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
