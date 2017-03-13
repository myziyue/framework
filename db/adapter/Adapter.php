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

abstract class AbstractDb extends Object
{
    abstract public function getMaster();
    abstract public function getSlaves();

    abstract public function limit($start, $size);
    abstract public function orderBy($order, $type);
    abstract public function groupBy($group);

    abstract public function select($fields = '*', $enableMaster = false);
    abstract public function selectAll($fields = '*', $enableMaster = false);
    abstract public function update(Array $setFields, $whereFields = []);
    abstract public function insert(Array $feilds);
    abstract public function delete($whereFeilds = []);

    abstract public function query($sql, $enableMaster = true);

    abstract public function beginTransaction();
    abstract public function commitTransaction();
    abstract public function rollbackTransaction();
}