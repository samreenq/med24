<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\ReplyResource;
use App\Reply;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends ApiController
{
    //

    public function review(Request $request){
        $this->validate($request,[
            'review'=>'required|min:6',
        ]);
        $review=new Review();
        $request->doctor_id=Auth::user()->id;
        $request->added_by='Doctor';
        $review->store($request);
        return $this->apiSuccessResponse('review.saved');
    }


    public function reply(Request $request){
        $this->validate($request,[
            'review_id'=>'required',
            'reply'=>'required'
        ]);
        $reply=new Reply();
        $request->doctor_id=Auth::user()->id;
        $request->hospital_id=0;
        $reply->store($request);
        $data=(new ReplyResource($reply))->resolve();
        return $this->apiDataResponse($data);
    }


    public function like(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'type'=>'required|in:review,reply'
        ]);
        if($request->type=='review'){
            $review=Review::find($request->id);
            if($review){
                $review->doctorLikes()->toggle(Auth::user()->id);
                return $this->apiSuccessResponse('success');
            }
            return $this->apiErrorResponse('Invalid Record');
        }elseif ($request->type=='reply'){
            $reply=Reply::find($request->id);
            if($reply){
                $reply->doctorLikes()->toggle(Auth::user()->id);
                return $this->apiSuccessResponse('success');
            }
            return $this->apiErrorResponse('Invalid Record');
        }
    }
}
