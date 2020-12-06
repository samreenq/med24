@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.doctor-sidebar')
			</div>
			<div class="col-lg-9">
				<div class="overview__tabs__sec">
						<div class="inner__tab__body patient__experiance__sec">
							<h2>How likely would you Recommend Our services</h2>
							<div class="patient__status__box">
								<ul class="text-center">
									<li>Very Bad</li>
									<li>Satisfied</li>
									<li>Excellent</li>
								</ul>
							</div>
							<div class="additional__remark">
                                @include('site.layouts.partials.messages')
                                @if(isset($d_review->id))
                                    <fieldset class="rating">
                                        <?php $style = " "; ?>
                                        @for($i = 1; $i<=5; $i++)
                                            @if($d_review->rating != '')
                                                @if($d_review->rating <= $i)
                                                    <?php
                                                    $style = 'style="color:#ffc107"';
                                                    ?>
                                                @endif
                                            @endif
                                            <input  type="radio" id="star{!! $i !!}" name="rating" value="{!! $i !!}" /><label {!! $style !!} class = "full" for="star{!! $i !!}" title="{!! $i !!} stars"></label>
                                        @endfor
                                    </fieldset>
                                    <label>Additional Remarks</label>
                                    <textarea name="review" placeholder="Please write here...">{!! $d_review->review !!}</textarea>

                                @else
								<form method="post" action="{!! url('add-review/'.$doctor_id) !!}">
                                    {!! csrf_field() !!}
                                    <input name="doctor_id" type="hidden" value="{!! $doctor_id !!}" />
                                    <input name="rating" type="hidden" value="3" />
									<label>Additional Remarks</label>
									<textarea name="review" placeholder="Please write here..."></textarea>
									<div class="custom__btn">
										<button type="submit">Submit</button>
									</div>
								</form>
                                @endif
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
