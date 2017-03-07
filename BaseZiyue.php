<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/1  下午1:45
 * @since 1.0
 */
namespace ziyue;

use ziyue\exception\UnknownClassException;
use ziyue\log\Logger;

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
    public static $app = null;
    /**
     * @var array class map
     */
    public static $classMap = [];

    public static function error($log, $category = 'app'){
        \Zy::$app->getLogger()->log($log, Logger::LEVEL_ERROR, $category);
    }

    public static function warning($log, $category = 'app'){
        \Zy::$app->getLogger()->log($log, Logger::LEVEL_WARNING, $category);
    }

    public static function info($log, $category = 'app'){
        \Zy::$app->getLogger()->log($log, Logger::LEVEL_INFO, $category);
    }

    public static function trace($log, $category = 'app'){
        \Zy::$app->getLogger()->log($log, Logger::LEVEL_TRACE, $category);
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
            echo "<pre style='word-wrap: break-word; position: relative;z-index: 100%; padding: 10px;border-radius: 5px;background: #F5F5F5; border: 1px solid #AAA;font-size:14px;line-height: 18px; opacity: 0.9;'>"
                . print_r($data, true) . "</pre>";
        }
        exit(1);
    }

    public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
            if(!file_exists($classFile)){
                throw new UnknownClassException("Unable to find '$className' in file: $classFile. Namespace missing?");
            }

            include($classFile);
        } elseif (ZY_DEBUG && !class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
            throw new UnknownClassException("Unable to find '$className' . Namespace missing?");
        }
    }

    public static function createComponent($name, $class = ''){
        if($class === ''){
            if (isset(\Zy::$app->components[$name])){
                $class = \Zy::$app->components[$name];
            }else {
                throw new UnknownClassException('Getting unknown class: ' . $name);
            }
        }

        try {
            $componentObj = new $class;
        } catch (\Exception $ex) {
            throw new UnknownClassException('Getting unknown class: ' . $name . ' in file: ' . $class . '.');
        }
        return $componentObj;
    }
}