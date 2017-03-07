<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/2  下午6:26
 * @since 1.0
 */

namespace ziyue\web;

use ziyue\core\Object;
use ziyue\exception\InvalidValueException;

class Request extends Object
{
    const TYPE_INT = 0;
    const TYPE_FLOAT = 1;
    const TYPE_STRING = 2;

    public function get($name = '', $default = ''){
        if($name){
            return isset($_GET[$name]) ? $_GET[$name] : NULL;
        }
        $value = $_GET;
    }

    public function post($name ='', $default = ''){
        if($name){
            return isset($_POST[$name]) ? $_POST[$name] : NULL;
        }
        $value = $_POST;
    }

    public function getParamsValue(){
        switch ($type){
            case self::TYPE_INT:
                $value = is_numeric($value) ? intval($value) : $default;
                break;
            case self::TYPE_FLOAT:
                $value = is_numeric($value) ? floatval($value) : $default;
                break;
            case self::TYPE_STRING:
                $value = empty($value) ? $default : trim($value);
                break;
            default:
                throw new InvalidValueException("Invalid type of the '$name'");
                break;
        }
        return $value;
    }

    public function isPOST(){
        return $this->getMethod() === 'POST';
    }

    public function isGet(){
        return $this->getMethod() === 'GET';
    }

    public function isHead()
    {
        return $this->getMethod() === 'HEAD';
    }

    public function getMethod()
    {
        if (isset($_POST[$this->methodParam])) {
            return strtoupper($_POST[$this->methodParam]);
        }

        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }
}