<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/10  上午10:24
 * @since 1.0
 */

namespace ziyue\cache;


interface Cache
{
    const CACHE_FILE = 'File';
    const CACHE_REDIS = 'Redis';
    const CACHE_DB = 'Db';

    public function setCache($key, $value);
    public function getCache($key);
}