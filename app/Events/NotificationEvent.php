<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title = 'Информационное сообщение от сайта HelpDesk';
    public $toMail = '';
    public $message ='';
    public $href ='';
    public $attach ='';
    public $fromUser = '';
    public $bottom = 'Это сообщение было создано автоматически сайтом HelpDesk, не надо на него отвечать.';


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title, $toMail, $message, $href, $attach, $fromUser)
    {
        $this->title = $title;
        $this->toMail = $toMail;
        $this->message = $message;
        $this->href = $href;
        $this->attach = $attach;
        $this->fromUser = $fromUser;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return new PrivateChannel('channel-name');
    }
}
