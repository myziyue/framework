<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/14  下午5:18
 * @since 1.0
 */

namespace ziyue\db\ext\mysql;

use ziyue\db\ext\Base;
use ziyue\exception\InvalidValueException;

class UpdateModel extends Base
{

    public function buildSql($model)
    {
        $sql = 'UPDATE ' . $model->getTableName() . ' SET ';
        if(!$model->getUpdateFeilds()){
            throw new InvalidValueException("Invalid Update feilds");
        } else {
            $updateArr = [];
            foreach($model->getUpdateFeilds() as $feild => $value){
                $updateArr[] = "$feild=:$feild";
            }
            $sql .= implode(',', $updateArr);
        }

        if($model->getWhere()) {
            $sql .= ' WHERE ' . $model->getWhere();
        }
        return $sql;
    }
}