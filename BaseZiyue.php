<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/1  下午1:45
 * @since 1.0
 */
namespace ziyue;


// 框架加载开始时间
defined('ZY_BEGIN_TIME') or define('ZY_BEGIN_TIME', microtime(true));
// 框架根目录
defined('ZY_PATH') or define('ZY_PATH', __DIR__);
// 是否开启框架Debug模式
defined('ZY_DEBUG') or define('ZY_DEBUG', false);
defined('ZY_ENV_TEST') or define('ZY_ENV_TEST', 'test');
defined('ZY_ENV_DEV') or define('ZY_ENV_DEV', 'dev');
defined('ZY_ENV_PROD') or define('ZY_ENV_PROD', 'prod');

defined('ZY_ENABLE_ERROR_HANDLER') or define('ZY_ENABLE_ERROR_HANDLER', true);

class BaseZiyue
{
    /**
     * 打印调试信息
     * @param $data
     */
    public static function p($data)
    {
        if (is_bool($data)) {
            var_dump($data);
        } elseif (is_null($data)) {
            var_dump($data);
        } else {
            echo "<pre style='position: relative;z-index: 100%; padding: 10px;border-radius: 5px;background: #F5F5F5; border: 1px solid #AAA;font-size:14px;line-height: 18px; opacity: 0.9;'>"
                . print_r($data, true) . "</pre>";
        }
    }
}