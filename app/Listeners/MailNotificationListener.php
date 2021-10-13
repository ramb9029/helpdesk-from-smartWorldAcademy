<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class MailNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        //$event->message = $event->message.'<br>'.$event->href.'<br>'.$event->bottom;
        //dd($event->fromUser);
        Mail::send('mail.sendSimple', ['event' => $event], function ($message) use ($event) {
            $message->to($event->toMail);
            $message->subject($event->title);
            if($event->attach !== '') {
                $message->attach($event->attach);
            }
    });
    }
}
