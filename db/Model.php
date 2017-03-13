<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/9  下午5:11
 * @since 1.0
 */

namespace ziyue\db;

use ziyue\core\Object;

class Model extends Object
{
    private static $instrance = null;
    private static $dbInstrance = null;

    /**
     * 初始化
     * @return null|Model
     */
    public static function model(){
        if(self::$instrance === null){
            self::$instrance = new self();
        }
        return self::$instrance;
    }

    public static function tableName(){
        throw new \Exception("Method not implemented");
    }
    public function rules(){
        throw new \Exception("Method not implemented");
    }

    public function select($fields = '*', $enableMaster = false){

    }
    public function selectAll($fields = '*', $enableMaster = false){}
    public function update(Array $setFields, $whereFields = []){}
    public function insert(Array $feilds){}
    public function delete($whereFeilds = []){}

    public function from($tableName){}
    public function join($tableName, $leftJoin = true){}
    public function limit($start, $size){}
    public function orderBy($order, $type){}
    public function groupBy($group){}

    public function query($sql, $bind = [], $enableMaster = true){
        return false;
    }

    public function beginTransaction(){}
    public function commitTransaction(){}
    public function rollbackTransaction(){}
}