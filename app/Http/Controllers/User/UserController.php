<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Helpers\UserSystemInfoHelper;
use App\Models\User;
use App\Events\NotificationEvent;




class UserController extends Controller
{

    public function registration(UserRequest $request)
    {
        //moderator can create only user
        //administrator can create all
        if($request->user()->role === User::MODERATOR_ROLE_ID AND $request->role !== User::USER_ROLE_ID)
        {
            return response()->json(['success' => false, 'error' => 'Access denied'], 403);
        }

        $user =  new User($request->validated());
        $user->password = bcrypt($user->password);
        if(!$user->save()) {
            return response()->json(['status' => 'error', 'message' => 'Не удалось зарегистрировать пользователя'], 201);
        }
        return response()->json(['data' => $user, 'message' => 'Зарегистрирован'], 201);
    }

    public function update(UserRequest $request, $id)
    {
        //User can update only yourself
        //Moderator can update users and yourself
        //Administrator can update all users
        $user = User::whereId($id)->first();
        if(!$user){
            return response()->json(['status' => false, 'message' => 'Пользователя не существует'], 404);
        }

        if(!$user->update($request->validated())){
            return response()->json(['status' => false, 'message' => 'Не удалось обновить пользователя'], 403);
        }
        return response()->json(['data' => $user, 'message' => 'обновлено'], 202);
    }

    public function delete($id)
    {
        //Moderator can delete only simple users
        //Administrator can delete all users
        try {
            $user = User::whereId($id)->first();
            if ($user->role === 1){
                return response()->json([
                    'data' => $user,
                    'message' => 'Пользователь уже перемещен в архив'],
                    201);
            }
            if($user->update(['role' => 1])) {
                $tokens = DB::table('personal_access_tokens')->whereId($id)->get();
                if(empty($tokens)){
                    return response()->json([
                        'data' => $user,
                        'message' => 'Пользователь перемещен в архив'],
                        201);
                }
                foreach ($tokens as $token){
                    if ($token->id !== $id){
                        $user->tokens()->where('id', $id)->delete();
                    }
                }
                //new event for update Orders where user was client or executor
                return response()->json([
                    'data' => $user,
                    'message' => 'Пользователь перемещен в архив'],
                    201);
            }
        } catch (\Error $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }
}
