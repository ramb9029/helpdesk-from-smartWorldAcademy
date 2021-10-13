<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModeratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bearer = $request->bearerToken();
        $token = DB::table('personal_access_tokens')->where('token', hash('sha256', $bearer))->first();

        if(empty($token)){
            return response()->json(['success' => false, 'error' => 'Ощибка авторизации'], 401);
        }

        if (empty($user = User::find($token->tokenable_id))){
            return  response()->json(['success' => false, 'error' => 'Ошибка авторизации'], 401);
        }

        if($user->role === User::USER_ROLE_ID){
            return response()->json(['success' => false, 'error' => 'Доступ запрещен'], 403);
        }

        Auth::login($user);
        return $next($request);
    }
}
