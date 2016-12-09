<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/12/9  下午4:08
 * @since 1.0
 */

namespace zy\web;

use Zy;
use zy\base\Component;

class UrlManager extends Component
{
    public function parseRequst($request)
    {
        $requestURI = ltrim($request->getRequestUri(), '/');
        if(empty($requestURI)){
            return Zy::$app->defaultController . '/' . Zy::$app->defaultAction;
        }
        $route = explode('/', $requestURI);
        if(sizeof($route) == 1) {
            return $requestURI . '/' . Zy::$app->defaultAction;
        } elseif(sizeof($route) == 2) {
            return $requestURI;
        } else {
            throw new NotFoundHttpException("Page Not Found!");
            exit(1);
        }
    }
}