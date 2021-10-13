<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Http\Requests\ReviewCreateRequest;
use App\Http\Services\Review\ReviewService;
use App\Converters\ReviewConverter;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Сервис для работы с отзывами
     *
     * @var ReviewService
     */
    protected $reviewService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Метод создания отзыва
     *
     * @param ReviewCreateRequest $request
     * @return JsonResponse
     */
    public function create(ReviewCreateRequest $request): JsonResponse
    {

        $userId = $request->user()->id;
        $userRole = $request->user()->role;

        $review = new Review();
        $review->order_id       = $request->get('order_id');

        //Заявка не исполнена
        if(Order::find($review->order_id)->statusExecution_id !== 4) {
            return $this->responseError('Вы можете оценить только выполненную заявку', 400);
        }

        //Могут только Клиент, Модер, Админ оставлять отзыв
        if($userId === Order::find($review->order_id)->client_user_id
            || $userRole === User::MODERATOR_ROLE_ID || $userRole === User::ADMINISTRATOR_ROLE_ID) {

            $review->critic_user_id = $request->get('criticUser');

            //Если есть отзыв от модера, то не может ставить админ, и наоборот
            if(Review::find($review->order_id) && Review::find($review->order_id)->critic_user_id !== $userId
                && ($userRole === User::MODERATOR_ROLE_ID || $userRole === User::ADMINISTRATOR_ROLE_ID)) {
                return $this->responseError('Исполнение данной заявки уже оценивалось ранее. Повторная оценка недоступна.', 400);
            }

            //Если люди разные или у заявки нет отзывов
            if(!Review::find($review->order_id) || $userId !== Review::find($review->order_id)->critic_user_id) {

                //Отметаю клиента, который пытается прислать оценку за модера или админа, таковым не являясь
                if($userId === Order::find($review->order_id)->client_user_id
                    && !($userRole === User::MODERATOR_ROLE_ID || $userRole === User::ADMINISTRATOR_ROLE_ID)
                    && $review->valueOther) {
                    return $this->responseError('У Вас нет прав на выбранное действие', 403);
                }

                //Отметаю модера или админа, которые пытаются поставить оценку за клиента, таковым не являясь
                if($userId !== Order::find($review->order_id)->client_user_id
                    && ($userRole === User::MODERATOR_ROLE_ID || $userRole === User::ADMINISTRATOR_ROLE_ID)
                    && $review->valueClient) {
                    return $this->responseError('У Вас нет прав на выбранное действие', 403);
                }

                $review->description    = $request->get('description');
                $review->valueClient    = $request->get('valueClient');
                $review->valueOther     = $request->get('valueOther');

                if (!$this->reviewService->create($review)) {
                    return $this->responseError('Не удалось оценить исполнение заявки, попробуйте позже', 400);
                }

                return $this->responseSuccess(ReviewConverter::oneToArray($review), 'Новый отзыв создан', 201);
            }
            //Если нашёл отзывы у заявки и люди одинаковые, то проверяю значения, которые прилетают, чтобы не оценили повторно
            elseif(Review::find($review->order_id) && Review::find($review->order_id)->critic_user_id === $userId) {
                if($review->valueClient && !Review::find($review->order_id)->where($review->valueClient, null)) {
                    return $this->responseError('Исполнение данной заявки уже оценивалось ранее. Повторная оценка недоступна.', 400);
                }
                if ($review->valueOther && !Review::find($review->order_id)->where($review->valueOther, null)) {
                    return $this->responseError('Исполнение данной заявки уже оценивалось ранее. Повторная оценка недоступна.', 400);
                }
                if ($review->description && !Review::find($review->order_id)->where($review->description, null)) {
                    return $this->responseError('Исполнение данной заявки уже оценивалось ранее. Повторная оценка недоступна.', 400);
                }

                $review->description    = $request->get('description');
                $review->valueClient    = $request->get('valueClient');
                $review->valueOther     = $request->get('valueOther');

                if (!$this->reviewService->create($review)) {
                    return $this->responseError('Не удалось оценить исполнение заявки, попробуйте позже', 400);
                }

                return $this->responseSuccess(ReviewConverter::oneToArray($review), 'Новый отзыв создан', 201);
            }
        }
        return $this->responseError('У Вас нет прав на выбранное действие', 403);
    }
}
