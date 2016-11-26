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

    public function handleRequest($request)
    {
        return $request;
    }

    public function getCoreComponents()
    {
        return array_merge(parent::getCoreComponents(), [
            'request' => ['class' => 'zy\web\Request'],
        ]);
    }
}