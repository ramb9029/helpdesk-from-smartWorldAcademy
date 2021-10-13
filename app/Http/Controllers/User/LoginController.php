<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Helpers\UserSystemInfoHelper;
use App\Models\User;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (! $user=User::where('email', $request->email)->first()){
            return response()->json(['success' => false,
                'message' => 'Неверный логин или пароль'], 200);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false,
                'message' => 'Неверный логин или пароль'], 200);
        }
        //Удаленный (архивированый пользователь не может войти в систему)
        if($user->role === User::ARCHIVED_ROLE_ID){
            return response()->json(['success' => false,
                'message' => 'Неверный логин или пароль'], 200);
        }

        $userOs = UserSystemInfoHelper::getOs();
        $userBrowser = UserSystemInfoHelper::getBrowsers();
        $tokenName = $request->email.'|'.$userOs.'|'.$userBrowser;
        $token = $user->createToken($tokenName)->plainTextToken;
        $token = explode('|', $token);
        UserSystemInfoHelper::getUser($user);
        return response()->json(['token' => $token[1], 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $bearer = $request->bearerToken();
        DB::table('personal_access_tokens')->where('token', hash('sha256', $bearer))->delete();
        //dd($request->user()->id);
//        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Вы вышли из системы']);
    }

    public function logoutAllDevice(Request $request) //except used
    {
        $tokens = DB::table('personal_access_tokens')->where('name', 'like', $request->user()->email.'%')->get();
        if(empty($tokens)){
            return response()->json(['message' => 'У вас нет подключеных устройств'
                ], 422);
        }
        $userTokenId = $request->user()->currentAccessToken()->id;
        foreach ($tokens as $token){
            if ($token->id !== $userTokenId){
                $request->user()->tokens()->where('id', $token->id)->delete();
            }
        }
        return response()->json(['message' => 'Вы вышли из всех устройств, кроме этого']);
    }

    public function logoutDevice(Request $request, $id)
    {
        //logout from one device
        try {
            if($request->user()->tokens()->where('id', $id)->first()->delete()) {
                return response()->json(['message' => 'Вы вышли']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }
}
