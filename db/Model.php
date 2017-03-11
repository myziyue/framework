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

class Model extends Object
{
    private static $instrance = null;
    private static $dbInstrance = null;
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

    private static function geDbInstrance(){
        if(self::$dbInstrance == null) {
            self::$dbInstrance = \Zy::$app->db->
        }
    }

    public function select($feilds = '*', $dbSlave = null){

    }

    public function query($sql){

    }
}