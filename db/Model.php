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
use ziyue\exception\InvalidConfigException;
use ziyue\utils\TypeUtils;
use ziyue\db\ext\SelectModel;
use ziyue\db\ext\UpdateModel;
use ziyue\db\ext\InsertModel;
use ziyue\db\ext\DeleteModel;


class Model extends Object
{
    /**
     * sql类型
     */
    private $sqlTypes = ['SELECT', 'UPDATE', 'INSERT', 'DELETE'];
    /**
     * @var null Model对象
     */
    private static $instrance = null;
    /**
     * @var null 数据库对象
     */
    private $dbInstrance = null;
    /**
     * @var string 当前sql类型
     */
    private $sqlType = 'SELECT';
    /**
     * @var string 查询字段
     */
    private $select = '*';
    /**
     * @var string where条件
     */
    private $where = '';
    /**
     * @var array 连表查询
     */
    private $joinTable = [];
    private $multipleRows = false;
    public $cacheHandler = 'file';
    public $enableCache = false;

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

    private function getDbInstrance($enableMaster){
        if($enableMaster) {
            $this->dbInstrance = \Zy::$app->db->getMaster();
        } else {
            $this->dbInstrance = \Zy::$app->db->getSlaver();
        }
    }

    public static function tableName(){
        throw new \Exception("Method not implemented");
    }
    public function rules(){
        throw new \Exception("Method not implemented");
    }

    public function select($fields = '*', $enableMaster = false){
        $this->select = $fields;
        // todo : 字段验证
        $this->getDbInstrance($enableMaster);
        $this->multipleRows = false;
        echo $sql = $this->buildSql();
    }
    public function selectAll($fields = '*', $enableMaster = false){
        $this->select = $fields;
        // todo : 字段验证
        $this->getDbInstrance($enableMaster);
        $this->multipleRows = true;
    }
    public function update(Array $setFields){}
    public function insert(Array $feilds){}
    public function delete($whereFeilds = []){}

    public function from($tableName = ''){}
    public function where(Array $data, $type = 'AND') {
        if(TypeUtils::isEmpty($data)) {
            return false;
        }
        if(!in_array($type = strtoupper($type), ['AND', 'OR'])){
            throw new InvalidConfigException("Invalid Type '$type'");
        }

        foreach ($data as $feild => $value){
            $this->where .= ($this->where ? " $type (" : ' (');
            if(is_array($value)){
                $this->where .= $feild . ' in (:' . preg_replace("/.*[.]/", '', $value, 1) . '))';
            } else {
                $this->where .= $feild . ' = :' . preg_replace("/.*[.]/", '', $value, 1) . ')';
            }
        }
    }
    public function andWhere(Array $data) {
        $this->where($data);
    }
    public function orWhere(Array $data) {
        $this->where($data, 'OR');
    }

    public function join($tableName, $leftJoin = true){}
    public function limit($start, $size){}
    public function orderBy($order, $type){}
    public function groupBy($group){}

    public function query($sql, $bind = [], $enableMaster = true){
        return false;
    }

    private function buildSql(){
        if(!in_array($this->sqlType, $this->sqlTypes)){
            throw new InvalidConfigException("Invalid Type '$this->sqlType'");
        }
        $modelName = '\\ziyue\\db\\ext\\' . ucfirst(strtolower($this->sqlType)) . 'Model';
        return \Zy::createComponent($modelName, $modelName)->buildSql();
    }

    public function beginTransaction(){}
    public function commitTransaction(){}
    public function rollbackTransaction(){}
}