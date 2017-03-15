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
     * @var null 表名
     */
    private $tblName = null;
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
     * @var array 绑定条件
     */
    private $binData = [];
    /**
     * @var string group by条件
     */
    private $groupBy = '';
    /**
     * @var string order by条件
     */
    private $orderBy = '';
    /**
     * @var string limit条件
     */
    private $limit = '';
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
        throw new \Exception("Method '" . __FUNCTION__ . "' not implemented");
    }
    public function rules(){
        throw new \Exception("Method '" . __FUNCTION__ . "' not implemented");
    }

    public function select($fields = '*', $enableMaster = false){
        $this->select = $fields;
        // todo : 字段验证
        $this->getDbInstrance($enableMaster);
        $this->multipleRows = false;
        $sql = $this->buildSql();
        \Zy::p($sql);
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

    /**
     * @param $tableName
     * @return null|Model
     */
    public function from($tableName) {
        $this->tblName = $tableName;
        return static::model();
    }

    /**
     * @param array $data
     * @param string $type
     * @return bool|null|Model
     * @throws InvalidConfigException
     */
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
                $this->where .= $feild . ' in (:' . preg_replace("/.*[.]/", '', $feild, 1) . '))';
            } else {
                $this->where .= $feild . ' = :' . preg_replace("/.*[.]/", '', $feild, 1) . ')';
            }
        }
        $this->binData = $data;
        return static::model();
    }
    public function andWhere(Array $data) {
        $this->where($data);
    }
    public function orWhere(Array $data) {
        $this->where($data, 'OR');
    }

    public function join($tableName, $leftJoin = true){}

    public function limit($limitOffset = 0, $limitSize = 1){
        if($limitOffset < 0) {
            $limitOffset = 0;
        }
        if($limitSize <= 0){
            $limitSize = 1;
        }
        $this->binData[] = ['limit_offset' => $limitOffset, 'limit_size' => $limitSize];
        $this->limit = "LIMIT :limit_offset,:limit_size";
        return static::model();
    }
    public function orderBy(Array $orderBy = []){
        foreach($orderBy as $order => $type) {
            //TODO ： order字段判断
            if(in_array(strtoupper($type), ['DESC', 'ASC'])){
                $order = str_replace('.', '_', $order);
                $this->binData[] = ["orderby_$order" => $order];
                $this->orderBy .= ($this->orderBy ? ',' : '') . ":orderby_$order $type";
            } else {
                throw new InvalidConfigException("Invalid Order by type '$type'");
            }
        }
        return static::model();
    }
    public function groupBy(Array $groupBy = []){
        foreach($groupBy as $group) {
            //TODO ： order字段判断
            $group = str_replace('.', '_', $group);
           $this->groupBy .= ($this->groupBy ? ',' : '') . " $group";
        }
        return static::model();
    }

    public function query($sql, $bind = [], $enableMaster = true){
        return false;
    }

    private function buildSql(){
        if(!in_array($this->sqlType, $this->sqlTypes)){
            throw new InvalidConfigException("Invalid Type '$this->sqlType'");
        }
        $modelName = '\\ziyue\\db\\ext\\' . strtolower(\Zy::$app->db->type) . '\\' . ucfirst(strtolower($this->sqlType)) . 'Model';
        return \Zy::createComponent($modelName, $modelName)->buildSql(static::model());
    }

    public function beginTransaction(){}
    public function commitTransaction(){}
    public function rollbackTransaction(){}

    /**
     * @return string
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return string
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return null
     */
    public function getTableName()
    {
        return $this->tblName;
    }

    /**
     * @return string
     */
    public function getGroupBy()
    {
        return $this->groupBy ? ' GROUP BY ' . $this->groupBy : '';
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy ? ' ORDER BY ' . $this->orderBy : '';
    }

    /**
     * @return string
     */
    public function getLimit()
    {
        return $this->limit;
    }
}