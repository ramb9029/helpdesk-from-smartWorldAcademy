<?php

namespace App\Http\Services\Order;

use App\Helpers\FileHelper;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OrderUpdateService
{
    const STATUS_EXECUTION_ARCHIVE = 1;
    const STATUS_EXECUTION_NEW = 2;
    const STATUS_EXECUTION_WORK = 3;
    const STATUS_EXECUTION_COMPLETE = 4;

    public function update($id, $request)
    {
        $order = Order::find($id);
        $userId = $request->user()->id;
        $userRole = $request->user()->role;
        $executorUsers = $order->executorUser;

        if($userId === $order->client_user_id){
            $order = $this->updateFromClient($order, $request);
        }

        if($userRole === User::MODERATOR_ROLE_ID or $userRole === User::ADMINISTRATOR_ROLE_ID){
            $order = $this->updateFromAdministrations($order, $request);
        }

        if($executorUsers->contains($request->user()->id)){
            $order = $this->updateFromExecutor($order, $request);
        }
        return $order;
    }

    private function updateFromClient($order, $request)
    {
        $order->name          = $request->get('name', $order->name);
        $order->description   = $request->get('description', $order->description);
        $order->priority      = $request->get('priority', $order->priority);
        $order->access        = $request->get('access', $order->access);

        if ($request->topic_id){
            $order->topics = $request->get('topic_id');
        }

        $file = $request->file('file');
        if($file){
            $file = FileHelper::FileUpload($request->file('file'), Order::FILE_DIR);
        }

        if (!$file) {
            return response()->json(['status' => 'error',
                'message' => 'Загрузка файла не удалась. Попробуйте ещё раз.'], 400);
        }

        $old_file = $order->file;
        $order->file          = $file;
        if($old_file != $file) {
            Storage::delete(Order::FILE_DIR . $old_file);
        }
        return $order;
    }

    private function updateFromAdministrations($order, $request)
    {
        if($request->executor_user_id){
            $order->executorUsers = $request->get('executor_user_id');
        }
        $order->estimatedDueDate = $request->get('estimatedDueDate', $order->estimatedDueDate);
        $order->action = $request->get('action');

        if($order->estimatedDueDate and $request->executor_user_id) {
            if($order->statusExecution_id === self::STATUS_EXECUTION_NEW){
                $order->statusExecution_id = self::STATUS_EXECUTION_WORK;
            }
        }
        if($request->statusExecution_id === self::STATUS_EXECUTION_ARCHIVE){
            $order->statusExecution_id = $request->get('statusExecution_id');
        }
        return $order;
    }

    private function updateFromExecutor($order, $request)
    {
        if ($request->estimatedDueDate){
            $order->estimatedDueDate = $request->get('estimatedDueDate');
            if ($order->statusExecution_id === self::STATUS_EXECUTION_NEW){
                $order->statusExecution_id = self::STATUS_EXECUTION_WORK;
            }
        }
        //Если есть запрос на изменение статуса. (исполнитель может верунать )
        if($request->statusExecution_id >= self::STATUS_EXECUTION_WORK){
            $order->statusExecution_id = $request->get('statusExecution_id');
        }
        return $order;
    }

}
