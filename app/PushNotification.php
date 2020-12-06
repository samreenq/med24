<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class PushNotification extends Model
{
    use LogsActivity;

    protected $table = 'push_notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'message', 'trigger_type', 'trigger_id', 'image_url', 'device_type', 'is_admin', 'success', 'failure'
    ];

    protected static $logAttributes = [
        'user_id', 'title', 'message', 'trigger_type', 'trigger_id', 'image_url', 'device_type', 'is_admin', 'success', 'failure'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Push Notification has been {$eventName}";
    }

    public $validationRules = [
        'title' => 'required|string',
        'message' => 'required',
        'trigger_type' => 'required|string',
        'device_type' => 'required',
    ];

//    public function user()
//    {
//        return $this->belongsTo('App\User');
//    }

    public function offer()
    {
        return $this->belongsTo('App\Offers', 'trigger_id', 'id');
    }

    public function gym()
    {
        return $this->belongsTo('App\Gym', 'trigger_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany('App\User','push_notification_users','push_notification_id', 'user_id');
    }

    public function get_module()
    {
        if($this->trigger_type == 'gym') {
            return $this->gym;
        } elseif($this->trigger_type == 'offer') {
            return $this->offer;
        } else {
            return null;
        }
    }

    public function sendFcm($request)
    {
        if($request->trigger_type == 'gym') {
            $this->validationRules['trigger_id'] = 'exists:gym,id';
        } elseif($request->trigger_type == 'offer') {
            $this->validationRules['trigger_id'] = 'exists:offers,id';
        }

        if($request->user_id) {
            $this->validationRules['user_id'] = 'exists:users,id';
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }
        $success = 0;
        $failure = 0;
        $push = 0;

        foreach($request->get('device_type') as $device_type) {
            if($request->user_id) {
                $target = \App\DeviceTokens::where('device_token', '!=', '')->where('device_type', $device_type)->where('user_id', $request->user_id)->pluck('device_token')->toArray();
            } else {
                $target = \App\DeviceTokens::where('device_token', '!=', '')->where('device_type', $device_type)->pluck('device_token')->toArray();
            }

            //FCM API end-point
            $url = 'https://fcm.googleapis.com/fcm/send';
            //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            // $server_key = 'AAAAP4_kZQE:APA91bF1I6OnLthsD0sZHeRTwEIub3iBXO54qr3-HSJq1yamAycXPm2UoQ8fScw586dyzxIJVzYvZ5Sqwc14Xl_QIicRRB4lNu8d33_NT3tNeXXheHxanxAQAV5GRhI4IxJeSzYcku_s';
            $server_key = env('FCM_SERVER_KEY','');

            $fields = array();

            // if($device_type == 'ios') {
                $fields['notification']['title'] = $request->get('title');
                $fields['notification']['text'] = $request->get('message');
            // }

            $fields['data']['id'] = '';
            $fields['data']['trigger_id'] = $request->get('trigger_id');
            $fields['data']['trigger_type'] = $request->get('trigger_type');
            $fields['data']['description'] = $request->get('message');
            $fields['data']['text'] = $request->get('message');
            $fields['data']['title'] = $request->get('title');
            $fields['mutable_content'] = true;
            if(isset($target) && count($target) > 1){
                $fields['registration_ids'] = $target;
            }elseif(isset($target) && count($target) == 1){
                $fields['to'] = $target[0];
            }else{
                $fields['to'] = '/topics/allDevices';
            }
            //   return $fields;
            //header with content_type api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Oops! FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            $res = (array) json_decode($result);
            if(isset($res['success'])) {
                $success = $res['success'];
            }
            if(isset($res['failure'])) {
                $failure = $res['failure'];
            }

            $notification_data = [
                'title' => $request->title,
                'message' => $request->message,
                'trigger_type' => $request->trigger_type,
                'trigger_id' => $request->trigger_id,
                'image_url' => '',
                'device_type' => $device_type,
                'success' => $success,
                'failure' => $failure,
                'is_admin' => 1
            ];

            $push = PushNotification::create($notification_data);

            if($request->user_id) {
                $push->user()->attach($request->user_id);
            }
        }

        return $push;
    }
}
