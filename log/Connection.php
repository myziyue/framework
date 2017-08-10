<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/10  上午10:31
 * @since 1.0
 */

namespace ziyue\log;

use ziyue\base\Components;
use ziyue\exception\UnknownClassException;

class Connection extends Components
{
    public static $driver = Logger::DRIVER_FILE;

    public function write($log, $level = Logger::LEVEL_INFO, $category = 'app'){
        $this->set('logAdapter', $this->getDriver());
        $this->get('logAdapter')->write($log, $level, $category);
    }

    public function getDriver(){
        $driverList = [
            'File' => ['class' => 'ziyue\log\adapter\FileLog'],
            'DB' =>  ['class' => 'ziyue\log\adapter\DBLog'],
            'Redis' =>  ['class' => 'ziyue\log\adapter\RedisLog'],
        ];
        if(!isset($driverList[$this->driver])){
            throw new UnknownClassException("Unable to find '$this->driver' . Namespace missing?");
        }
        return isset($driverList[$this->driver]) ? $driverList[$this->driver] : $driverList['File'];
    }
}