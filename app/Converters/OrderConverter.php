<?php

namespace App\Converters;

use App\Models\Review;
use App\Models\Topic;
use App\Models\Order;
use App\Models\User;
use App\Models\StatusExecution;


class OrderConverter
{
    /**
     * Преобразовывает модель Painting в массив
     *
     * @param Order $order
     * @return array
     */
    public static function oneToArray(Order $order): array
    {

        $order->statusExecution = StatusExecution::find($order->statusExecution_id);
        $order->clientUser = User::find($order->client_user_id);
        $order->executorUsers = Order::find($order->id)->executorUser->makeHidden('pivot')->toArray();
        $order->topics = Order::find($order->id)->topic->makeHidden('pivot')->toArray();
        $order->review = Order::find($order->id)->reviews->toArray();

        $result = [
            'id'    => $order->id,
            'name' => $order->name,
            'description' => $order->description,
            'file' => $order->file,
            'priority' => $order->priority,
            'estimatedDueDate' => $order->estimatedDueDate,
            'access' => $order->access,
            'action' => $order->action,
            'clientUser' => json_decode($order->clientUser),
            'executorUser' => $order->executorUsers,
            'topics' => $order->topics,
            'statusExecution' => json_decode($order->statusExecution),
            'review' => $order->review,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ];

        return $result;
    }

    /**
     * Преобразовывает коллекцю в массив
     *
     * @param $orders
     * @return array
     */
    public static function manyToArray($orders)
    {
        $items = [];

        foreach ($orders as $order) {
            $items[] = self::oneToArray($order);
        }

        return ['items' => $items];
    }


}
