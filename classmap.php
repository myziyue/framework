<?php
/**
 * Class map
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午4:57
 * @since 1.0
 */

return [
    //cache
    'ziyue\cache\Connection' => ZY_PATH . '/cache/Connection.php',
    // core
    'ziyue\core\Application' => ZY_PATH . '/core/Application.php',
    'ziyue\core\Exception' => ZY_PATH . '/core/Exception.php',
    'ziyue\core\ErrorHandler' => ZY_PATH . '/core/ErrorHandler.php',
    'ziyue\core\ExitException' => ZY_PATH . '/core/ExitException.php',
    'ziyue\core\Object' => ZY_PATH . '/core/Object.php',
    'ziyue\core\Components' => ZY_PATH . '/core/Components.php',
    //db
    'ziyue\db\Connection' => ZY_PATH . '/db/Connection.php',
    //log
    'ziyue\log\Logger' => ZY_PATH . '/log/Logger.php',
    //exception
    'ziyue\exception\InvalidCallException' => ZY_PATH . '/exception/InvalidCallException.php',
    'ziyue\exception\InvalidConfigException' => ZY_PATH . '/exception/InvalidConfigException.php',
    'ziyue\exception\UnknownClassException' => ZY_PATH . '/exception/UnknownClassException.php',
    'ziyue\exception\UnknownMethodException' => ZY_PATH . '/exception/UnknownMethodException.php',
    'ziyue\exception\UnknownPropertyException' => ZY_PATH . '/exception/UnknownPropertyException.php',
    //web
    'ziyue\web\Application' => ZY_PATH . '/web/Application.php',
];