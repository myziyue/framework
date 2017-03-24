<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/14  下午5:20
 * @since 1.0
 */

namespace ziyue\db\ext\mysql;

use ziyue\db\ext\Base;
use ziyue\exception\InvalidValueException;


class InsertModel extends Base
{

    public function buildSql($model)
    {
        $sql = 'INSERT INTO ' . $model->getTableName() ;
        if(!$model->getInsertFeilds() || !$model->getBinData()){
            throw new InvalidValueException("Invalid Insert feilds");
        } else {
            $sql .= ' ( ' . implode(',', array_keys($model->getInsertFeilds())) . ') values (';
            $updateArr = [];
            $bindData = [];
            foreach($model->getBinData() as $key => $insertRows){
                foreach ($insertRows as $feild => $value) {
                    $bindData[][$feild. '_' . $key] = $value;
                }
                $updateArr[] = '(' . implode(',', array_keys($insertRows)) . ')';
            }
            $sql .= implode(',', $updateArr) . ')';
            $model->setBinData($bindData);
        }
        return $sql;
    }
}