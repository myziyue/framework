<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  下午10:58
 * @since 1.0
 */

namespace zy\di;


class Instance
{
    // 仅有的属性，用于保存类名、接口名或者别名
    public $id;

    // 构造函数，仅将传入的ID赋值给 $id 属性
    protected function __construct($id)
    {
    }

    // 静态方法创建一个Instance实例
    public static function of($id)
    {
        return new static($id);
    }

    // 静态方法，用于将引用解析成实际的对象，并确保这个对象的类型
    public static function ensure($reference, $type = null, $container = null)
    {
    }

    // 获取这个实例所引用的实际对象，事实上它调用的是
    // yii\di\Container::get()来获取实际对象
    public function get($container = null)
    {
    }
}