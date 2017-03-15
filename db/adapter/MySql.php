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
    private $dsn = null;
    public $tblPrefix = '';
    public $master = [];
    public $enableSlaves = false;
    public $slaves = [];

    public function getMaster()
    {
        return new self();
    }

    public function getSlaves($slaveDbId = 1)
    {
        return new self();
    }

    public function getSlaveNum()
    {
        return sizeof($this->slaves);
    }

    public function query($sql, Array $bind = []){
        \Zy::p($sql);
        \Zy::p($bind);
    }
}