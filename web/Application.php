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
            try {
                list ($route, $params) = $request->resolve();
            } catch (UrlNormalizerRedirectException $e) {
                $url = $e->url;
                if (is_array($url)) {
                    if (isset($url[0])) {
                        // ensure the route is absolute
                        $url[0] = '/' . ltrim($url[0], '/');
                    }
                    $url += $request->getQueryParams();
                }
                return $this->getResponse()->redirect(Url::to($url, $e->scheme), $e->statusCode);
            }
        } else {
            $route = $this->catchAll['url'];
            $params = $this->catchAll;
            unset($params['url']);
        }
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