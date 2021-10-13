<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Helpers\UserSystemInfoHelper;
use App\Models\User;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserInfoController extends Controller
{
    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        UserSystemInfoHelper::getUser($user);
        return response()->json($user);
    }

    public function getUsers(Request $request)
    {
        //Moderator can not see deleted(arhived) user
        //Administrator can see all users
        $filter = $request->filter;
        $orderByRaw = UserSystemInfoHelper::orderByRaw($request);
        $perPage = $request->per_page;
        //dd($perPage);
        if($request->user()->role === User::MODERATOR_ROLE_ID){
            $filter[] = ['role', '!=', User::ARHIVER_USER_ID];
        }
        $users = User::where($filter)->orderByRaw($orderByRaw)->paginate($perPage);
        if(!$users){
            return response()->json('По запросу ни чего не найдено', 404);
        }
        foreach ($users as $user){
            UserSystemInfoHelper::getUser($user);
        }
        return response()->json($users);
    }

    public function getDevices(Request $request)
    {
        try {
            $devices = DB::table('personal_access_tokens')
                ->select('name')
                ->where('name', 'like', $request->user()->email . '%')
                ->get();
            if($devices){
                foreach ($devices as $device) {
                    $item = explode('|', $device->email);
                    $device->name = $item[0];
                    $device->os = $item[1];
                    $device->browser = $item[2];
                }
                return response()->json($devices);
            }
        } catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }
}
