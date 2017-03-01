<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午11:43
 * @since 1.0
 */

namespace ziyue\core;

use ziyue\exception\InvalidConfigException;

class Components extends Object
{
// 对象
    public static $instance = [];
    // 关系
    public static $definitions = [];

    public function set($name, $definition = []){
        if(isset(self::$instance[$name])){
            return true;
        }
        // 关系
        self::$definitions[$name] = $definition;
        // 对象
        if(isset($definition['class'])){
            self::$instance[$name] = \Ziyue::createComponent($name, $definition['class']);
        } else {
            self::$instance[$name] = \Ziyue::createComponent($name);
        }
        unset($definition['class']);
        // 初始化参数
        if($definition) {
            foreach ($definition as $property => $value){
                self::$instance[$name]->$property = $value;
            }
        }
    }

    public function get($name){
        if(!isset(self::$instance[$name])){
            throw new InvalidConfigException("Unknown component ID: $name");
        }
        return self::$instance[$name];
    }

}