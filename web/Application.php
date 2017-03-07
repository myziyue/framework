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
    public function run()
    {
        try {
            $this->request();
            echo "ok";
        } catch (\Exception $ex){
            throw new ExitException($ex->getCode(), $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function request(){
        var_dump($routeUrl = \Zy::$app->request->get('id', Request::TYPE_INT));
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