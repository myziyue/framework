<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/8  上午11:41
 * @since 1.0
 */

namespace ziyue\db\adapter;

class MySql extends Adapter
{
    public $prefixTbl = '';

    public function getMaster()
    {
        // TODO: Implement getMaster() method.
    }

    public function getSlaves($slaveDbId = 1)
    {
        // TODO: Implement getSlaves() method.
    }

    public function getSlaveNum()
    {
        return sizeof($this->slaves);
    }
}