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
    abstract public static function tableName();
    abstract public static function rules();

    abstract public function select($fields = '*', $enableMaster = false);
    abstract public function selectAll($fields = '*', $enableMaster = false);
    abstract public function update(Array $setFields, $whereFields = []);
    abstract public function insert(Array $feilds);
    abstract public function delete($whereFeilds = []);

    abstract public function from($tableName);
    abstract public function join($tableName, $leftJoin = true);
    abstract public function limit($start, $size);
    abstract public function orderBy($order, $type);
    abstract public function groupBy($group);

    abstract public function query($sql, $bind = [], $enableMaster = true);

    abstract public function beginTransaction();
    abstract public function commitTransaction();
    abstract public function rollbackTransaction();
}