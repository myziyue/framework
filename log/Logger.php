<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/22  下午7:08
 * @since 1.0
 */
namespace zy\log;

use zy\base\Component;
use zy\log\driver\FileLogger;

class Logger extends Component
{
    //type
    const TYPE_FILE = 'File';
    const TYPE_REDIS = 'Redis';
    const TYPE_DB = 'Db';
    //lever
    const LEVEL_ERROR = 0x01;
    const LEVEL_WARNING = 0x02;
    const LEVEL_INFO = 0x04;
    const LEVEL_TRACE = 0x08;
    const LEVEL_PROFILE = 0x40;
    const LEVEL_PROFILE_BEGIN = 0x50;
    const LEVEL_PROFILE_END = 0x60;
    public static $instrance = null;

    public $type = self::TYPE_FILE;


    public function instrance()
    {

    }

    public function createFactory(){
        if(self::$instrance == null){
            $driverClass = '\\zy\\log\\driver\\' . $this->type . 'Logger';
            self::$instrance = new $driverClass;
            self::setInstance(self::$instrance);
        }
        return self::$instrance;
    }
}