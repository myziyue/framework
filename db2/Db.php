<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/16  上午11:14
 * @since 1.0
 */

namespace ziyue\db;


Interface Db
{
    public function getMaster();
    public function getSlavers($slaveDbId = 0);
    public function query($sql, array $bind = []);
}