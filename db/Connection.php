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

class Connection extends Object
{
    public $type = 'mysql';
    public $host = '127.0.0.1';
    public $port = '';
    public $user = '';
    public $password = '';
    public $dbName = '';
    public $enableSlave = false;
    public $slaves = [];

    public function getDbType(){
        return isset($this->getAdapter()[$this->type]);
    }
    public function getMasterDb(){

    }
    public function getSlaveDb(){

    }

    public function getAdapter(){
        return [
            'mysql' => ['class' => 'ziyue\db\adapter\MySql'],
            'mongodb' => ['class' => 'ziyue\db\adapter\Mongodb']
        ];
    }

}