<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Country;
use App\City;

class AjaxController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }

    function getCity(Request $request){
    	$country = Country::find($request->countryId);

    	$data = [];
    	$message = "Invalid country id";
    	$status = 0;

    	if($country){
    		$data = [];
	    	$message = "No cities found";
	    	$status = 0;

	    	$cities = City::where('country_id', $country->code)
	    				  ->get();
			
			if(count($cities) > 0){
				$data = $cities;
		  		$message = "Cities found";
		  		$status = 1;
		  	}
	  	}

	  	return response()->json([
			'message' => $message,
			'data' => $data,
			'status' => $status,
		], 200);
    }
}