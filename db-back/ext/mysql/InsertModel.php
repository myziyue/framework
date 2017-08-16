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
            $sql .= ' ( ' . implode(',',$model->getInsertFeilds()) . ')';
            $updateArr = [];
            $bindData = [];
            $sqlSplit = 'value';
            foreach($model->getBinData() as $key => $insertRows){
                $data = [];
                if(is_array($insertRows)) {
                    foreach ($insertRows as $feild => $value) {
                        $data[] = ':' . $feild. '_' . $key;
                        $bindData[$feild. '_' . $key] = $value;
                    }
                    $updateArr[] = "(" . implode(",", $data) . ")";
                    $sqlSplit = 'values';
                } else {
                    $updateArr[] = ':' . $key;
                    $bindData[$key] = $insertRows;
                }
            }
            $sql .= $sqlSplit == 'value' ? ' value (' . implode(',', $updateArr) . ')' : ' values ' .implode(',', $updateArr);
            $model->setBinData($bindData);
        }
        return $sql;
    }
}