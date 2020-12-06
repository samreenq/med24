<?php
namespace App\Http\Controllers\Api\Doctor;
use App\Http\Controllers\Controller;
use App\Appointment;
use App\Doctor;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Appointment\AppointmentResource;
use App\Http\Resources\Api\Doctor\Certifications;
use App\Http\Resources\Api\Doctor\DoctorProfile;
use App\Http\Resources\Api\Doctor\Specialities;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use App\Http\Resources\Api\ReviewResource;
use App\Review;
use App\Privacy;
use App\Terms;
use App\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ApiController{
    public function index(){
        $doctors=Doctor::with('languages','specialities','certifications','hospitals')
            ->where('id',auth()->user()->id)->first();
        $current_appointments=Appointment::with('hospital','patient')->whereIn('status',['pending','scheduled'])->where('doctor_id',Auth::user()->id)->limit(5)->latest()->get();
        $past_appointments=Appointment::with('hospital','patient')->whereIn('status',['completed','cancelled','rejected'])->where('doctor_id',Auth::user()->id)->limit(5)->orderBy('id','ASC')->get();
        $reviews=Review::with('replies.likes','likes','hospital','doctor')->where('doctor_id',Auth::user()->id)->limit(5)->get();
        $profile=[
            'profile'=>(new DoctorProfile($doctors)),
            'appointments'=>[
                'current'=>AppointmentResource::collection($current_appointments),
                'past'=>AppointmentResource::collection($past_appointments),
            ],
            'overview'=>[
                'about'=>$doctors->about,
                'languages'=>$doctors->languages->map(function ($q){
                    return[
                        'id'=>$q->id,
                        'name'=>$q->name
                    ];
                }),
                'biography'=>$doctors->biography,
                'summary'=>$doctors->summary,
                'care_philosophy'=>$doctors->care_philosophy,
                'specialities'=>Specialities::collection($doctors->specialities),
                'certifications'=>Certifications::collection($doctors->certifications),
            ],
            'locations'=>$doctors->hospitals->map(function($q){
                return[
                    'latitude'=>$q->latitude,
                    'longitude'=>$q->longitude
                ];
            }),
            'reviews'=>ReviewResource::collection($reviews),
            'insurance'=>[
                'name'=>'Health insurance',
                'image'=>asset('public/uploads/images/insurance.jpg'),
                'address'=>'Enim vero eligendi sequi labore. Repellendus quibusdam ',
                'phone_no'=>'+123 34124356'
            ],
            'affiliated_hospitals'=>HospitalInfo::collection($doctors->hospitals),
        ];
        return response()->json(['status'=>1,'api_status'=>200,'message'=>'hello','data'=>$profile]);
    }

    public function privacyPolicy(){
        $privacyPolicy = Privacy::select('content')
                            ->where('type', 'doctor')
                            ->first();

        return $this->apiSuccessResponse('success', $privacyPolicy->content ?? "");
    }

    public function termsAndConditions(Request $request){
        $termsConditions = Terms::select('content')
                            ->where('type', 'doctor')
                            ->first();

        return $this->apiSuccessResponse('success', $termsConditions->content ?? "");
    }

    public function faqs(){
        $faqs = Faqs::where('type', 'doctor')
                                ->get(); 

        $filterFaqs = [];
        
        if(count($faqs) > 0){
            foreach($faqs as $key => $value){
                $filterFaq = json_decode($value->faq);

                foreach($filterFaq->questions as $key => $question){
                    $filterFaqs[] = [
                        'question' => $question,
                        'answer' => $filterFaq->answers[$key]
                    ];
                }
            } 
        }

        return $this->apiSuccessResponse('success', $filterFaqs);
    }
}