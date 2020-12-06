<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


Class SocialController extends WebController
{
/**
* Redirect the user to the GitHub authentication page.
*
* @return \Illuminate\Http\Response
*/
    public function redirectToProvider($provider)
    {
       // die($provider);
        return Socialite::driver($provider)->redirectUrl(config('services.'.$provider.'.redirect'))->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request,$provider)
    {
        try {

            try {
                $row = Socialite::driver($provider)->stateless()->user();
            } catch (InvalidStateException $e) {
                //echo '<pre>'; print_r($e->getTraceAsString()); exit;
                return redirect('sign-in')->with('error',$e->getMessage());
            }

            $platform = ($provider == 'google') ? 'google.com' : 'facebook.com';

            if (isset($row)) {

                $user = (object)$row->user;

                if (!isset($user->email) || (isset($user->email) && empty($user->email))) {
                    return redirect('sign-in')->with('error', "Social Login: You cannot login without email, Please use another account.");
                }

                $name = explode(' ',$user->name);
                $first_name = $name[0];
                $last_name = isset($name[1]) ? $name[1] : '';

                $data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $user->email,
                    'phone_no' => isset($user->mobile) ? $user->mobile : '',
                    'provider_id' => $user->id,
                    'provider_type' => $platform,
                    'status' => 1
                );

                $patient=Patient::where('provider_id',$user->id)->first();

                $request->request->add($data);

                if(!$patient){
                    $patient=new Patient();
                   $patient_raw = $patient->store($request);
                }
                else{
                    $patient_model = Patient::find($patient->id);
                    $patient_raw = $patient_model->store($request);
                }

                //echo '<pre>'; print_r($patient_raw); exit;
                Auth::guard('user')->login($patient_raw);
                return redirect('/');

            }
        }
        catch (\Exception $ee){
           // echo '<pre>'; print_r($ee->getTraceAsString()); exit;
            return redirect('sign-in')->with('error',$ee->getMessage());
        }

    }

}
