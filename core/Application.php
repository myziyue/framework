<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:02
 * @since 1.0
 */

namespace ziyue\core;

use ziyue\db\Connection;
use ziyue\exception\UnknownClassException;

class Application extends Object
{
    public function run(){
        try {
            $this->getErrorHandler()->register();
            $this->getDb();
            echo "ok";
        } catch (\Exception $ex){
            throw new ExitException($ex->getCode(), $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function getErrorHandler(){
        if(!isset(self::$intrance['errorHandler'])){
            self::$intrance['errorHandler'] = $this->getComponent('errorHandler');
        }
        return self::$intrance['errorHandler'];
    }

    public function getDb(){
        if(!isset(self::$intrance['db'])){
            self::$intrance['db'] = $this->getComponent('db');
        }
        return self::$intrance['db'];
    }

    public function end($status = 0)
    {
        if (ZY_DEBUG) {
            throw new ExitException($status);
        } else {
            exit($status);
        }
    }

    public function getComponent($componentName) {
        $components = [
            'db' => 'ziyue\db\Connection',
            'errorHandler' => 'ziyue\core\ErrorHandler'
        ];
        if (isset($components[$componentName])){
            try {
                $componentObj = new $components[$componentName];
            } catch (\Exception $ex) {
                throw new UnknownClassException('Getting unknown class: ' . $componentName);
            }
            return $componentObj;
        }
        throw new UnknownClassException('Getting unknown class: ' . $componentName);
    }
}