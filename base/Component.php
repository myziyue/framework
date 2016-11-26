<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/22  下午2:01
 * @since 1.0
 */

namespace zy\base;

use Zy;
use zy\exception\InvalidConfigException;

class Component extends Object
{

    public $controllerNamespaces = 'app\\controllers';

    private $basePath = '';
    private $runtimePath = '';
    private $viewPath = '';
    private $layoutPath = '';

    public static function className()
    {
        return get_called_class();
    }

    public static function setInstance($instance)
    {
        if ($instance === null) {
            unset(Zy::$app->loadedModules[get_called_class()]);
        } else {
            Zy::$app->loadedModules[get_class($instance)] = $instance;
        }
    }

    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Zy::$app->loadedModules[$class]) ? Zy::$app->loadedModules[$class] : null;
    }

    public function getAppPath(){
        $this->basePath = Zy::getAliasPath('@app');
        if($this->basePath){
            return $this->basePath;
        }
        throw new InvalidConfigException('The "appPath" configuration for the Application is required.');
    }
    public function setAppPath($basePath){
        Zy::setAliasPath('@app', $basePath);
    }

    public function getRuntimePath(){
        if(Zy::getAliasPath('@runtime')){
            return Zy::getAliasPath('@runtime');
        }
        return Zy::getAliasPath('@app') . DIRECTORY_SEPARATOR . 'runtimes' . DIRECTORY_SEPARATOR;
    }

    public function getViewPath(){
        if(Zy::getAliasPath('@view')){
            return Zy::getAliasPath('@view');
        }
        return Zy::getAliasPath('@app') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
    }
    public function getLayoutPath(){
        if(Zy::getAliasPath('@layout')){
            return Zy::getAliasPath('@layout');
        }
        return Zy::getAliasPath('@app')
        . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
        . 'layouts' . DIRECTORY_SEPARATOR;
    }

    public function getTimeZone()
    {
        return date_default_timezone_get();
    }
    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }

}