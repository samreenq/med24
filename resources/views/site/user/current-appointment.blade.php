@extends('site.layouts.index')
@section('content')
<section class="select__doctor__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="doctor__search__box heading__favourite__sec text-center">
					<h2>Current Appointments</h2>
                    <h5><a href="{!! url('past-appointment') !!}">Past Appointments</a></h5>
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
								<select class="selectpicker" id="appointment-select">
									<option selected value="{!! url('current-appointment') !!}">Current Appointments</option>
									<option value="{!! url('past-appointment') !!}">Past Appointments</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
            @if(count($appointments))
                @foreach($appointments as $appointment)
			<div class="col-lg-6">
				<div class="inner__affiliated favourite__hospital__list crt__apoint__box">
					<div class="status__box">
						<span>waiting</span>
					</div>
					<div class="affiliated__figure">
                        @if (@getimagesize(asset('public/uploads/images/'.$appointment->doctor->image)))
                        <img class="img-fluid" src="{!! asset('public/uploads/images/'.$appointment->doctor->image) !!}" alt="" />
                        @else
                            <img src="images/profile-img.png" alt="" />
                            @endif
					</div>
					<div class="affiliated__doctor__bio">
						<h4>{!! $appointment->doctor->first_name.' '.$appointment->doctor->last_name !!}</h4>
						<a href="javascript:;">{!! $appointment->hospital->name !!}</a>
						<span>Unitrust insurance</span>
					</div>
					<div class="schedule__box">
						<div class="date_format">
							<ul>
								<li><a href="javascript:"><i class="fa fa-clock-o"></i> {!! date('h:i A',strtotime($appointment->appointment_time)) !!}</a></li>
								<li><a href="javascript:"><i class="fa fa-calendar"></i> {!! date('d/m/Y',strtotime($appointment->appointment_date)) !!}</a></li>
							</ul>
						</div>
						<div class="link">
							<a href="{!! url('book-appointment/hospital/'.$appointment->hospital->id.'/doctor/'.$appointment->doctor->id) !!}">reschedule</a>
						</div>
					</div>
				</div>
			</div>
                @endforeach
				@endif
		</div>
        <div class="row">
            {{ $paginator->links() }}
        </div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
