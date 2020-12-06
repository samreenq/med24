<?php
namespace App\Http\Controllers\Admin\Doctors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Review;
use Illuminate\Support\Facades\Validator;

class DoctorsReviewsController extends Controller{
	public $get_name = 'review';

	function __construct(){
        $this->middleware('auth');
    }
	
	function get_title($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1).'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title.'es';
		}else{
			$title = $title.'s';
		}

		return str_replace(['-', '_'], ' ', $title);
	}

    function index(){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
        $data['reviews'] = Review::with([
        							'doctor',
        							'patient',
        						 ])
        						 ->orderBy('created_at', 'desc')
						 	  	 ->get();

        return view('admin.doctor.'.$data['title'].'.index', $data);
    }
}