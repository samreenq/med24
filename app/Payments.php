<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Settings;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Payments extends Model
{
    use LogsActivity;

    protected $table = 'payments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'session_id', 'card_id', 'amount', 'response', 'status'
    ];

    protected static $logAttributes = [
        'user_id', 'session_id', 'card_id', 'amount', 'response', 'status'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Payments has been {$eventName}";
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function session()
    {
        return $this->belongsTo('App\UserSessions', 'session_id');
    }

    public function card()
    {
        return $this->belongsTo('App\UserCards', 'card_id');
    }

    public static function addPayment($session, $card)
    {
        $merchant_email = Settings::where('name', 'paytab_merchant_email')->first();
        $merchant_email = ($merchant_email) ? $merchant_email : 'abc@abc.com';
        $secret_key = Settings::where('name', 'paytab_secret_key')->first();
        
        
        $secret_key = ($secret_key) ? $secret_key->value : 'abcd1234';
        $user = User::find($session->user_id);
        $pay_data['merchant_email'] = $merchant_email->value;
        $pay_data['secret_key'] = $secret_key;
        $pay_data['title'] = $session->gym->name;
        $pay_data['cc_first_name'] = $session->user->first_name;
        $pay_data['cc_last_name'] = $session->user->first_name ? $session->user->first_name : 'Doe';
        $pay_data['order_id'] = $session->id;
        $pay_data['product_name'] = $session->gym->name;
        $pay_data['customer_email'] = $session->user->email;
        $pay_data['phone_number'] = $session->user->phone ? $session->user->phone : '1234567';
        $pay_data['amount'] = $session->total_amount;
        $pay_data['currency'] = 'AED';
        $pay_data['address_billing'] = 'manama bahrain';
        $pay_data['state_billing'] = 'manama';
        $pay_data['city_billing'] = 'manama';
        $pay_data['postal_code_billing'] = '00973';
        $pay_data['country_billing'] = 'BHR';
        $pay_data['address_shipping'] = 'Juffair bahrain';
        $pay_data['city_shipping'] = 'manama';
        $pay_data['state_shipping'] = 'manama';
        $pay_data['postal_code_shipping'] = '00973';
        $pay_data['country_shipping'] = 'BHR';
        $pay_data['pt_token'] = $card->token;
        $pay_data['pt_customer_email'] = $card->customer_email;
        $pay_data['pt_customer_password'] = $card->customer_password;
        
        $url = 'https://www.paytabs.com/apiv3/tokenized_transaction_prepare';
        // return $pay_data;
        $pay = Self::runPost($url, $pay_data);

        $pay = (array) json_decode($pay);

        // $pay['response_code'] = '100';
        // $pay['result'] = 'Success';
        
        $payment = Payments::where('session_id', $session->id)->first();
        
        if(!$payment) {
            $payment = new Payments();
        }

        $payment->user_id = $user->id;
        $payment->session_id = $session->id;
        $payment->card_id = $card->id;
        $payment->amount = $session->total_amount;
        $payment->response = json_encode($pay);
        if(isset($pay['response_code']) && $pay['response_code'] == '100') {
            $payment->status = 'PAID';
        } else {
            $payment->status = 'DUE';
        }
        $payment->save();

        return ['pay' => $pay, 'payment' => $payment];
    }

    public static function runPost($url, $fields) {
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        $ch = curl_init();
        $ip = $_SERVER['REMOTE_ADDR'];
        $ip_address = array(
            "REMOTE_ADDR" => $ip,
            "HTTP_X_FORWARDED_FOR" => $ip
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
