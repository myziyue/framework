<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/1  下午1:45
 * @since 1.0
 */
namespace zy;

defined('ZY_BEGIN_TIME') or define('ZY_BEGIN_TIME', microtime(true));
defined('ZY_PATH') or define('ZY_PATH', __DIR__);
defined('ZY_DEBUG') or define('ZY_DEBUG', false);

class BaseZiyue
{
    /**
     * @var string 版本
     */
    private static $version = 'Version 0.1 beta';
    /**
     * @var null 框架入口对象
     */
    private static $app = null;
    /**
     * @var array 类映射地图
     */
    private static $classMap = [];
    /**
     * @var
     */
    private static $container;

    public static function run()
    {
        return "OK";
    }

    public static function autoload($class)
    {

    }

    public static function version()
    {
        return self::$version;
    }

    public static function powered()
    {

    }

    public static function error()
    {

    }

    public static function info()
    {

    }

    public static function warning()
    {

    }

    public static function trace()
    {

    }

    public static function p($data){
        if(is_bool($data)){
            var_dump($data);
        } elseif (is_null($data)){
            var_dump($data);
        } else {
            echo "<pre style='position: relative;z-index: 100%; padding: 10px;border-radius: 5px;background: #F5F5F5; border: 1px solid #AAA;font-size:14px;line-height: 18px; opacity: 0.9;'>"
                . print_r($data, true) . "</pre>";
        }
    }

    public static function t($message, $params = [], $category = 'app', $language = 'US_en')
    {
    }

}