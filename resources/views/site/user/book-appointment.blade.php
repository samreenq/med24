@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
            <div class="col-lg-12">
                <div class="doctor__search__box heading__favourite__sec text-center">
                    <h2>Book Appointment</h2>
                </div>
            </div>
			<div class="col-lg-6">
				<div class="overview__tabs__sec">
                    @include('site.layouts.partials.messages')
                    <form method="post" action="{!! url('save-appointment') !!}">
                        <?php echo csrf_field(); ?>

                             @if(isset($doctor->hospitals))
                                <div class="form-group">
                                    <label>Select Hospital</label>
                                        <select name="hospital_id" class="selectpicker">
                                            @foreach($doctor->hospitals as $hospital)
                                                <option value="{!! $hospital->id !!}">{!! $hospital->name !!}</option>
                                            @endforeach
                                        </select>
                                </div>
                            @else
                                <input type="hidden" name="hospital_id" value="{!! $hospital_id !!}">
                            @endif

                            @if(isset($hospital->doctors))
                                <div class="form-group">
                                    <label>Select Doctor</label>
                                    <select name="doctor_id" class="selectpicker">
                                        @foreach($hospital->doctors as $doctor)
                                            <option value="{!! $doctor->id !!}">{!! $doctor->first_name.' '.$doctor->last_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="doctor_id" value="{!! $doctor_id !!}">

                            @endif

                            @if(isset($family_members))
                                <div class="form-group">
                                    <label>Select Family Member</label>
                                    <select name="family_member_id" class="selectpicker">
                                        <option value="">-Select-</option>
                                        @foreach($family_members as $member)
                                            <option value="{!! $member->id !!}">{!! $member->first_name.' '.$member->last_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(isset($insurances))
                                <div class="form-group">
                                    <label>Select Insurance</label>
                                    <select name="insurance_id" class="selectpicker">
                                        <option value="">-Select-</option>
                                        @foreach($insurances as $insurance)
                                            <option value="{!! $insurance->id !!}">{!! $insurance->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group">
                            <label>Select Date</label>
                            <input type="text" class="form-control" name="appointment_date" id="datepicker">
                        </div>
                            <div class="form-group">
                                <label>Select Time</label>
                                {{--<input type="text" class="form-control" id="timepicker1" name="appointment_time">--}}
                                <div class="input-group bootstrap-timepicker timepicker">
                                    <input id="timepicker1" type="text" class="form-control input-small" name="appointment_time">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Extra Details</label>
                                <textarea class="form-control" rows="10" name="extraDetails"></textarea>
                            </div>

                        <div class="form-group">
                            <div class="custom__btn">
                                <button type="submit">Submit</button>
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
