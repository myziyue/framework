<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/1  下午1:45
 * @since 1.0
 */
namespace zy;

use zy\log\Logger;
use zy\exception\UnknownClassException;
use zy\exception\InvalidConfigException;

// 框架加载开始时间
defined('ZY_BEGIN_TIME') or define('ZY_BEGIN_TIME', microtime(true));
// 框架根目录
defined('ZY_PATH') or define('ZY_PATH', __DIR__);
// 是否开启框架Debug模式
defined('ZY_DEBUG') or define('ZY_DEBUG', false);
defined('ZY_ENV_TEST') or define('ZY_ENV_TEST', 'test');
defined('ZY_ENV_DEV') or define('ZY_ENV_DEV', 'dev');
defined('ZY_ENV_PROD') or define('ZY_ENV_PROD', 'prod');

defined('ZY_ENABLE_ERROR_HANDLER') or define('ZY_ENABLE_ERROR_HANDLER', true);

class BaseZiyue
{
    /**
     * @var string 版本
     */
    private static $version = '0.1';
    /**
     * @var null 框架入口对象
     */
    public static $app = null;
    /**
     * @var array 类映射地图
     */
    public static $classMap = [];
    /**
     * @var 容器对象
     */
    public static $container;

    public static $aliasPath = [
        '@zy' => __DIR__
    ];

    public static function run()
    {
        return "OK";
    }

    /**
     * 自动加载
     * @param $className
     */
    public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
        } elseif (strpos($className, '\\') !== false) {
            $classFile = str_replace('\\', '/', $className) . '.php';
            if ($classFile === false || !is_file($classFile)) {
                return;
            }
        } else {
            return;
        }

        include($classFile);

        if (ZY_DEBUG && !class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
            throw new UnknownClassException("Unable to find '$className' in file: $classFile. Namespace missing?");
        }
    }

    /**
     * 创建对象
     * @param $type
     * @param array $params
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function createObject($type, $params = [])
    {
        // 字符串，代表一个类名、接口名、别名。
        if (is_string($type)) {
            return static::$container->get($type, $params);
            // 是个数组，代表配置数组，必须含有 class 元素。
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            // 调用DI容器的get() 来获取、创建实例
            return static::$container->get($class, $params, $type);
        } elseif (is_callable($type, true)) {// 是个PHP callable则调用其返回一个具体实例。
            // 是个PHP callable，那就调用它，并将其返回值作为服务或组件的实例返回
            return call_user_func($type, $params);
        } elseif (is_array($type)) { // 是个数组但没有 class 元素，抛出异常
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');
        } else { // 其他情况，抛出异常
            throw new InvalidConfigException( "Unsupported configuration type: " . gettype($type));
        }

    }

    /**
     * 翻译提示信息
     * @param $message
     * @param array $params
     * @param string $category
     * @param string $language
     * @return mixed
     */
    public static function t($message, $params = [], $category = 'app', $language = 'US_en')
    {
        foreach ($params as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

    /**
     * 版本信息
     * @return string
     */
    public static function version()
    {
        return self::$version;
    }

    /**
     * 技术支持信息
     * @return mixed
     */
    public static function powered()
    {
        return \Zy::t('Powered by {ziyue}',
            ['ziyue' => '<a href="http://framework.myziyue.com/" rel="external">MyZiyue Framework</a>']);
    }

    public static function error($message, $category = 'application')
    {
        static::$app->getLogger()->log($message, Logger::LEVEL_ERROR, $category);
    }

    public static function info($message, $category = 'application')
    {
        static::$app->getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }

    public static function warning($message, $category = 'application')
    {
        static::$app->getLogger()->log($message, Logger::LEVEL_WARNING, $category);
    }

    public static function trace($message, $category = 'application')
    {
        static::$app->getLogger()->log($message, Logger::LEVEL_TRACE, $category);
    }

    /**
     * 打印调试信息
     * @param $data
     */
    public static function p($data)
    {
        if (is_bool($data)) {
            var_dump($data);
        } elseif (is_null($data)) {
            var_dump($data);
        } else {
            echo "<pre style='position: relative;z-index: 100%; padding: 10px;border-radius: 5px;background: #F5F5F5; border: 1px solid #AAA;font-size:14px;line-height: 18px; opacity: 0.9;'>"
                . print_r($data, true) . "</pre>";
        }
    }

    public static function configure($object, $properties)
    {
        error_reporting(-1);
        ini_set('display_errors','on');
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    public static function getAliasPath($alias){
        if (strncmp($alias, '@', 1)) {
            // not an alias
            return $alias;
        }
        return isset(static::$aliasPath[$alias]) ? static::$aliasPath[$alias] : '';
    }

    public static function setAliasPath($alias, $path){
        if (strncmp($alias, '@', 1)) {
            // not an alias
            return false;
        }
        static::$aliasPath[$alias] = $path;
        return true;
    }

}