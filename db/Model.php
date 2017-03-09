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

    public static function model(){
        if(self::$instrance === null){
            self::$instrance = new self();
        }
        return self::$instrance;
    }
    public function select($feilds = '*'){

    }

    public function query($sql){

    }
}