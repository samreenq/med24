<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\ReplyResource;
use App\Http\Resources\Api\ReviewResource;
use App\Reply;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends ApiController
{
    public function index(Request $request){
        $reviews=Review::with('replies','doctor','hospital','patient')
            ->where('patient_id',Auth::user()->id)
            ->skip($request->offset)->take($request->limit)->get();
        $reviews=ReviewResource::collection($reviews);

        if (count($reviews) > 0)
        {
            return $this->apiResponse('success', 1, $reviews, 200);
        }
        else
        {
            return $this->apiResponse('success', 1, [], 200);
        }
    }

    public function review(Request $request){
        $this->validate($request,[
            'review'=>'required',
        ]);


        $review = Review::where([
            'added_by' => 'Patient',
            'patient_id' => Auth::user()->id,
        ]);
        
        if($request->doctor_id != null){
            $review->where('doctor_id', $request->doctor_id);
        }else if($request->hospital_id != null){
            $review->where('hospital_id', $request->hospital_id);
        }

        $review = $review->first();
        
        if($review){
            return $this->apiErrorResponse('Review has already added');
        }

        $review=new Review();
        $request->patient_id=Auth::user()->id;
        $request->added_by='Patient';
        $review->store($request);
        return $this->apiSuccessResponse('review.saved');
    }

    public function reply(Request $request){
        $this->validate($request,[
            'review_id'=>'required',
            'reply'=>'required'
        ]);

        $review=Review::find($request->review_id);
        if(!$review){
            return $this->apiErrorResponse('Invalid review ID');
        }
        $reply=new Reply();
        $request->patient_id=Auth::user()->id;
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
                $review->patient_likes()->toggle(Auth::user()->id);
                return $this->apiSuccessResponse('success');
            }
            return $this->apiErrorResponse('Invalid Record');
        }elseif ($request->type=='reply'){
            $reply=Reply::find($request->id);
            if($reply){
                $reply->patientLikes()->toggle(Auth::user()->id);
                return $this->apiSuccessResponse('success');
            }
            return $this->apiErrorResponse('Invalid Record');
        }
    }

    public function viewReplies(Request $request)
    {
        $request->validate([
            'reviewId' => 'required|exists:reviews,id',
        ]);

        $review = Review::with([
            'replies' => function ($query)
            {
                $query->orderBy('created_at', 'desc');
            }
        ])
        ->where('id', $request->reviewId)
        ->first();

        if (!$review)
        {
            return $this->apiErrorResponse('Invalid review id');
        }

        $records = [];

        if (count($review->replies) > 0) 
        {
            foreach ($review->replies as $key => $value)
            {
                $isLiked = 0;

                if (auth()->guard('api_patient')->user()) 
                {
                    $record = $value->likes->where('patient_id', auth()->guard('api_patient')->user()->id)
                    ->first();

                    if ($record) 
                    {
                        $isLiked = 1;
                    }
                }
                
                if (isset($value->doctor))
                {
                    $name = $value->doctor->first_name.' '.$value->doctor->last_name;
                    $image = asset('public/uploads/images/'.$value->doctor->image);
                } 
                elseif (isset($value->patient))
                {
                    $name = $value->patient->first_name.' '.$value->patient->last_name;
                    $image = asset('public/uploads/images/'.$value->patient->image);
                }
                elseif (isset($value->hospital))
                {
                    $name = asset('public/uploads/images/'.$value->hospital->logo);
                    $image = $value->hospital->name;
                }

                $records[] = [
                    'id' => $value->id,
                    'review_id' => $value->review_id,
                    'reply' => $value->reply,
                    'date' => Carbon::parse($value->created_at)->diffForHumans(),
                    'total_likes' => $value->likes->count(),
                    'is_liked' => $isLiked,
                    'replier_name' => $name,
                    'replier_image' => $image
                ];
            }
        }

        return $this->apiDataResponse($records);
    }
}