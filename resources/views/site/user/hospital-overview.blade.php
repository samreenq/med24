@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec hospital__overview">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.second-sidebar')
			</div>
			<div class="col-lg-9">
				<div class="overview__tabs__sec">
					<ul class="tab__list">
						<li class="active" data-tag="one"><a href="javascript:;">Overview</a></li>
						<li data-tag="two"><a href="javascript:;">Reviews</a></li>
						<li data-tag="three"><a href="javascript:;">Affiliated Doctors</a></li>
						<li data-tag="four"><a href="javascript:;">Patient Experience</a></li>
					</ul>
					<div class="tab__body__sec" id="one">
						<div class="inner__tab__body">
							<h2>About</h2>
							<p>{!! $hospital->description !!}</p>
						</div>
						<div class="inner__tab__body">
							<h2>Specialities</h2>
							<ul>
                                @if(count($hospital->specialities_hospitals)>0)
                                    @foreach($hospital->specialities_hospitals as $sp)
                                        <li>{!! $sp->name !!}</li>
                                    @endforeach
                                @endif
							</ul>
						</div>
                        <div class="inner__tab__body certification__sec">
                            <h2>Board Certifications</h2>
                            <ul>
                                @if(count($hospital->certifications_hospitals)>0)
                                    @foreach($hospital->certifications_hospitals as $cert)
                                        <li>{!! $cert->name !!}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="inner__tab__body certification__sec">
                            <h2>Awards</h2>
                            <ul>
                                @if(count($hospital->awards_hospitals)>0)
                                    @foreach($hospital->awards_hospitals as $cert)
                                        <li>{!! $cert->name !!}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="inner__tab__body">
							<h2>Locations</h2>
							<div id="map">
                                <input type="hidden" name="latitude" value="<?php echo $hospital->latitude; ?>" id="latitude" />
                                <input type="hidden" name="longitude" value="<?php echo $hospital->longitude ?>" id="longitude" />
                                <div id="venue_map" style="height: 300px; position: relative; overflow: hidden;"></div>
								{{--<img class="img-fluid" src="images/map.png" alt="" />--}}
							</div>
						</div>
					</div>
					<div class="tab__body__sec hide" id="two">
                        @if(count($hospital->reviews)>0)
                            @foreach($hospital->reviews as $review)
                                <div class="main__review__box">
                                    <div class="review__box">
                                        <div class="inner__review__box">
                                            <div class="review__left__box">
                                                <div class="review__figure">
                                                    @if (@getimagesize(asset('public/uploads/images/'.$review->patient->image)))
                                                        <img src="{!! asset('public/uploads/images/'.$review->patient->image) !!}" alt="" />
                                                    @else
                                                        <img src="images/profile-img.png" alt="" />
                                                    @endif
                                                    <span class="current__status"></span>
                                                </div>
                                                <div class="review__doctor__bio">
                                                    <h4>{!! $review->patient->first_name.' '.$review->patient->last_name !!}</h4>
                                                    <fieldset class="rating">
                                                        <?php $style = " "; ?>
                                                        @for($i = 1; $i<=5; $i++)
                                                            @if($review->rating != '')
                                                                @if($review->rating <= $i)
                                                                    <?php
                                                                    $style = 'style="color:#ffc107"';
                                                                    ?>
                                                                @endif
                                                            @endif
                                                            <input  type="radio" id="star{!! $i !!}" name="rating" value="{!! $i !!}" /><label {!! $style !!} class = "full" for="star{!! $i !!}" title="{!! $i !!} stars"></label>
                                                        @endfor
                                                    </fieldset>
                                                    <ul>
                                                        <li>{!! date('d-m-Y',strtotime($review->created_at)) !!}</li>
                                                        <li>{!! convert($review->created_at) !!}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="review__right__box">
                                                <p>{!! $review->review !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment__box">
                                        <ul>
                                            @if(isset($login_user->id))
                                              @if($review->is_like>0)
                                                <li><i class="fa fa-thumbs-down"></i> <a href="{!! url('review-like/hospital_id/'.$hospital->id.'/id/'.$review->id.'/type/2') !!}" >Unlike</a></li>
                                                @else
                                                    <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('review-like/hospital_id/'.$hospital->id.'/id/'.$review->id.'/type/1') !!}" >Like</a></li>
                                            @endif
                                                    <li><i class="fa fa-comment-o"></i> <a href="javascript:void(0)" class="addReply" data-id="{!! $review->id !!}">Reply</a></li>
                                            @else
                                                <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('sign-in') !!}" >Like</a></li>
                                                <li><i class="fa fa-comment-o"></i> <a href="{!! url('sign-in') !!}" >Reply</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                @if(isset($review->replies))
                                    @foreach($review->replies as $reply)
                                        <?php
                                        if(isset($reply->patient)){
                                            $reply_user = $reply->patient;
                                            $name = $reply_user->first_name.' '.$reply_user->last_name;
                                        }
                                        elseif(isset($reply->doctor)){
                                            $reply_user = $reply->doctor;
                                            $name = $reply_user->first_name.' '.$reply_user->last_name;
                                        }
                                        elseif(isset($reply->hospital)){
                                            $reply_user = $reply->hospital;
                                            $name = $reply_user->name;
                                        }

                                        ?>
                                        <div class="main__review__box" style="margin-left:50px;">
                                            <div class="review__box">
                                                <div class="inner__review__box">
                                                    <div class="review__left__box">
                                                        <div class="review__figure">
                                                            @if (@getimagesize(asset('public/uploads/images/'.$reply_user->image)))
                                                                <img src="{!! asset('public/uploads/images/'.$reply_user->image) !!}" alt="" />
                                                            @else
                                                                <img src="images/profile-img.png" alt="" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="review__right__box">
                                                        <span><b>{!! $name !!}</b></span>
                                                        <p>{!! $reply->reply !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="comment__box">
                                                <ul>
                                                    <li>{!! convert($reply->created_at) !!}</li>
                                                    @if(isset($login_user->id))
                                                        @if($reply->is_like)
                                                        <li><i class="fa fa-thumbs-down"></i> <a href="{!! url('reply-like/hospital_id/'.$hospital->id.'/id/'.$review->id.'/reply_id/'.$reply->id.'/type/2') !!}" >Unlike</a></li>
                                                        @else
                                                        <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('reply-like/hospital_id/'.$hospital->id.'/id/'.$review->id.'/reply_id/'.$reply->id.'/type/1') !!}" >Like</a></li>
                                                        @endif
                                                        <li><i class="fa fa-comment-o"></i> <a href="javascript:void(0)" class="addReply" data-id="{!! $review->id !!}">Reply</a></li>
                                                    @else
                                                        <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('sign-in') !!}" >Like</a></li>
                                                        <li><i class="fa fa-comment-o"></i> <a href="{!! url('sign-in') !!}" >Reply</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif

                                <div class="additional__remark hide" id="remarks-{!! $review->id !!}" style="margin-left:50px;">
                                    <form method="post" action="{!! url('add-review-reply') !!}">
                                        {!! csrf_field() !!}
                                        <input name="hospital_id" type="hidden" value="{!! $hospital->id !!}" />
                                        <input name="review_id" type="hidden" value="{!! $review->id !!}" />
                                        <input name="reply_id" type="hidden" value="" />
                                        <textarea name="reply" placeholder="Please write here..."></textarea>
                                        <div class="custom__btn">
                                            <button type="submit">Submit</button>
                                        </div>
                                    </form>

                                </div>

                            @endforeach
                        @endif

					</div>
					<div class="tab__body__sec hide" id="three">
                        @if(count($hospital->doctors)>0)
                            <?php $i = 1; ?>
                            @foreach($hospital->doctors as $key => $doc)
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
									<a href="javascript:;">{!! $hospital->name !!}</a>
								</div>
								<div class="custom__btn">
                                    @if(isset($login_user->id))
                                        <?php $url = url('book-appointment/hospital/'.$hospital->id.'/doctor/'.$doc->id) ?>
                                    @else
                                        <?php $url = url('sign-in') ?>
                                    @endif
									<a href="{!! $url !!}">Book Me</a>
								</div>
							</div>

                                        @if($i%2 == 0 || $i == count($hospital->doctors))
                                    </div>
                                    @endif
                                    <?php $i++; ?>
                                @endforeach
                            @endif
					</div>
					<div class="tab__body__sec hide" id="four">
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
                                @if(isset($h_review->id))
                                    <fieldset class="rating">
                                        <?php $style = " "; ?>
                                        @for($i = 1; $i<=5; $i++)
                                            @if($h_review->rating != '')
                                                @if($h_review->rating <= $i)
                                                    <?php
                                                    $style = 'style="color:#ffc107"';
                                                    ?>
                                                @endif
                                            @endif
                                            <input  type="radio" id="star{!! $i !!}" name="rating" value="{!! $i !!}" /><label {!! $style !!} class = "full" for="star{!! $i !!}" title="{!! $i !!} stars"></label>
                                        @endfor
                                    </fieldset>
                                            <label>Additional Remarks</label>
                                            <textarea name="review" placeholder="Please write here...">{!! $h_review->review !!}</textarea>

                                    @else
								<form method="post" id="hospitalReviewForm" action="{!! url('add-hospital-review') !!}">
                                    <div class="alert alert-danger hide" id="errorWrap"></div>
                                    {!! csrf_field() !!}
                                    <input name="hospital_id" id="hospital_id" type="hidden" value="{!! $hospital->id !!}" />
                                    <input name="rating" type="hidden" value="3" />
									<label>Additional Remarks</label>
									<textarea name="review" placeholder="Please write here..."></textarea>
									<div class="custom__btn">
										<button class="hospital-review" type="button">Submit</button>
									</div>
								</form>
                                @endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
    $(document).ready(function(){
        //alert({!! $is_book !!})
    });

</script>
@endsection
{{-- @section('scripts')
@endsection --}}
