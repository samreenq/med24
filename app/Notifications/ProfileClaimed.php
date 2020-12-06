<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileClaimed extends Notification
{
    use Queueable;
    public $doctor;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($doctor,$token)
    {
        $this->doctor=$doctor;
        $this->token=$token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
                    ->subject('Profile Claimed')
                    ->line('Dear '. $this->doctor->first_name .' '. $this->doctor->last_name. ' Someone Has Claimed Your Profile')
                    ->line('Please follow this link to update your password')
                     ->action('Update Password', route('password.reset',['token'=>$this->token,'email'=>$this->doctor->email,'broker'=>'doctor']))
                     ->line('This  link will expire in 60 minutes.')
            ->line('If you did not request profile claim , no further action is required')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
