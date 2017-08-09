<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午4:07
 * @since 1.0
 */

namespace ziyue;

use ziyue\exception\InvalidConfigException;
use ziyue\exception\UnknownClassException;
use ziyue\log\Logger;

/**
 * 定义项目根目录
 */
defined('ZIYUE_PATH') OR define('ZIYUE_PATH', __DIR__);
/**
 * 是否启用调试模式
 */
defined('ZIYUE_DEBUG') OR define('ZIYUE_DEBUG', false);
/**
 * 运行环境，prod：生产环境,test：测试环境, dev：开发环境;
 */
defined('ZIYUE_ENV') OR define('ZIYUE_ENV', 'prod');
/**
 * 应用开始时间
 */
defined('ZIYUE_BEGIN_TIME') OR define('ZIYUE_BEGIN_TIME', microtime(true));

class Ziyue{
    public static $classmap = [];
    public static $app;
    public static $container;
    public static $aliases = ['@ziyue' => __DIR__];

    public static function getVersion(){
        return "0.0.1-dev";
    }

    public static function powered(){
        return '<a href="http://framework.myziyue.com/" rel="external">MyZiyue Framework</a>';
    }

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
                . print_r($data, true) 
                . "</pre>";
        }
    }

    public static function autoload($className)
    {
        if (isset(static::$classmap[$className])) {
            $classFile = static::$classmap[$className];
            if(!file_exists($classFile)){
                throw new UnknownClassException("Unable to find '$className' in file: $classFile. Namespace missing?");
            }
        }elseif(strpos($className, "\\") !== false){
            $classNameArray = explode("\\", $className);
            $aliasName = isset($classNameArray[0]) ? $classNameArray[0] : 'app';
            $classNameFile = str_replace('\\', DIRECTORY_SEPARATOR, $className);

            $classFile = \Zy::getAlias('@'. $aliasName) . str_replace($aliasName, '', $classNameFile) . '.php';
            if(!file_exists($classFile)){
                throw new UnknownClassException("Unable to find '$className' in file: $classFile. Namespace missing?");
            }
        }else {
            return ;
        }
        include($classFile);

        if (ZIYUE_DEBUG && !class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
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

    public static function getAlias($aliasName){
        if(strpos($aliasName, '@') !== 0){
            throw new InvalidConfigException("Invalid alias ： $aliasName");
        }
        $aliasName = ltrim($aliasName, '@');
        if(isset(self::$aliases[$aliasName])){
            return self::$aliases[$aliasName];
        }
        throw new InvalidConfigException("Invalid alias ： $aliasName");
    }

    public static function setAlias($aliasName, $aliasPath){
        if(strpos($aliasName, '@') !== 0){
            throw new InvalidConfigException("Invalid alias ： $aliasName");
        }
        $aliasName = ltrim($aliasName, '@');

        if(isset(self::$aliases[$aliasName])){
            return true;
        }
        self::$aliases[$aliasName] = $aliasPath;
        return true;
    }
}