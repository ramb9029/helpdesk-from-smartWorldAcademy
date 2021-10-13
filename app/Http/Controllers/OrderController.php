<?php

namespace App\Http\Controllers;

use App\Events\Orders\CreatingOrdersEvent;
use App\Events\Orders\UpdatingOrdersEvent;
use App\Events\MailNotificationEvent;
use App\Helpers\FileHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Models\Order;
use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Http\Services\Order\OrderUpdateService;
use App\Http\Services\Order\OrderGetAllService;
use App\Converters\OrderConverter;


class OrderController extends Controller
{

    const STATUS_EXECUTION_ARCHIVE = 1;
    const STATUS_EXECUTION_NEW = 2;
    const STATUS_EXECUTION_WORK = 3;
    const STATUS_EXECUTION_COMPLETE = 4;
    /**
     * Сервис для работы с заявками
     *
     * @var OrderUpdateService
     */
    protected OrderUpdateService $orderUpdateService;
    protected OrderGetAllService $orderGetAllService;

    /**
     * Create a new controller instance.
     *
     * @param OrderUpdateService $orderUpdateService
     * @param OrderGetAllService $orderGetAllService
     */
    public function __construct(OrderUpdateService $orderUpdateService, OrderGetAllService $orderGetAllService)
    {
        $this->orderUpdateService = $orderUpdateService;
        $this->orderGetAllService = $orderGetAllService;
    }

    /**
     * Метод создания заявки
     *
     * @param OrderCreateRequest $request
     * @return JsonResponse
     */
    public function create(OrderCreateRequest $request): JsonResponse
    {
        $file = $request->file('file');

        if($file){
            $file = FileHelper::FileUpload($request->file('file'), Order::FILE_DIR);
        }

        if (!$file) {
            $this->responseError('Загрузка файла не удалась. Попробуйте ещё раз.', 400);
        }

        $order = new Order();
        $order->name           = $request->get('name');
        $order->description    = $request->get('description');
        $order->file           = $file;
        $order->priority       = $request->get('priority');
        $order->access         = $request->get('access');;
        $order->client_user_id = $request->user()->id;
        $order->statusExecution_id = self::STATUS_EXECUTION_ARCHIVE;
        $order->topics         = $request->get('topics');
        if($request->checkListOrders){
            $order->checkListOrders = $request->get('checkListOrders');
        }

        if (!$order->save()) {
            return $this->responseError('Не удалось добавить заявку. Попробуйте позже', 400);
        }

        event(new CreatingOrdersEvent($order));
        //event( new MailNotificationEvent($order));
        return $this->responseSuccess(OrderConverter::oneToArray($order), 'Новая заявка создана', 201);
    }

    /**
     * Метод обновления заявки
     *
     * @param $id
     * @param OrderUpdateRequest $request
     * @return JsonResponse
     */
    public function update($id, OrderUpdateRequest $request): JsonResponse
    {

        $order = $this->orderUpdateService->update($id, $request->all());

        if (!$order->save()) {
            return $this->responseError('Не удалось отредактировать заявку. Попробуйте позже', 400);
        }

        event(new UpdatingOrdersEvent($order));
        return $this->responseSuccess(OrderConverter::oneToArray($order), 'Изменения сохранены',200);
    }

    /**
     * Метод удаления заявки
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $order = Order::find($id);

        if(!$order) {
            return $this->responseError('Заявка не найдена', 404);
        }

        if (!in_array($order->statusExecution_id, [1, 2, 3], true)) {
            return $this->responseError('Заявка уже удалена', 405);
        }
        try {
            $order->statusExecution_id = 1;
            $order->save();
        } catch (\Exception $e) {
            return $this->responseError('Не удалось удалить заявку, попробуйте позже', 400);
        }

        return $this->responseSuccess(null, 'Заявка удалена в архив',200);

    }

    /**
     * Получить все заявки
     *
     * @param OrderCreateRequest $request
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {

        $orders = $this->orderGetAllService($request->all());

        if(!$orders) {
            return $this->responseError('Здесь пока нет заявок', 404);
        }

        return $this->responseSuccess(OrderConverter::manyToArray($orders), '', 200);
    }


    /**
     * Метод получения одной заявки
     *
     * @param $id
     * @return JsonResponse
     */
    public function getOne(Request $request): JsonResponse
    {
        $id = $request->id;
        $order = Order::where('id', $id)->first();
        if(!$order) {
            return $this->responseError('Заявка не найдена!', 404);
        }
        $executorUsers = Order::find($order->id)->executorUser;



        //Проверка на архив
        if($request->user()->role === User::USER_ROLE_ID) {
            if ($order->statusExecution_id === 1) {
                return $this->responseError('У вас прав нет!', 403);
            }
        }

        //Проверка привата
        if(!($request->user()->id === $order->client_user_id || $request->user()->role !== User::USER_ROLE_ID)) {
            //Исполнители тоже имеют доступ к приватной заявке
            if(!$executorUsers->contains($request->user()->id)) {
                if ($order->access === true) {
                    return $this->responseError('У вас нет прав!', 403);
                }
            }
        }


        /** @var Order $order */
        //return response()->json($order);
        return $this->responseSuccess(OrderConverter::oneToArray($order), '', 200);
    }

}
