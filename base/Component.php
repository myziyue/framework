<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/19  下午11:17
 * @since 1.0
 */
namespace zy\base;

class Component
{
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
    }

    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if(function_exists($getter)){
            return $this->$getter;
        } else {
            return \Ziyue::createObject($name);
        }
    }
}