<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/8  上午11:41
 * @since 1.0
 */

namespace ziyue\db\adapter;

use ziyue\core\Object;
use ziyue\db\adapter\AbstractDb;

class MySql extends AbstractDb
{
    /**
     *
     */
    public function getMasterDb(){
        $dbAdapterClass = $this->getDbType();

    }
    public function getSlaveDb(){

    }

    public function limit($start, $size)
    {
        // TODO: Implement limit() method.
    }

    public function orderBy($order, $type)
    {
        // TODO: Implement orderBy() method.
    }

    public function groupBy($group)
    {
        // TODO: Implement groupBy() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }

    public function selectAll()
    {
        // TODO: Implement selectAll() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function insert()
    {
        // TODO: Implement insert() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commitTransaction()
    {
        // TODO: Implement commitTransaction() method.
    }

    public function rollbackTransaction()
    {
        // TODO: Implement rollbackTransaction() method.
    }
}