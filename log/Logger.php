<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午11:43
 * @since 1.0
 */

namespace ziyue\log;

use ziyue\core\Object;
use ziyue\exception\UnknownClassException;

class Logger extends Object
{
    /*===== driver =====*/
    const DRIVER_FILE = 'File';
    const DRIVER_DB = 'DB';
    const DIRVER_REDIS = 'Redis';
    public $driver = self::DRIVER_FILE;

    /*===== level =====*/
    const LEVEL_ERROR = 'Error';
    const LEVEL_WARNING = 'Warning';
    const LEVEL_NOTICE = 'Notice';
    const LEVEL_INFO = 'Info';
    const LEVEL_TRACE = 'Trace';

    /**
     * 写日志
     * @param $log
     * @return mixed
     */
    public function log($log, $level, $category){
        $driverName = $this->getDriver();
        $driver = new $driverName;
        $driver->log($log, $level, $category);
    }

    public function getDriver(){
        $driverList = [
            'File' => 'ziyue\log\drivers\FileLog',
            'DB' => 'ziyue\log\drivers\DBLog',
            'Redis' => 'ziyue\log\drivers\RedisLog',
        ];
        if(!isset($driverList[$this->driver])){
            throw new UnknownClassException("Unable to find '$this->driver' . Namespace missing?");
        }
        return isset($driverList[$this->driver]) ? $driverList[$this->driver] : $driverList['File'];
    }
}