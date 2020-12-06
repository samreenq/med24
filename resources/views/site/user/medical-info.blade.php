@extends('site.layouts.index')
@section('content')
    <section class="dashboard__sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('site.user.sidebar')
                </div>
                <div class="col-lg-8 offset-1">
				<div class="inner__presc__box">
					<div class="presc__search__box">
						<h4>Medical Info</h4>
						<div class="add__new">
							<a href="{!! url('add-medical-info') !!}"><i class="fa fa-plus"></i> Add New</a>
						</div>
						<div class="presc__search__form">
                            @include('site.layouts.partials.messages')
							<form method="" action="">
								<div class="form-group">
									<input type="text" name="" class="form-control search__field" placeholder="Search by Title">
								</div>
								<div class="form-group date__field">
									<input type="text" name="" class="form-control calendar" placeholder="Date Select">
								</div>
							</form>
						</div>

                            @if(count($record)>0)
                            <?php $size = count($record); ?>
                                @foreach($record as $key => $val)
                                    <?php if($key%2==0){ ?>
                                        <div class="mein__prescriptions__group">
                                    <?php } ?>

							<div class="prescriptions__group">
								<div class="prescriptions__left">
									<div class="prescriptions__figure">
                                        @if(!empty($val->attachment))
                                            <img class="img-fluid" src="{!! asset('public/uploads/images/'.$val->attachment) !!}" alt="" />
                                        @else
                                            <img src="images/presc-img.png" alt="" />
                                        @endif

									</div>
									<div class="prescriptions__bio">
										<h5>{!! $val->title !!}</h5>
										<a href="javascript:;">Diabetes Medicine</a>
										<p>{!! $val->description !!}</p>
									</div>
								</div>
								<div class="prescriptions__right">
									<div class="prefix__date">
										<span><i class="fa fa-calendar"></i> {!! date('d/m/Y',strtotime($val->created_at)) !!}</span>
									</div>
                                    {{--<div class="delivered__btn">
                                        <a href="javascript:;">Delivered</a>
									</div>--}}
								</div>
							</div>
                                        <?php if($key%2==1 || $key == $size-1){ ?>
                                            </div>
                                            <?php  }  ?>
                                @endforeach
                                @endif

					</div>
                    {{ $record->links() }}

				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
