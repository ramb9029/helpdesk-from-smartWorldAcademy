<?php

namespace App\Listeners\Orders;

use App\Events\Orders\UpdatingOrdersEvent;
use App\Models\Topic;
use App\Models\User;

class UpdatingOrdersListener
{
    /**
     * Слушатель для созданного листа
     *
     * @param UpdatingOrdersEvent $event
     */
    public function handle(UpdatingOrdersEvent $event)
    {
        if($event->order->topics){
            $topic = Topic::find($event->order->topics);
            $event->order->topic()->sync($topic);
        }
        if ($event->order->executorUsers) {
            $executorUsers = User::find($event->order->executorUsers);
            $event->order->executorUser()->sync($executorUsers);
        }
    }
}
