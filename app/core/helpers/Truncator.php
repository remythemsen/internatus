<?php

namespace TheWall\Core\Helpers;


class Truncator {
    public static function excerpt($str, $length=10, $trailing='...') {

        // take off chars for the trailing
        $length-=mb_strlen($trailing);
        if (mb_strlen($str)> $length)
        {
            // string exceeded length, truncate and add trailing dots
            return mb_substr($str,0,$length).$trailing;
        }
        else
        {
            // string was already short enough, return the string
            $res = $str;
        }

        return $res;
    }
} 