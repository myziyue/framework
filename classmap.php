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
    'zy\base\Exception' => ZY_PATH . '/base/Exception.php',
    'zy\base\ErrorException' => ZY_PATH . '/base/ErrorException.php',
    'zy\base\ErrorHandler' => ZY_PATH . '/base/ErrorHandler.php',
    'zy\base\Component' => ZY_PATH . '/base/Component.php',
    'zy\base\Object' => ZY_PATH . '/base/Object.php',
    //db
    'zy\db\Connention' => ZY_PATH . '/db/Connention.php',
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
    // helper
    'zy\helper\Dumper' => ZY_PATH . '/helper/Dumper.php',
    // log
    'zy\log\Logger' => ZY_PATH . '/log/Logger.php',
    'zy\log\driver\FileLogger' => ZY_PATH . '/log/driver/FileLogger.php',
    // WEB
    'zy\web\Application' => ZY_PATH . '/web/Application.php',
    'zy\web\ErrorAction' => ZY_PATH . '/web/ErrorAction.php',
    'zy\web\ErrorHandler' => ZY_PATH . '/web/ErrorHandler.php',
    'zy\web\Request' => ZY_PATH . '/web/Request.php',
    'zy\web\Response' => ZY_PATH . '/web/Response.php',
    'zy\web\UrlManager' => ZY_PATH . '/web/UrlManager.php',
    'zy\web\UrlNormalizerRedirectException' => ZY_PATH . '/web/UrlNormalizerRedirectException.php',
    'zy\web\NotFoundHttpException' => ZY_PATH . '/web/NotFoundHttpException.php',
];