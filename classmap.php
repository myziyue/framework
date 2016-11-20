<?php
/**
 * Class map
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午4:57
 * @since 1.0
 */

return [
    // BASE
    'zy\base\Application' => ZY_PATH . '/base/Application.php',
    'zy\base\Controller' => ZY_PATH . '/base/Controller.php',
    'zy\base\Exception' => ZY_PATH . '/base/Exception.php',
    'zy\base\ErrorHandler' => ZY_PATH . '/base/ErrorHandler.php',
    'zy\base\Component' => ZY_PATH . '/base/Component.php',
    // di
    'zy\di\Container' => ZY_PATH . '/di/Container.php',
    'zy\di\ServiceLocator' => ZY_PATH . '/di/ServiceLocator.php',
    // exception
    'zy\exception\InvalidCallException' => ZY_PATH . '/exception/InvalidCallException.php',
    'zy\exception\InvalidConfigException' => ZY_PATH . '/exception/InvalidConfigException.php',
    'zy\exception\InvalidParamException' => ZY_PATH . '/exception/InvalidParamException.php',
    'zy\exception\InvalidRouteException' => ZY_PATH . '/exception/InvalidRouteException.php',
    'zy\exception\InvalidValueException' => ZY_PATH . '/exception/InvalidValueException.php',
    'zy\exception\UnknownClassException' => ZY_PATH . '/exception/UnknownClassException.php',
    'zy\exception\UnknownMethodException' => ZY_PATH . '/exception/UnknownMethodException.php',
    'zy\exception\UnknownPropertyException' => ZY_PATH . '/exception/UnknownPropertyException.php',
    // WEB
    'zy\web\Application' => ZY_PATH . '/web/Application.php',
];