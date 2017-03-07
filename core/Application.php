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
    public $defaultController = 'IndexController';
    public $defaultAction = "actionIndex";
    protected $components = [];
    public $catchAll = '';

    public function __construct($config)
    {
        \Ziyue::$app = $this;
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