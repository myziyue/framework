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

    public function getSlaves()
    {
        // TODO: Implement getSlaves() method.
    }

    public static function tableName()
    {
        // TODO: Implement tableName() method.
    }

    public static function rules()
    {
        // TODO: Implement rules() method.
    }

    public function select($fields = '*', $enableMaster = false)
    {
        // TODO: Implement select() method.
    }

    public function selectAll($fields = '*', $enableMaster = false)
    {
        // TODO: Implement selectAll() method.
    }

    public function update(Array $setFields, $whereFields = [])
    {
        // TODO: Implement update() method.
    }

    public function insert(Array $feilds)
    {
        // TODO: Implement insert() method.
    }

    public function delete($whereFeilds = [])
    {
        // TODO: Implement delete() method.
    }

    public function from($tableName)
    {
        // TODO: Implement from() method.
    }

    public function join($tableName, $leftJoin = true)
    {
        // TODO: Implement join() method.
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

    public function query($sql, $bind = [], $enableMaster = true)
    {
        // TODO: Implement query() method.
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