<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/19  下午11:17
 * @since 1.0
 */
namespace zy\base;

use Zy;

class Object
{
    public function __construct($config = [])
    {
        if (!empty($config)) {
            Zy::configure($this, $config);
        }
    }
}