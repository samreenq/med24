<?php
namespace App\Http\Controllers\Api\Patient;
use App\Doctor;
use App\Hospital;
use App\Privacy;
use App\Terms;
use App\Faqs;
use App\Patient;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Doctor\DoctorDetails;
use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use Illuminate\Http\Request;
use DB;
use Auth;

class DashboardController extends ApiController{
    public function index(){
        $data = [
            'doctors'=>DoctorInfo::collection(Doctor::with('specialities','certifications')->where(['status' => 1])->limit(5)->get()),
            'hospitals'=>HospitalInfo::collection(Hospital::where('status', 1)->limit(5)->get())
        ];

        return $this->apiDataResponse($data);
    }

    function search(Request $request){
        $records = [];

        if($request->type == 'doctor'){
            $doctors = Doctor::with([
                            'specialities', 
                            'certifications',
                            'doctorTimeSlots'
                        ])
                        ->where([
                            'status' => 1
                        ]);

                        if(isset($request->keyword) && $request->keyword != null){
                $doctors->where('first_name', 'like', '%'.$request->keyword.'%');
                        }

                if(isset($request->inNetwork) && $request->inNetwork == 0){
                    if(isset($request->insurances) && count($request->insurances) > 0){
                        $doctors->whereHas('hospitals.hospital_insurances.insurance', function ($query) use($request){
                            $query->whereIn('id', $request->insurances);
                        });
                    }
                }else if(isset($request->inNetwork) && $request->inNetwork == 1){
                    if(isset(auth('api_patient')->user()->insurance_id)){
                        $doctors->whereHas('hospitals.hospital_insurances.insurance', function ($query) {
                            $query->where('id', auth('api_patient')->user()->insurance_id);
                        });
                    }
                }

                        if(isset($request->specialities) && count($request->specialities) > 0){
                $doctors->whereHas('specialities', function ($query) use($request){
                    $query->whereIn('doctor_specialities.speciality_id', $request->specialities);
                });
                        }

                        if(isset($request->hospitals) && count($request->hospitals) > 0){
                $doctors->WhereHas('hospitals', function ($query) use($request){
                    $query->whereIn('doctor_hospitals.hospital_id', $request->hospitals);
                });
                        }

                        if(isset($request->languages) && count($request->languages) > 0){
                $doctors->whereHas('languages', function ($query) use($request){
                    $query->whereIn('doctor_languages.language_id', $request->languages);
                });
                        }

                        if(isset($request->city) && $request->city != null){
                $doctors->where('city_id', $request->city);
                        }

                        if(isset($request->country) && $request->country != null){
                $doctors->where('country_id', $request->country);
                        }

                        if(isset($request->gender) && $request->gender != null){
                $doctors->where('gender', $request->gender);
                        }

                        if(isset($request->rating) && $request->rating != null){
                $doctors->whereHas('reviews', function ($query) use($request){
                    $query->having(DB::raw('avg(rating)'), '>=', $request->rating);
                });
                        }

                        if(isset($request->availableDate) && $request->availableDate != null){
                $doctors->whereHas('doctorTimeSlots', function ($query) use($request) {
                    $query->where('day', date('l', strtotime($request->availableDate)));
                });
                        }

                        if(isset($request->fromTime) && $request->fromTime != null){
                $doctors->whereHas('doctorTimeSlots', function ($query) use($request) {
                    $query->where('from', '>=', $request->fromTime.':00');
                });
                        }

                        if(isset($request->toTime) && $request->toTime != null){
                $doctors->whereHas('doctorTimeSlots', function ($query) use($request) {
                    $query->where('to', '<=', $request->toTime.':00');
                });
                        }

            if (isset($request->isFeatured) && $request->isFeatured == 1) 
            {
                $doctors->where('isFeatured', $request->isFeatured);            
            }

            $results = $doctors->limit($request->limit)
                            ->offset($request->offset)
                            ->get();

            if(count($results) > 0){
                $records = DoctorInfo::collection(
                    $results  
                ); 
            }
        }else if($request->type == 'hospital'){
            $hospitals = Hospital::where('status', 1);

                        if(isset($request->keyword) && $request->keyword != null){
                $hospitals->where('name', 'like', '%'.$request->keyword.'%');
                        }

                       

                        if(isset($request->specialities) && count($request->specialities) > 0){
                $hospitals->whereHas('specialities_hospitals', function ($query) use($request){
                    $query->whereIn('hospital_specialities.speciality_hospital_id', $request->specialities);
                });
                        }

                        if(isset($request->city) && $request->city != null){
                $hospitals->where('city_id', $request->city);
                        }

                        if(isset($request->country) && $request->country != null){
                $hospitals->where('country_id', $request->country);
                        }

                        if(isset($request->fromTime) && $request->fromTime != null){
                $hospitals->where('opening_time', '>=', $request->fromTime.':00');
                        }

                        if(isset($request->toTime) && $request->toTime != null){
                $hospitals->where('closing_time', '<=', $request->toTime.':00');
                        }

                if(isset($request->inNetwork) && $request->inNetwork == 0){
                    if(isset($request->insurances) && count($request->insurances) > 0){
                        $hospitals->whereHas('hospital_insurances.insurance', function ($query) use($request){
                            $query->whereIn('id', $request->insurances);
                        });
                    }
                }else if(isset($request->inNetwork) && $request->inNetwork == 1){
                    if(isset(auth('api_patient')->user()->insurance_id)){
                        $hospitals->whereHas('hospital_insurances.insurance', function ($query) {
                            $query->where('id', auth('api_patient')->user()->insurance_id);
                        });
                    }
                }

                        if(isset($request->rating) && $request->rating != null){
                $hospitals->whereHas('reviews', function ($query) use($request){
                    $query->having(DB::raw('avg(rating)'), '>=', $request->rating);
                });
                        }

            if (isset($request->isFeatured) && $request->isFeatured == 1) 
            {
                $hospitals->where('isFeatured', $request->isFeatured);            
            }

            $results = $hospitals->limit($request->limit)
                            ->offset($request->offset)
                            ->get();   

            if(count($results) > 0){
                $records = HospitalInfo::collection(
                    $results
                );
            }
        }

        if(count($records) > 0){
            return $this->apiSuccessResponse('success', $records);
        }

        return $this->apiSuccessResponse('success', $records);
    }

    public function privacyPolicy(){
        $privacyPolicy = Privacy::select('content')
                                ->where('type', 'patient')
                                ->first();

        return $this->apiSuccessResponse('success', $privacyPolicy);
    }

    public function faqs(){
        $faqs = Faqs::orderBy('created_at', 'desc')
                    ->where('type', 'patient')
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


    public function termsAndConditions(Request $request){
        $termsConditions = Terms::select('content')
                                ->where('type', 'patient')
                                ->first();

        return $this->apiSuccessResponse('success',$termsConditions);
    }
}
