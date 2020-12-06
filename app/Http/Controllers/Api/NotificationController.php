<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Offers;
use App\Banners;
use App\Settings;
use App\DeviceTokens;
use App\PushNotification;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class NotificationController extends Controller
{
    function sendPushNotification($fcm_token, $title, $message, $id = "", $trigger_type = "home", $trigger_id = "") {
        $device = DeviceTokens::with('user')->where('device_token', $fcm_token)->first();
        if($device) {
            $push_notification_key = env('FCM_SERVER_KEY','');
            // $push_notification_key = 'AAAAP4_kZQE:APA91bF1I6OnLthsD0sZHeRTwEIub3iBXO54qr3-HSJq1yamAycXPm2UoQ8fScw586dyzxIJVzYvZ5Sqwc14Xl_QIicRRB4lNu8d33_NT3tNeXXheHxanxAQAV5GRhI4IxJeSzYcku_s';    
            $url = "https://fcm.googleapis.com/fcm/send";            
            $header = array("authorization: key=" . $push_notification_key . "",
                "content-type: application/json"
            );

            $postdata = '{
                "to" : "' . $fcm_token . '",
                    "notification" : {
                        "title":"' . $title . '",
                        "text" : "' . $message . '"
                    },
                "data" : {
                    "id" : "'.$id.'",
                    "title":"' . $title . '",
                    "description" : "' . $message . '",
                    "text" : "' . $message . '",
                    "trigger_type" : "' . $trigger_type . '",
                    "trigger_id" : "' . $trigger_id . '",
                    "is_read": 0
                  }
            }';

            $ch = curl_init();
            $timeout = 120;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            // Get URL content
            $result = curl_exec($ch);    
            // close handle to release resources
            curl_close($ch);

            $res = (array) json_decode($result);
            if(isset($res['success'])) {
                $success = $res['success'];
            }
            if(isset($res['failure'])) {
                $failure = $res['failure'];
            }

            $notification_data = [
                'user_id' => $device->user->id,
                'title' => $title,
                'message' => $message,
                'trigger_type' => $trigger_type,    
                'trigger_id' => $trigger_id,
                'image_url' => '',
                'device_type' => $device->device_type,
                'success' => $success,
                'failure' => $failure
            ];

            $notification = PushNotification::create($notification_data);

            return 1;
        } else {
            return 0;
        }
    }
}