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
                    @if(count($reviews)>0)
                        @foreach($reviews as $review)
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
                                    @if($review->is_like >0)
                                        <li><i class="fa fa-thumbs-down"></i> <a href="{!! url('review-like/doctor_id/'.$doctor_id.'/id/'.$review->id.'/type/2') !!}" >Unlike</a></li>
                                    @else
                                        <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('review-like/doctor_id/'.$doctor_id.'/id/'.$review->id.'/type/1') !!}" >Like</a></li>
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
                                        if(isset($reply->patient) ){
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

                                                        @if($reply->is_like >0)
                                                            <li><i class="fa fa-thumbs-down"></i> <a href="{!! url('reply-like/doctor_id/'.$doctor_id.'/id/'.$review->id.'/reply_id/'.$reply->id.'/type/2') !!}" >Unlike</a></li>
                                                            @else
                                                                <li><i class="fa fa-thumbs-up"></i> <a href="{!! url('reply-like/doctor_id/'.$doctor_id.'/id/'.$review->id.'/reply_id/'.$reply->id.'/type/1') !!}" >Like</a></li>
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
                                    <input name="doctor_id" type="hidden" value="{!! $doctor_id !!}" />
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

			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
