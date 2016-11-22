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
use zy\base\Object;
use zy\log\Logger;

class FileLogger extends Object
{
    public function log($message, $level = Logger::LEVEL_INFO, $category = 'application')
    {
        ini_set('display_errors', true);
        $message = date("Y-m-d H:i:s") . '[][' . $level . '][' . $category . '] ' . $message . "\n\r";
        $fileName = 'app-' . date('Ymd') . '.log';
        $filePath = Zy::getAliasPath(Zy::$app->getRuntimePath()) . 'logs' . DIRECTORY_SEPARATOR;
        if(!file_exists($filePath)){
            mkdir($filePath, 0755, true);
        }

        file_put_contents($filePath . $fileName, $message, FILE_APPEND);
    }
}