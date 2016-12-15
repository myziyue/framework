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

    public function runAction($route, $params)
    {
        $route = explode('/', $route);
        //controller
        $controllerName = ucwords(isset($route[0]) ? $route[0] : $this->defaultController);
        $controllerClass = $this->controllerNamespace . '\\' . $controllerName . 'Controller';
        $controllerFile = realpath(Zy::getAliasPath('@app') . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $controllerClass) . '.php');
        if(!file_exists($controllerFile)){
            throw new NotFoundHttpException("Page Not Found!");
            exit(1);
        }
        include_once  $controllerFile;
        $controller = Zy::createObject($controllerClass);
        // action
        $actionName = isset($route[1]) ? $route[1] : $this->defaultAction;
        $actionArray = explode('-', $actionName);
        foreach($actionArray as $key => $action){
            $actionArray[$key] = ucwords($action);
        }
        $actionName = 'action' . implode('', $actionArray);
        if(!method_exists($controller, $actionName)){
            throw new NotFoundHttpException("Page Not Found!");
            exit(1);
        }
        // run action
        $controller->$actionName();
    }

    public function getCoreComponents()
    {
        return array_merge(parent::getCoreComponents(), [
            'request' => ['class' => 'zy\web\Request'],
            'response' => ['class' => 'zy\web\Response'],
        ]);
    }
}