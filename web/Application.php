<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午9:11
 * @since 1.0
 */
namespace zy\web;

use Zy;

class Application extends \zy\base\Application
{
    public $defaultController = 'ziyue';
    public $defaultAction = 'index';
    /**
     * 全局拦截
     * @var array
     *
     * ```php
     * [
     *     'url' => 'offline/notice',
     *     'param1' => 'value1',
     *     'param2' => 'value2',
     * ]
     * ```
     */
    public $catchAll = '';

    public function handleRequest($request)
    {
        if(!$this->catchAll){
            list ($route, $params) = $request->resolve();
        } else {
            $route = $this->catchAll['url'];
            $params = $this->catchAll;
            unset($params['url']);
        }
        $this->runAction($route, $params);
        return $request;
    }

    public function getCoreComponents()
    {
        return array_merge(parent::getCoreComponents(), [
            'request' => ['class' => 'zy\web\Request'],
            'response' => ['class' => 'zy\web\Response'],
        ]);
    }
}