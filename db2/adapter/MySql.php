<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/16  上午11:35
 * @since 1.0
 */

namespace ziyue\db\adapter;

use ziyue\db\Db;
use ziyue\exception\DatabaseException;
use ziyue\exception\InvalidConfigException;

class MySql implements Db
{
    /**
     * @var null 主库实例化对象
     */
    private static $instranceMaster = null;
    /**
     * @var null 从库实例化对象
     */
    private static $instranceSlaver = null;
    /**
     * @var null PDO
     */
    private $dsn = null;
    /**
     * @var string 账号
     */
    private $user = 'root';
    /**
     * @var string 密码
     */
    private $password = '';
    /**
     * @var string 编码
     */
    private $charset = 'utf-8';
    /**
     * @var string 表前缀
     */
    private $tblPrefix = '';
    /**
     * @var array 主库配置信息
     */
    public $master = [];
    /**
     * @var bool 是否启用从库
     */
    public $enableSlaves = false;
    /**
     * @var array 从库配置信息
     */
    public $slaves = [];

    /**
     * 获取主库实例化对象
     * @return null
     */
    public function getMaster()
    {
        if(static::$instranceMaster == null) {
            $this->setDSNInfo($this->master);
            self::$instranceMaster = new \PDO($this->dsn, $this->user, $this->password);
            self::$instranceMaster->exec('SET character_set_connection=' . $this->charset . ', character_set_results=' . $this->charset . ', character_set_client=binary');
        }
        return static::$instranceMaster;
    }

    /**
     * 获取从库实例化对象
     * @return null
     */
    public function getSlavers($slaveDbId = 0)
    {
        if($this->enableSlaves == false) {
            throw new InvalidConfigException("'enableSlaves' is false.");
        }
        if(static::$instranceSlaver == null) {
            if($slaveDbId == 0) {
                $slaveDbId = rand(1, sizeof($this->slaves)) - 1;
            }
            $this->setDSNInfo($this->slaves[$slaveDbId]);
            self::$instranceMaster->exec('SET character_set_connection=' . $this->charset . ', character_set_results=' . $this->charset . ', character_set_client=binary');
        }
        return static::$instranceSlaver;
    }

    /**
     * 配置数据库连接信息
     * @param $pdoParams
     * @throws InvalidConfigException
     */
    private function setDSNInfo($pdoParams){
        if (isset($pdoParams['tblPrefix'])) {
            $this->tblPrefix = trim($pdoParams['tblPrefix']);
            unset($pdoParams['tblPrefix']);
        }
        if ($diff = array_diff(array_keys($pdoParams), $this->pdoParams)) {
            throw new InvalidConfigException("Invalid Config '" . implode(',', $diff) . "'");
        }
        $this->dsn = 'mysql:host=' . $pdoParams['host'] . ';dbname=' . $pdoParams['dbName'];
        $this->user = $pdoParams['user'];
        $this->password = $pdoParams['password'];
        $this->charset = $pdoParams['charset'];
    }

    public function query($sql, Array $bind = [])
    {
        $sql = str_replace('{{%', $this->tblPrefix, $sql);
        $sql = str_replace('}}', '', $sql);

        $prepare = self::$instrance->prepare($sql);
        foreach ($bind as $feild => $value) {
            $feild = ':' . $feild;
            // todo : 类型判断
            $prepare->bindValue($feild, intval($value));
        }
        $prepare->execute();

        // exception
        if ('00000' !== $prepare->errorCode()) {
            throw new DatabaseException("SQL error : $sql ." . $prepare->errorInfo()[2]);
        }

        if ($this->getSqlType($sql) == 'select') {
            $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($this->getSqlType($sql) == 'insert') {
            $result = self::$instrance->lastInsertId();
        } else {
            $result = $prepare->rowCount();
        }
//        \Zy::p($sql);
//        \Zy::p($result);
//        \Zy::p($this->getSqlType($sql));
        return $result;
    }

    private function getSqlType($sql) {
        if (0 === strpos(strtolower($sql), 'select')) {
            return 'select';
        }
        if (0 === strpos(strtolower($sql), 'update')) {
            return 'update';
        }
        if (0 === strpos(strtolower($sql), 'insert') || 0 === strpos(strtolower($sql), 'replace')) {
            return 'insert';
        }
    }
}