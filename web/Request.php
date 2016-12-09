<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/26  下午4:00
 * @since 1.0
 */

namespace zy\web;

use Zy;
use zy\base\Component;

class Request extends Component
{
    private $_queryParams = null;
    private $_postParams = null;
    private $_hostInfo = null;
    private static $realip = null;

    public function send()
    {
    }

    public function resolve()
    {
        $result = Zy::$app->getUrlManager()->parseRequst($this);
        return [$result, $this->getQueryParams()];
    }

    public function get($name = null, $defaultValue = null)
    {
        if ($name === null) {
            return $this->getQueryParams();
        } else {
            return $this->getQueryParam($name, $defaultValue);
        }
    }

    public function getQueryParams()
    {
        if ($this->_queryParams === null) {
            return $this->_queryParams = $_GET;
        }
        return $this->_queryParams;
    }

    public function getQueryParam($name, $defaultValue = null)
    {
        $queryParams = $this->getQueryParams();
        return isset($queryParams[$name]) ? $queryParams[$name] : $defaultValue;
    }

    public function post($name = null, $defaultValue = null)
    {
        if ($name === null) {
            return $this->getPostParams();
        } else {
            return $this->getPostParam($name, $defaultValue);
        }
    }

    public function getPostParams()
    {
        if ($this->_queryParams === null) {
            return $this->_queryParams = $_POST;
        }
        return $this->_queryParams;
    }

    public function getPostParam($name, $defaultValue = null)
    {
        $queryParams = $this->getPostParams();
        return isset($queryParams[$name]) ? $queryParams[$name] : $defaultValue;
    }

    public function getMethod()
    {
        if (isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }

        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * 获取用户IP地址
     * @return null|string
     */
    public function getUserIP()
    {
        if (self::$realip !== null) {
            return self::$realip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        self::$realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER ['HTTP_CLIENT_IP'])) {
                self::$realip = $_SERVER ['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER ['REMOTE_ADDR'])) {
                    self::$realip = $_SERVER ['REMOTE_ADDR'];
                } else {
                    self::$realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                self::$realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                self::$realip = getenv('HTTP_CLIENT_IP');
            } else {
                self::$realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", self::$realip, $onlineip);
        self::$realip = !empty($onlineip [0]) ? $onlineip [0] : '0.0.0.0';

        return self::$realip;
    }

    /**
     * 将IP转换格式(用于在数据库查询得到相应国家)
     * param $target String
     * return
     */
    public function numFromIp($ip)
    {
        $ip = explode(".", $ip);
        for ($i = 0; $i < 4; $i++) {
            if ($i == 0) {
                $ip[$i] = $ip[$i] * 256 * 256 * 256;
            } elseif ($i == 1) {
                $ip[$i] = $ip[$i] * 256 * 256;
            } elseif ($i == 2) {
                $ip[$i] = $ip[$i] * 256;
            }
            $newip = $ip[0] + $ip[1] + $ip[2] + $ip[3];
        }
        return $newip;
    }

    /**
     * 将数字转换为IP，进行上面函数的逆向过程
     */
    public function longToIp($long)
    {
        // Valid range: 0.0.0.0 -> 255.255.255.255
        if ($long < 0 || $long > 4294967295) {
            return false;
        }
        $ip = "";
        for ($i = 3; $i >= 0; $i--) {
            $ip .= (int)($long / pow(256, $i));
            $long -= (int)($long / pow(256, $i)) * pow(256, $i);
            if ($i > 0) {
                $ip .= ".";
            }
        }
        return $ip;
    }

    /**
     * 判断是否为https请求
     *
     * @return bool
     */
    public function isSSL()
    {
        if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
            return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }

    /**
     * 获取服务器域名
     * @return null
     */
    public function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }

    /**
     * 获取服务器端口
     * @return int
     */
    public function getServerPort()
    {
        return $this->isSSL() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
    }

    public function getRequestUri()
    {
        $serverURI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
        $serverURI = explode('?', $serverURI);
        return $serverURI[0];
    }

    /**
     * 获取服务器域名地址
     *
     * @return null|string
     */
    public function hostInfo()
    {
        if ($this->_hostInfo === null) {
            $secure = $this->isSSL();
            $http = $secure ? 'https' : 'http';
            if (isset($_SERVER['HTTP_HOST'])) {
                $this->_hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
            } elseif (isset($_SERVER['SERVER_NAME'])) {
                $this->_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
                $port = $this->getServerPort();
                if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                    $this->_hostInfo .= ':' . $port;
                }
            }
        }
        return $this->_hostInfo;
    }

    public function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

}