<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/14  下午5:19
 * @since 1.0
 */

namespace ziyue\db\ext\mysql;

use ziyue\db\ext\Base;

class DeleteModel extends Base
{

    public function buildSql($model)
    {
        $sql = 'DELETE FROM ' . $model->getTableName();
        
        if($model->getWhere()) {
            $sql .= ' WHERE ' . $model->getWhere();
        }
        return $sql;
    }
}