<?php

namespace App\Http\Controllers\Frontend;

use App\ContactUs;
use App\Doctor;
use App\Hospital;
use App\Http\Controllers\Controller;
use App\Offers;
use App\Options;
use App\Settings;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Frontend\WebController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class HomeController extends WebController
{
    public $_data = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->loggedInUser();
        $this->getSpeciality();

        //Get Featured Doctors
        $doctor_model = new Doctor();
        $this->_data['featured_doctors'] = $doctor_model->getFeaturedDoctors();

        $hospital_model = new Hospital();
        $this->_data['featured_hospitals'] = $hospital_model->getFeatured();

        $offers_model = new Offers();
        $this->_data['featured_offers'] = $offers_model->getFeatured();

        $options_model = new Options();
        $options = $options_model->where('page','home')->get();

        $return = array();
        if(count($options)>0){
            foreach($options as $record){

                $option = $record['option_name'];
                $return[$record['option_name']] = $record['option_value'];
            }
            $return = json_decode(json_encode($return));
        }
        $this->_data['search_title'] = isset($return->search_title) ? $return->search_title : '';

        $this->_data['options'] = $return;
       //echo '<pre>'; print_r($return); exit;
        return view('site.home',$this->_data);
    }

      public function change_password()
    {
        $this->loggedInUser();
        return view('site.user.change-password',$this->_data);
    }

      public function add_family()
    {
        $this->loggedInUser();
        return view('site.user.add-family',$this->_data);
    }
      public function health_record()
    {
        $this->loggedInUser();
        return view('site.user.health-record',$this->_data);
    }
      public function privacy__policy()
    {
        $this->loggedInUser();
        $this->_data['data'] = Settings::where('name', 'privacy_policy')->first();

        return view('site.privacy-policy',$this->_data);
    }
      public function term__condition()
    {
        $this->loggedInUser();
        $this->_data['data'] =  Settings::where('name', 'toc')->first();
        return view('site.term-and-condition',$this->_data);
    }

    public function aboutUs()
    {
        $this->loggedInUser();
        $this->_data['data'] =  Settings::where('name', 'about-us')->first();
        return view('site.about-us',$this->_data);
    }
    public function faq()
    {
        $this->loggedInUser();
        $this->_data['data'] =  Settings::where('name', 'faq')->first();
        return view('site.faq',$this->_data);
    }

      public function contactUs()
    {
        $this->loggedInUser();
        return view('site.contact-us',$this->_data);
    }

      public function doctor__profile()
    {
        $this->loggedInUser();
        return view('site.user.doctor-profile',$this->_data);
    }


    public function patient__experience()
    {
        $this->loggedInUser();
        return view('site.user.patient-experience',$this->_data);
    }

     public function special__offer()
    {
        $this->loggedInUser();
        return view('site.special-offer',$this->_data);
    }
     public function special__offer__description()
    {
        $this->loggedInUser();
        return view('site.special-offer-description',$this->_data);
    }
      public function select__doctor()
    {
        $this->loggedInUser();
        return view('site.user.select-doctor',$this->_data);
    }
      public function add__detail()
    {
        $this->loggedInUser();
        return view('site.user.add-detail',$this->_data);
    }
      public function select__family()
    {
        $this->loggedInUser();
        return view('site.user.select-family',$this->_data);
    }
       public function insurance__profile()
    {
        $this->loggedInUser();
        return view('site.user.insurance-profile',$this->_data);
    }
       public function favourite__doctor()
    {
        $this->loggedInUser();
        return view('site.user.favourite-doctor',$this->_data);
    }
       public function favourite__hospital()
    {
        $this->loggedInUser();
        return view('site.user.favourite-hospital',$this->_data);
    }

    public function search(Request $request)
    {
        $this->loggedInUser();
        $this->getSpeciality();

        //echo '<pre>'; print_r($request->all()); exit;

        $user_id = false;
        if(Auth::guard('user')->check()){
            $user_id = Auth::guard('user')->user()->id;
        }

        if($request->search_type == 'hospital'){
            $hospital_model = new Hospital();
            $hospitals = $hospital_model->getAll($user_id,$request);
           // echo '<pre>'; print_r($hospitals);
            $this->_data['hospitals'] = json_decode(json_encode($hospitals['hospitals']));

            $pagination = $hospitals['pagination'];

            if(isset($pagination)) {
                $this->_data['paginator'] = $paginator = new LengthAwarePaginator($hospitals['hospitals'], $pagination['total'],
                    $pagination['per_page'], $pagination['current_page']
                    , ['path' => url('search')]);
            }

            $option = Options::where('option_name','search_title')->first();
            $this->_data['search_title'] = isset($option->option_value) ? $option->option_value : '';

            return view('site.user.hospital-list',$this->_data);
        }
        else{
            $doctor_model = new Doctor();
            $doctors = $doctor_model->getAll($user_id,$request);
            $this->_data['doctors'] = json_decode(json_encode($doctors['doctors']));

            $pagination = $doctors['pagination'];

            if(isset($pagination)) {
                $this->_data['paginator'] = $paginator = new LengthAwarePaginator($doctors['doctors'], $pagination['total'],
                    $pagination['per_page'], $pagination['current_page']
                    , ['path' => url('search')]);
            }

            $option = Options::where('option_name','search_title')->first();
            $this->_data['search_title'] = isset($option->option_value) ? $option->option_value : '';
            //echo '<pre>'; print_r($this->_data); exit;
            return view('site.user.doctor-list',$this->_data);
        }


    }

    public function submitContactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('contact-us')->withInput()->withErrors($validator->errors());
        }

        $contact_model = new ContactUs();
        $contact_model->first_name = $request->first_name;
        $contact_model->last_name = $request->last_name;
        $contact_model->email = $request->email;
        $contact_model->subject = $request->subject;
        $contact_model->description = $request->description;
        $contact_model->save();

        $data = $request->all();

        try {
            Mail::send('emailTemplates.contactUs', $data, function ($message) use ($data) {
                $message->to(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))->subject($data['subject']);
                $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
            });
        } catch (\Exception $ex) {
            return redirect('contact-us')->withInput()->with('error', $ex->getMessage());
        }

        return redirect('contact-us')->with('success','Email has sent successfully');
    }


}
