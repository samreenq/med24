<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\DeviceTokens;
use App\UserSessions;
use App\User;

class Notifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::with('sessions')->where('status', 1)->get();
        foreach($users as $user) {
            $last_visit = $user->sessions->max('start_datetime');
            $created = new Carbon($last_visit);
            if($user->last_reminder && $last_visit < $user->last_reminder) {
                $created = new Carbon($user->last_reminder);
            }
            $now = new Carbon($user->last_reminder);
            
            $difference = ($created->diff($now)->days < 1)
                ? 'today'
                : $created->diffInDays($now);
//            $check_date = date('Y-m-d H:i:s', strtotime('-10 days'));

            if($difference >= 10) {
                $device = DeviceTokens::where('user_id', $user->id)->first();

                if($device) {
                    $title = "Train60";
                    $message = "You haven't used train60 for a while. Start training & burn calories to stay fit.";
                    $user->last_reminder = date('Y-m-d H:i:s');
                    $user->save();
                    $notification = app('App\Http\Controllers\Api\NotificationController')->sendPushNotification($device->device_token, $title, $message);
                }
            }
        }
    }
}
