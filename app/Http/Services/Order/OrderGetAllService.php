<?php

namespace App\Http\Services\Order;

use App\Converters\OrderConverter;
use App\Helpers\FileHelper;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OrderGetAllService
{
    public function getAll($request)
    {
        $filter   = $request->get('filter');
        $perPage = $request->get('per_page');
        $page     = $request->get('page', 0);
        $tab      = $request->get('tab');
        $orderByRaw = '';
        $orders = Order::where($filter);

        if ($request->order) {
            foreach ($request->order as $items) {
                foreach ($items as $item){
                    $orderByRaw .=$item.' ';
                }
            }
        } else
        {
            $orderByRaw = 'id asc';
        }

        if(!$request->user()) {
            if ($request->filter){
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            $filter[] = ['statusExecution_id', '!=', Order::STATUS_EXECUTION_ARCHIVE];
            $filter[] = ['access', '!=', 'true'];

            //$orders = $orders->where($filter)->orderByRaw($orderByRaw)->paginate($perPage);
            $orders = $orders->where($filter)->orderByRaw($orderByRaw)->limit($perPage)->offset($page*$perPage)->get();
            return $this->responseSuccess(OrderConverter::manyToArray($orders), '', 200);
        }

        $userId = $request->user()->id;
        if($tab === 'list task') {
            if($request->user()->role == User::USER_ROLE_ID){
                $orders = $orders->where('access', 'false')
                    ->where('statusExecution_id', '!=', Order::STATUS_EXECUTION_ARCHIVE)
                    ->orWhere('client_user_id', $request->user()->id)
                    ->where('access', 'true')
                    ->where($filter)
                    ->orWhereHas('executorUser', function ($q) use($userId, $filter){
                        $q->where('access', 'true')
                            ->where('user_id', $userId)
                            ->where($filter);
                    });
            }
        }

        if($tab === 'my task') {
            $filter[] = ['client_user_id', '=', $request->user()->id];
            $filter[] = ['statusExecution_id', '!=', Order::STATUS_EXECUTION_ARCHIVE];
        }

        if($tab === 'archive') {
            $filter[] = ['statusExecution_id', '=', Order::STATUS_EXECUTION_ARCHIVE];
        }
        //$orders = $orders->where($filter)->orderByRaw($orderByRaw)->paginate($perPage);
        $orders = $orders->where($filter)->orderByRaw($orderByRaw)->limit($perPage)->offset($page*$perPage)->get();
        return $orders;
    }
}
