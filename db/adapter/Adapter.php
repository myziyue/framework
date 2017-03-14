<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/9  下午4:39
 * @since 1.0
 */

namespace ziyue\db\adapter;

use ziyue\core\Object;

abstract class Adapter extends Object
{
    public $enableProf = false;
    public $enableCacheTable = false;

    abstract public function getMaster();
    abstract public function getSlaves();
    abstract public function getSlaveNum();
}