<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:02
 * @since 1.0
 */

namespace ziyue\web;

use ziyue\core\ExitException;

class Application extends \ziyue\core\Application
{
    public function run()
    {
        try {
//            $this->getDb();
            echo "ok";
        } catch (\Exception $ex){
            throw new ExitException($ex->getCode(), $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function request(){

    }
}