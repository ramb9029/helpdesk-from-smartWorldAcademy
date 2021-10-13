<?php

namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrdersEvent
{
    use SerializesModels;

    public $order;

    /**
     * Слушатель для созданного листа
     *
     * @param Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
