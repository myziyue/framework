<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午4:10
 * @since 1.0
 */

return [
    //base
    'ziyue\base\Action' => ZIYUE_PATH . '/base/Action.php',
    'ziyue\base\Application' => ZIYUE_PATH . '/base/Application.php',
    'ziyue\base\Component' => ZIYUE_PATH . '/base/Component.php',
    'ziyue\base\Controller' => ZIYUE_PATH . '/base/Controller.php',
    'ziyue\base\Exception' => ZIYUE_PATH . '/base/Exception.php',
    'ziyue\base\View' => ZIYUE_PATH . '/base/View.php',
    // cache
    'ziyue\cache\adapter\FileCache' => ZIYUE_PATH . '/cache/adapter/FileCache.php',
    'ziyue\cache\adapter\RedisCache' => ZIYUE_PATH . '/cache/adapter/RedisCache.php',
    'ziyue\cache\Cache' => ZIYUE_PATH . '/cache/Cache.php',
    'ziyue\cache\Connection' => ZIYUE_PATH . '/cache/Connection.php',
    // db
    'ziyue\db\adapter\HBase' => ZIYUE_PATH . '/db/adapter/HBase.php',
    'ziyue\db\adapter\MongoDB' => ZIYUE_PATH . '/db/adapter/MongoDB.php',
    'ziyue\db\adapter\MySQL' => ZIYUE_PATH . '/db/adapter/MySQL.php',
    'ziyue\db\Connection' => ZIYUE_PATH . '/db/Connection.php',
    'ziyue\db\DB' => ZIYUE_PATH . '/db/DB.php',
    'ziyue\db\Model' => ZIYUE_PATH . '/db/Model.php',
    // exception
    'ziyue\exception\InvalidCallException' => ZIYUE_PATH . '/exception/InvalidCallException.php',
    'ziyue\exception\InvalidConfigException' => ZIYUE_PATH . '/exception/InvalidConfigException.php',
    'ziyue\exception\InvalidParamException' => ZIYUE_PATH . '/exception/InvalidParamException.php',
    'ziyue\exception\InvalidRouterException' => ZIYUE_PATH . '/exception/InvalidRouterException.php',
    'ziyue\exception\InvalidValueException' => ZIYUE_PATH . '/exception/InvalidValueException.php',
    'ziyue\exception\NotSupportedException' => ZIYUE_PATH . '/exception/NotSupportedException.php',
    'ziyue\exception\UnknownClassException' => ZIYUE_PATH . '/exception/UnknownClassException.php',
    'ziyue\exception\UnknownMethodException' => ZIYUE_PATH . '/exception/UnknownMethodException.php',
    'ziyue\exception\UnknownPropertyException' => ZIYUE_PATH . '/exception/UnknownPropertyException.php',
    'ziyue\exception\ViewNotFoundException' => ZIYUE_PATH . '/exception/ViewNotFoundException.php',
    // log
    'ziyue\log\adapter\DbLogger' => ZIYUE_PATH . '/log/adapter/DbLogger.php',
    'ziyue\log\adapter\FileLogger' => ZIYUE_PATH . '/log/adapter/FileLogger.php',
    'ziyue\log\adapter\RedisLogger' => ZIYUE_PATH . '/log/adapter/RedisLogger.php',
    'ziyue\log\Connection' => ZIYUE_PATH . '/log/Connection.php',
    'ziyue\log\Logger' => ZIYUE_PATH . '/log/Logger.php',
    //web
    'ziyue\web\Application' => ZIYUE_PATH . '/web/Application.php',
    'ziyue\web\Controller' => ZIYUE_PATH . '/web/Controller.php',
    'ziyue\web\Request' => ZIYUE_PATH . '/web/Request.php',
    'ziyue\web\Response' => ZIYUE_PATH . '/web/Response.php',
    'ziyue\web\View' => ZIYUE_PATH . '/web/View.php',
];