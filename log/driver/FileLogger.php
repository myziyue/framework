<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/22  下午7:21
 * @since 1.0
 */
namespace zy\log\driver;

use Zy;
use zy\log\Logger;

class FileLogger
{
    public function log($message, $level = Logger::LEVEL_INFO, $category = 'application')
    {
        $ts = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        array_pop($ts); // remove the last trace since it would be the entry script, not very useful
        Zy::p($ts);
        $message = date("Y-m-d H:i:s") . '[][' . $level . '][]' . $message;
        $fileName = $category . '-' . date('Y-m-d') . '_' . time() . '.log';
        $filePath = Zy::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($filePath, $message, FILE_APPEND);
    }
}