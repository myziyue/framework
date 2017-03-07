<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/2  下午4:46
 * @since 1.0
 */

namespace ziyue\log\drivers;

use ziyue\log\Logger;

class FileLog extends Logger
{
    public $logFileName = 'app.log';
    public static $logDirPath = null;

    public function __construct()
    {
        self::$logDirPath = \Zy::$app->appPath . DIRECTORY_SEPARATOR . 'runtimes' . DIRECTORY_SEPARATOR . 'logs'
            . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR ;
        if(!file_exists(self::$logDirPath)){
            mkdir(self::$logDirPath, 0755, true);
        }
    }

    public function log($message, $level, $category = 'app'){
        $message = date("Y-m-d H:i:s") . '[][' . $level . '][' . $category . '] ' . $message . "\n\r";
        file_put_contents(self::$logDirPath . $category .'-' . strtolower($level) . '.log', $message, FILE_APPEND);
    }
}