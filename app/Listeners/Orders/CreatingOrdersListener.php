<?php

namespace App\Listeners\Orders;

use App\Events\Orders\CreatingOrdersEvent;
use App\Models\Message;
use App\Models\Review;
use App\Models\Topic;
use App\Models\CheckListOrders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatingOrdersListener
{
    /**
     * Слушатель для созданного листа
     *
     * @param CreatingOrdersEvent $event
     */
    public function handle(CreatingOrdersEvent $event)
    {
        $topic = Topic::find($event->order->topics);
        $event->order->topic()->attach($topic);

        if ($event->order->checkListOrders) {
            foreach ($event->order->checkListOrders as $list){
                CheckListOrders::create($list, $event->order->id);
            }
        }
    }
}
