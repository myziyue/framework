<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:02
 * @since 1.0
 */

namespace ziyue\core;

use ziyue\db\Connection;
use ziyue\exception\NotFoundHttpException;
use ziyue\exception\UnknownClassException;
use ziyue\exception\InvalidConfigException;

class Application extends Components
{
    public $name = "My Application Name";
    public $version = "0.0.1";
    public $charset = "UTF-8";
    public $language = "Zh-cn";
    public $appPath = '';
    public $defaultNameSpace = "\\app\\controllers";
    public $defaultController = 'Index';
    public $defaultAction = "Index";
    protected $components = [];
    public $catchAll = null;

    public function __construct($config)
    {
        \Zy::$app = $this;
        $this->preInit($config);
        $this->registerErrorHandler();
        $this->bootstrap($config);
    }

    public function preInit(&$config){
        $this->components = $this->coreComponents();
        // 合并组件
        if(!isset($config['components'])){
            return false;
        }
        foreach ($config['components'] as $component => $value){
            $this->components[$component] = $value;
        }
        unset($config['components']);

        foreach ($config as $property => $value){
            $this->$property = $value;
        }
    }

    public function bootstrap($config){
        foreach ($this->components as $id => $definition){
            $this->set($id, $definition);
        }

        \Zy::setAlias('@app', $this->appPath);
        \Zy::setAlias('@ziyue', ZY_PATH);
    }

    public function run(){
        try {
            echo "ok";
        } catch (\Exception $ex){
            throw new ExitException($ex->getCode(), $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    protected function registerErrorHandler(){
        $this->set('errorHandler', $this->components['errorHandler']);
        $this->get('errorHandler')->register();
    }
    public function getErrorHandler(){
        return $this->get('errorHandler');
    }

    public function getDb(){
        return $this->get('db');
    }

    public function getLogger(){
        $this->set('logger', $this->components['logger']);
        return $this->get('logger');
    }

    public function runAction($router, $params = []){
        $router = explode('/', trim($router, '/'));
        // controller
        if(strpos($router[0], '-') === false){
            $controller = ucfirst($router[0]) . 'Controller';
        } else {
            $routerArr = explode($router[0], '-');
            foreach ($routerArr as $key => $value){
                $routerArr[$key] = ucfirst($value);
            }
            $controller = implode('', $routerArr) . 'Controller';
        }

        // action
        if(strpos($router[1], '-') === false){
            $action = 'action'. ucfirst($router[1]);
        } else {
            $routerArr = explode($router[1], '-');
            foreach ($routerArr as $key => $value){
                $routerArr[$key] = ucfirst($value);
            }
            $action = 'action' . implode('', $routerArr);
        }

        $className = \Zy::$app->defaultNameSpace . '\\' . $controller;
        $classFile = \Zy::$app->appPath . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php';
        if(!file_exists($classFile)) {
            throw new NotFoundHttpException('Page not found.');
        }
        include_once $classFile;
        \Zy::$app->request->queryParams = $params;
        (new $className())->$action();
    }

    /**
     * 默认核心组件
     * @return array
     */
    public function coreComponents() {
        return [
            'errorHandler' => ['class' => 'ziyue\core\ErrorHandler'],
            'logger' => ['class' => 'ziyue\log\Logger']
        ];
    }
}