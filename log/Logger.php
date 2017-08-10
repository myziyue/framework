<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午11:43
 * @since 1.0
 */

namespace ziyue\log;

interface Logger
{
    /*===== driver =====*/
    const DRIVER_FILE = 'File';
    const DRIVER_DB = 'DB';
    const DIRVER_REDIS = 'Redis';

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
    public function write($log, $level, $category);

}