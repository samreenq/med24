<div class="sidebar__sec">
	<div class="profile__figure">
		<img class="img-fluid" src="{!! $hospital->image !!}" alt="" />
        <input type="hidden" id="hospital_id" name="hospital_id" value="{!! $hospital->id !!}">
		<div class="hospital__bio">
			<h3>{!! $hospital->name !!}</h3>
			<p>Medical Pharma</p>
			<div class="rating__star">
				<fieldset class="rating">
                    <?php $style = " "; ?>
                    @for($i = 1; $i<=5; $i++)
                        @if($hospital->AverageRating != '')
                            @if($hospital->AverageRating <= $i)
                                <?php
                                $style = 'style="color:#ffc107"';
                                ?>
                            @endif
                        @endif
                        <input  type="radio" id="star{!! $i !!}" name="rating" value="{!! $i !!}" /><label {!! $style !!} class = "full" for="star{!! $i !!}" title="{!! $i !!} stars"></label>
                    @endfor
					{{--<input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
					<input type="radio" id="star4half" name="rating" value="4 and a half" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
					<input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
					<input type="radio" id="star3half" name="rating" value="3 and a half" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
					<input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
					<input type="radio" id="star2half" name="rating" value="2 and a half" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
					<input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
					<input type="radio" id="star1half" name="rating" value="1 and a half" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
					<input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
					<input type="radio" id="starhalf" name="rating" value="half" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
				--}}
                </fieldset>
				<span>({!! count($hospital->reviews) !!} Review)</span>
			</div>
			<div class="fav__box">
                @if(isset($login_user->id))
                    <?php
                    $style = (isset($is_fav) && $is_fav == 1) ? 'style="color:#F11122"' : 'style="color: #B7B7B7;"';

                    if((isset($is_fav) && $is_fav == 1)){
                        $text = "Added to Favorite";
                        $type = 2;
                    }
                    else{
                        $text = "Add to Favorite";
                        $type = 1;
                    }

                    ?>
                    <a href="javascript:;" id="add-fav-hospital" data-type="{!! $type !!}">
                        <i {!! $style !!} class="fa fa-heart"></i> {!! $text !!}</a>
                @else
                    <a href="{!! url('sign-in') !!}" ><i  class="fa fa-heart-o"></i> Add to Favorite</a>
                @endif
			</div>
		</div>
	</div>
</div>
