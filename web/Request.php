<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/2  下午6:26
 * @since 1.0
 */

namespace ziyue\web;

use ziyue\base\Object;
use ziyue\exception\InvalidValueException;

class Request extends Object
{
    const TYPE_INT = 0;
    const TYPE_FLOAT = 1;
    const TYPE_STRING = 2;

    public $queryParams = [];

    /**
     * 解析url地址
     * @return array
     */
    public function parseUrl(){
        $uri = $_SERVER['REQUEST_URI'];
        if(strpos('?', $uri) === false){
            // 默认首页
            if($uri == '/'){
                $router = \Zy::$app->defaultController . '/' . \Zy::$app->defaultAction;
                $params = [];
                return [$router, $params];
            }

            $uriArr = explode('/', trim($uri, '/'));
            // 只有控制器
            if (sizeof($uri) == 1){
                $router = $uriArr[0] . '/' . \Zy::$app->defaultAction;
                $params = [];
                return [$router, $params];
            }
            // 完整
            $router = $uriArr[0] . '/' . $uriArr[1];
            for($i = 2; $i < sizeof($uriArr); $i++){
                $params[$uriArr[$i]] = $uriArr[$i+1];
                $i++;
            }
        } else {
            $uriArr = explode('?', $uri);
            $router = $uriArr[0];
            $params = $_GET;
        }
        return [$router, $params];
    }
    public function get($name = '', $default = ''){
        if($name){
            return isset($this->queryParams[$name]) ? $this->queryParams[$name] : NULL;
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

    public function getHostInfo(){
        $port = $_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443 ? '' : $_SERVER['SERVER_PORT'];
        $protocol = $this->isSsl() ? 'https' : 'http';
        return $protocol . "://" . $this->getServerName() . ($port ? ':' .$port : '') . '/';
    }

    public function getServerName(){
        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl(){
        return ((isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) ||
            (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] ))) ? true : false;
    }

    /**
     * @return array|false|null|string
     */
    public function getClientIp()
    {
        static $realip = NULL;

        if ($realip !== NULL) {
            return $realip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
            } elseif (isset($_SERVER ['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER ['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER ['REMOTE_ADDR'])) {
                    $realip = $_SERVER ['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip [0]) ? $onlineip [0] : '0.0.0.0';

        return $realip;
    }
}