<?php


namespace App\Helpers;



class OrderHelper
{
    public static function checkUpdateRequest(array $request, array $check)
    {
        $check = array_intersect_key($request, array_flip($check));
        if ($check) {
            return 'error';
            //return response()->json(['status' => 'error', 'message' =>'Нет доступа!'], 403);
        }
    }
}
