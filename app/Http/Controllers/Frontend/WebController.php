<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Speciality;
use Illuminate\Support\Facades\Auth;

Class WebController extends Controller
{
    public $_data;

    public function __construct()
    {
        $this->_data = [];
    }

    /**
     *
     */
    public function loggedInUser()
    {
        $this->_data['login_user'] = Auth::guard('user')->user();
    }

    public function getSpeciality()
    {
        $specialities_model = new Speciality();
        $speciality = $specialities_model->where('status',1)->get();
        $speciality = translateArray($speciality->toArray());
        $this->_data['speciality'] = json_decode(json_encode($speciality));
        //echo '<pre>'; print_r($speciality); exit;
    }



}
