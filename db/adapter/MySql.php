<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/8  上午11:41
 * @since 1.0
 */

namespace ziyue\db\adapter;

use ziyue\core\ErrorException;
use ziyue\exception\InvalidConfigException;

class MySql extends Adapter
{
    private static $instrance = null;
    private $dsn = null;
    private $user = 'root';
    private $password = '';
    private $charset = 'utf-8';
    private $pdoParams = ['host', 'port', 'user', 'password', 'dbName', 'charset'];
    public $tblPrefix = '';
    public $master = [];
    public $enableSlaves = false;
    public $slaves = [];

    public function getMaster()
    {
        if(self::$instrance == null){
            $this->getPdoParams($this->master);
            self::$instrance = new \PDO($this->dsn, $this->user, $this->password);
        }
    }

    private function getPdoParams($pdoParams){
        if(isset($pdoParams['tblPrefix'])){
            $this->tblPrefix = trim($pdoParams['tblPrefix']);
            unset($pdoParams['tblPrefix']);
        }
        if($diff = array_diff(array_keys($pdoParams), $this->pdoParams)) {
            throw new InvalidConfigException("Invalid Config '" . implode(',', $diff) . "'");
        }
        $this->dsn = 'mysql:host=' . $pdoParams['host'] . ';dbname=' .  $pdoParams['dbName'];
        $this->user = $pdoParams['user'];
        $this->password = $pdoParams['password'];
        $this->charset = $pdoParams['charset'];
    }
    public function getSlaves($slaveDbId = 1)
    {
        if(self::$instrance == null){
            $this->getPdoParams($this->slaves[$slaveDbId-1]);
            self::$instrance = new \PDO($this->dsn, $this->user, $this->password);
            self::$instrance->exec('SET character_set_connection=' . $this->charset . ', character_set_results=' . $this->charset . ', character_set_client=binary');
        }
    }

    public function getSlaveNum()
    {
        return sizeof($this->slaves);
    }

    public function query($sql, Array $bind = []){
        $sql = str_replace('{{%', $this->tblPrefix, $sql);
        $sql = str_replace('}}', '', $sql);

        $prepare = self::$instrance->prepare($sql);
        foreach($bind as $feild => $value){
            $feild = ':' . $feild;
            $prepare->bindValue($feild, intval($value));
        }
        $prepare->execute();

        // exception
        if('00000' !== $prepare->errorCode()){
            throw new ErrorException("SQL error : $sql ." . $prepare->errorInfo()[2]);
        }

        if($this->getSqlType($sql) == 'select'){
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
        } elseif($this->getSqlType($sql) == 'insert') {
            $result = self::$instrance->lastInsertId();
        } else {
            $result = self::$instrance->rowCount();
        }
//        \Zy::p($sql);
//        \Zy::p($result);
        return $result;
    }

    private function getSqlType($sql){
        if(false !== strpos(strtolower($sql), 'select')){
            return 'select';
        }
        if(false !== strpos(strtolower($sql), 'update')){
            return 'update';
        }
        if(false !== strpos(strtolower($sql), 'insert')){
            return 'insert';
        }
    }
}