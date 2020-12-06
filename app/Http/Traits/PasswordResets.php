<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

trait PasswordResets{
    public function sendPasswordLink($email,$broker='users'){
        try{
            $response = $this->broker($broker)->sendResetLink(
                ['email'=>$email]
            );
            $data= $response == Password::RESET_LINK_SENT
                ? ['status'=>1,'message'=>'We have emailed you password reset link']
                : ['status'=>0,'message'=>__($response)];
            return $data;
        }catch (\Exception $e){
            return ['status'=>0,'message'=>$e->getMessage()];
        }
    }
    public function broker($broker)
    {
        return Password::broker($broker);
    }

    public function updatePass($data,$broker){
        $response = $this->broker($broker)->reset(
            $data, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
        );

         return $response == Password::PASSWORD_RESET
            ? ['status'=>1,'message'=>trans($response)]
            : ['status'=>0,'message'=>trans($response)];

    }
}
