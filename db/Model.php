<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/9  下午5:11
 * @since 1.0
 */

namespace ziyue\db;

use ziyue\base\Object;
use ziyue\exception\InvalidValueException;
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
    /**
     * @var array 更新字段
     */
    private $updateFeilds = [];
    /**
     * @var array 添加字段
     */
    private $insertFeilds = [];
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
        $this->limit = " LIMIT 1";
        $sql = $this->buildSql();
        $data = $this->query($sql, $this->binData, $enableMaster);
        return isset($data[0]) ? $data[0] : $data;
    }
    public function selectAll($fields = '*', $enableMaster = false){
        $this->select = $fields;
        // todo : 字段验证
        $sql = $this->buildSql();
        return $this->query($sql, $this->binData, $enableMaster);
    }

    /**
     * from
     * @param $tableName
     * @return null|Model
     */
    public function from($tableName) {
        $this->tblName = $tableName;
        return static::model();
    }

    /**
     * where
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
            throw new InvalidValueException("Invalid Type '$type'");
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

    /**
     * join
     * @param $tableName
     * @param bool $leftJoin
     * @return null|Model
     * @throws InvalidValueException
     */
    public function join($tableName, $leftJoin = true){
        if(preg_match_all('/{{%([a-zA-Z_]+)}}(\s+)(AS|as){1}(\s+)([a-zA-Z_]+)(\s)(ON|on){1}(\s)([a-zA-Z_]+(.){1}[a-zA-Z_]+(=){1}[a-zA-Z_]+(.){1}[a-zA-Z_]+)/', $tableName)){
            $joinType = $leftJoin ? 'LEFT' : 'RIGHT';
            $this->joinTable[] = "$joinType JOIN " . $tableName;
        } else {
            throw new InvalidValueException("Invalid table name '$tableName'");
        }
        return static::model();
    }
    public function leftJoin($tableName) {
        return $this->join($tableName, true);
    }
    public function rightJoin($tableName) {
        return $this->join($tableName, false);
    }

    /**
     * limit
     * @param int $limitOffset
     * @param int $limitSize
     * @return null|Model
     */
    public function limit($limitOffset = 0, $limitSize = 1){
        if($limitOffset < 0) {
            $limitOffset = 0;
        }
        if($limitSize <= 0){
            $limitSize = 1;
        }
        $this->limit = ' LIMIT ' . intval($limitOffset) . ',' . intval($limitSize);
        return static::model();
    }
    public function orderBy(Array $orderBy = []){
        foreach($orderBy as $order => $type) {
            //TODO ： order字段判断
            if(in_array(strtoupper($type), ['DESC', 'ASC'])){
                $order = str_replace('.', '_', $order);
                $this->orderBy .= ($this->orderBy ? ',' : '') . "$order $type";
            } else {
                throw new InvalidValueException("Invalid Order by type '$type'");
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

    private function buildSql(){
        if(!in_array($this->sqlType, $this->sqlTypes)){
            throw new InvalidConfigException("Invalid Type '$this->sqlType'");
        }
        $modelName = '\\ziyue\\db\\ext\\' . strtolower(\Zy::$app->db->type) . '\\' . ucfirst(strtolower($this->sqlType)) . 'Model';
        return \Zy::createComponent($modelName, $modelName)->buildSql(static::model());
    }

    /**
     * 更新
     * @param array $setFields
     * @return mixed
     */
    public function update(Array $setFields){
        $this->sqlType = 'UPDATE';
        $this->updateFeilds = $setFields;
        $this->binData = array_merge($this->binData, $setFields);
        // todo : 字段验证
        $sql = $this->buildSql();
        $data = $this->query($sql, $this->binData, true);
        return isset($data[0]) ? $data[0] : $data;
    }

    /**
     *  单条记录添加
     * @param array $feilds
     * @return mixed
     */
    public function insert(Array $feilds){
        $this->sqlType = 'INSERT';
        $this->insertFeilds = is_array(current($feilds)) ? array_keys(current($feilds)) : array_keys($feilds);
        $this->binData = $feilds;
        // todo : 字段验证
        $sql = $this->buildSql();
        $data = $this->query($sql, $this->binData, true);
        return (is_array($data) && isset($data[0])) ? $data[0] : $data;
    }

    /**
     * 单条／多条记录添加
     * @param array $feilds
     * @return mixed
     */
    public function insertRows(Array $feilds){
        $this->sqlType = 'INSERT';
        $this->insertFeilds = array_keys($feilds);;
        $this->binData = $feilds;
        // todo : 字段验证
        $sql = $this->buildSql();
        $data = $this->query($sql, $this->binData, true);
        return (is_array($data) && isset($data[0])) ? $data[0] : $data;
    }

    public function delete(){
        $this->sqlType = 'DELETE';
        // TODO 字段验证
        $sql = $this->buildSql();
        $data = $this->query($sql, $this->binData, true);
        return (is_array($data) && isset($data[0])) ? $data[0] : $data;
    }

    public function query($sql, $bind = [], $enableMaster = true){
        $this->getDbInstrance($enableMaster);
        return $this->dbInstrance->query($sql, $bind);
    }

    public function beginTransaction(){
        $this->getDbInstrance(true);
        $this->dbInstrance->beginTransaction();
    }
    public function commitTransaction(){
        $this->getDbInstrance(true);
        $this->dbInstrance->commit();
    }
    public function rollbackTransaction(){
        $this->getDbInstrance(true);
        $this->dbInstrance->rollBack();
    }

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
     * @return array
     */
    public function getJoinTable()
    {
        return $this->joinTable;
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

    /**
     * @return array
     */
    public function getUpdateFeilds()
    {
        return $this->updateFeilds;
    }

    /**
     * @return array
     */
    public function getInsertFeilds()
    {
        return $this->insertFeilds;
    }

    /**
     * @return array
     */
    public function getBinData()
    {
        return $this->binData;
    }

    public function setBinData($bindData) {
        $this->binData = $bindData;
    }
}