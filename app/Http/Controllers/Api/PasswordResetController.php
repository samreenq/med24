<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\PasswordResets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class PasswordResetController extends Controller
{
    use PasswordResets;
    public function forgotPass(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'broker'=>'required|in:users,doctor,patient'
        ]);
        $response=$this->sendPasswordLink($request->email,$request->broker);
        $response['data']=null;
        $response['api_status']=200;
        return response()->json($response);
    }
    public function reset(Request $request, $token = null){
        return view('site.password-reset')->with(
            ['token' => $token, 'email' => $request->email,'broker'=>$request->broker]
        );
    }
    public function updatePassword(Request $request){
        $rules= [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'broker'=>'required|in:users,doctor,patient'
        ];
        $this->validate($request,$rules);
        $data=$request->only('token','email','password','password_confirmation');
        $reset_password=$this->updatePass($data,$request->broker);
        if($reset_password['status']==1){
            return redirect()->back()->with('success','Your Password Has Been Reset!');
        }else{
            return redirect()->back()->with('error',$reset_password['message']);
        }
    }
}
