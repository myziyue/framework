<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:02
 * @since 1.0
 */

namespace ziyue\web;

use ziyue\core\ExitException;

class Application extends \ziyue\core\Application
{

    public $catchAllAllowIp = ['127.0.0.1'];
    public function run()
    {
        try {
            $this->handleRequest();
        } catch (\Exception $ex){
            throw new ExitException($ex->getCode(), $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function handleRequest(){
        if($this->catchAll === null || in_array(\Zy::$app->request->getClientIp(), $this->catchAllAllowIp)){
            list($router, $params) = \Zy::$app->request->parseUrl();
        } else {
            $router = $this->catchAll['class'];
            $params = $this->catchAll;
            unset($params['class']);
        }
        \Zy::$app->runAction($router, $params);
    }

    public function getRequest(){
        return $this->get('request');
    }

    public function getResponse(){
        return $this->get('response');
    }

    /**
     * 默认核心组件
     * @return array
     */
    public function coreComponents() {
        return array_merge([
            'request' => ['class' => 'ziyue\web\Request'],
            'response' => ['class' => 'ziyue\web\Response']
        ], parent::coreComponents());
    }
}