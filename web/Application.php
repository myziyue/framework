<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午9:11
 * @since 1.0
 */
namespace zy\web;


class Application extends \zy\base\Application
{
    public $defaultController = 'ziyue';

    public function run(){
        return "Ziyue Web Application";
    }
}