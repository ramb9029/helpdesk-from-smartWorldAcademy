<?php

namespace App\Helpers;

use App\Models\Department;
use App\Models\Position;
use App\Models\Room;
use App\Models\User;

class UserSystemInfoHelper
{
    public static function orderByRaw($request)
    {
        if(empty($request->order))
            return 'id asc';
        return $request->order[0][0].' '.$request->order[0][1];
    }

    public static function getUser($user)
    {
                $user['department'] = Department::find($user->department_id)->name;
                $user['position'] = Position::find($user->position_id)->name;
                $user['room'] = Room::find($user->room_id)->number;
                return $user;
    }

    public static function getTokens()
    {

    }

    public static function getIp()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        else
            return 'UNKNOWN';
    }

    public static function getOs()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
            return "Unknown OS Platform";
        }
    }

    public static function getBrowsers()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/Trident/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/knoqueror/i' => 'Konqueror',
            '/ubrowser/i' => 'UC Browser',
            '/mobile/i' => 'Safari Browser',
        );

        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;

            }
            return "Unknown Browser";
        }
    }
}
