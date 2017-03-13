<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/13  上午10:03
 * @since 1.0
 */

namespace ziyue\utils;


class TypeUtils
{
    public static function isNull($value){
        return $value === NULL;
    }

    public static function isEmpty($value) {
        return $value == null || empty($value);
    }

    public static function isNumber($value) {
        return is_numeric($value);
    }

    public static function isInt($value) {
        return self::isNumber($value) && floor($value) == $value;
    }

    public static function isFloat($value) {
        return self::isNumber($value) && floor($value) != $value;
    }
}