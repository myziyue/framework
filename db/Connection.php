<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:46
 * @since 1.0
 */
namespace ziyue\db;

use ziyue\core\Object;
use ziyue\exception\InvalidConfigException;

class Connection extends Object
{
    private static $dbInstrance = null;
    public $type = 'mysql';
    public $master = [];
    public $enableSlaves = false;
    public $slaves = [];

    /**
     * 获取db适配器类
     * @return mixed
     * @throws InvalidConfigException
     */
    public function init(){
        if(!isset($this->getAdapter()[$this->type])) {
            throw new InvalidConfigException("Unknown type : $this->type");
        }

        if(self::$dbInstrance === null){
            $adapterClass = $this->getAdapter()[$this->type];
            self::$dbInstrance = new $adapterClass();
        }
    }

    public function getMaster(){
        $this->init();
        return self::$dbInstrance->getMaster();
    }

    public function getSlaver($slaveDbId = ''){
        $this->init();
        if($slaveDbId == '') {
            $slaveDbId = $this->getSlaveId();
        }
        return self::$dbInstrance->getSlaves($slaveDbId);
    }

    public function getSlaveId(){
        return rand(1, self::$dbInstrance->getSlaveNum());
    }

    public function getAdapter(){
        return [
            'mysql' => '\ziyue\db\adapter\MySql',
            'mongodb' => '\ziyue\db\adapter\Mongodb'
        ];
    }

}