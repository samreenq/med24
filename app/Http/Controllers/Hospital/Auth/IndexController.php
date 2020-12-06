<?php
namespace App\Http\Controllers\Hospital\Auth;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use App\Hospital;

class IndexController extends Controller{
	function index(){
    	if(Auth::guard('hospital')->user()->id){
			return redirect()->route('admin.dashboard.index');
		}else{
			return redirect()->route('hospital.auth.signIn')->with('error', 'You need to sign in first.');
    	}
    }

    function signIn(Request $request){
        if(!Auth::guard('hospital')->check()){
            return view('hospital.auth.signIn');
        }else{
            return redirect()->route('hospital.dashboard.index');
        }
    }

    function authenticating(Request $request){
        $credentials = $request->only('email', 'password');

        $user = Hospital::where([
	        	'email' => $request->email, 
	        	'status' => 1
	        ])
        	->first();

        if($user){
            if(Auth::guard('hospital')->attempt($credentials)){
                return redirect()->route('hospital.dashboard.index');
            }else{
                return redirect()->route('hospital.auth.signIn')->with('error', 'Invalid username or password');
            }
        }

        return redirect()->route('hospital.auth.signIn')->with('error', 'Invalid username or password');
    }

    function signOut(Request $request){
        if(Auth::guard('hospital')->user()->id){
            Auth::guard('hospital')->logout();

            return redirect()->route('hospital.auth.signIn');
        }else{
            return redirect()->route('hospital.dashboard.index');
        }
    }
}