<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/14  下午5:12
 * @since 1.0
 */

namespace ziyue\db\ext\mysql;

use ziyue\db\ext\Base;

class SelectModel extends Base
{
    public function buildSql($model)
    {
        $sql = 'SELECT ' . $model->getSelect() . ' FROM ' . $model->getTableName();
        if($model->getWhere()) {
            $sql .= ' WHERE ' . $model->getWhere();
        }
        if($model->getGroupBy()){
            $sql .= ' ' . $model->getGroupBy();
        }
        if($model->getOrderBy()){
            $sql .= ' ' . $model->getOrderBy();
        }
        if($model->getLimit()){
            $sql .= ' ' . $model->getLimit();
        }
        return $sql;
    }
}