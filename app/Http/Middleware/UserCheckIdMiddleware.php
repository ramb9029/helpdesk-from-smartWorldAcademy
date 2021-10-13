<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCheckIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //Заметка на будущее, есть метод получения перфикса роута. Надо переделать и убрать повторяющиеся проверки
        $bearer = $request->bearerToken();
        $token = DB::table('personal_access_tokens')->where('token', hash('sha256', $bearer))->first();

        if(empty($token)){
            return response()->json(['success' => false, 'error' => 'Ошибка авторизации'], 401);
        }

        if (empty($user = User::find($token->tokenable_id))){
            return  response()->json(['success' => false, 'error' => 'Ошибка авторизации'], 401);
        }
        //Внимание говнокод
        //
        if($request->route()->getName() === 'user.getInfoUser') {
            $checkRequestUser = User::whereId($request->route()->id)->first();
            if(!$checkRequestUser){
                return response()->json(['success' => false, 'error' => 'Прольователя не существует'], 404);
            }
            //Пользователь может смотреть только свою информацию
            //если роль пользователя юзер и в реквесте не его ИД
            //запрещаем
            if($user->role === User::USER_ROLE_ID and $user->id !== $request->route()->id){
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            //Модератор может смотреть информацию о любом пользователе, кроме других модераторов и руководителя
            //Если роль модератор, и ид реквеста совпадает с ид модератора
            //Разрешаем
            if($user->role === User::MODERATOR_ROLE_ID and $checkRequestUser->id === $user->id){
                Auth::login($user);
                return $next($request);
            }
            //Если пользователь модератор и роль пользователя в реквесте модератор или руководитель
            //Запрещаем
            if ($user->role === User::MODERATOR_ROLE_ID and $checkRequestUser->role !== User::USER_ROLE_ID) {
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
        }
        //Запрет удаления пользователя всем кроме администратора
        if($request->route()->getName() === 'user.deleteUser'){
            if($user->role !== User::ADMINISTRATOR_ROLE_ID){
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
        }

        if($request->route()->getName() === 'user.updateUser') {

            $checkRequestUser = User::whereId($request->route()->id)->first();
            if(!$checkRequestUser){
                return response()->json(['success' => false, 'error' => 'Прольователя не существует'], 404);
            }

            if($user->role === User::MODERATOR_ROLE_ID and $request->route()->id == $user->id)
            {

                if($request->role === USER::ADMINISTRATOR_ROLE_ID and $user->role !== USER::ADMINISTRATOR_ROLE_ID){
                    //dd($request->role);
                    return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                }
                Auth::login($user);
                return $next($request);
            }
            //
            if ($user->role !== User::ADMINISTRATOR_ROLE_ID and $checkRequestUser->role !== User::USER_ROLE_ID) {
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
        }

        if($request->route()->getName() === 'order.getAll'){

            if($user->role === User::USER_ROLE_ID){

                    //if in filter from simple user we are have some data
                    //this is not avtorize action for simple user, because
                    //filter by client_user add on controller
                if($request->filter){
                        foreach ($request->filter as $item) {
                            if($item[0] === 'client_user_id'){
                                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                            }
                            if($item[0] === 'statusExecution_id'){
                                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                            }
                            if($item[0] === 'action'){
                                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                            }
                        }
                }

                if($request->tab !== 'Архив' and $request->tab !== 'Список заявок' and $request->tab !== 'Мои заявки'){
                    return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                }
            }
        }

        if($request->route()->getName() === 'order.delete'){
            if($user->role !== User::ADMINISTRATOR_ROLE_ID){
                return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
        }



        if($request->route()->getName() === 'order.update'){
            $order = Order::find($request->route()->id);
            if(!$order) {
                return response()->json(['success' => false, 'error' => 'Заявка не найдена'], 404);
            }
            $executorUsers = $order->executorUser;
            if($user->id !== $order->client_user_id){
                if ($user->role === User::USER_ROLE_ID){
                    if (!$executorUsers->contains($user->id)){
                        return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
                        }
                    }
                }
        }


        Auth::login($user);
        return $next($request);
    }
}
