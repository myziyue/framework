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
    'ziyue\db\adapter\AbstractDb' => ZY_PATH . '/db/adapter/AbstractDb.php',
    'ziyue\db\adapter\Mongodb' => ZY_PATH . '/db/adapter/Mongodb.php',
    'ziyue\db\adapter\MySql' => ZY_PATH . '/db/adapter/MySql.php',
    'ziyue\db\ext\Base' => ZY_PATH . '/db/ext/Base.php',
    'ziyue\db\ext\mongodb\DeleteModel' => ZY_PATH . '/db/ext/mongodb/DeleteModel.php',
    'ziyue\db\ext\mongodb\InsertModel' => ZY_PATH . '/db/ext/mongodb/InsertModel.php',
    'ziyue\db\ext\mongodb\SelectModel' => ZY_PATH . '/db/ext/mongodb/SelectModel.php',
    'ziyue\db\ext\mongodb\UpdateModel' => ZY_PATH . '/db/ext/mongodb/UpdateModel.php',
    'ziyue\db\ext\mysql\DeleteModel' => ZY_PATH . '/db/ext/mysql/DeleteModel.php',
    'ziyue\db\ext\mysql\InsertModel' => ZY_PATH . '/db/ext/mysql/InsertModel.php',
    'ziyue\db\ext\mysql\SelectModel' => ZY_PATH . '/db/ext/mysql/SelectModel.php',
    'ziyue\db\ext\mysql\UpdateModel' => ZY_PATH . '/db/ext/mysql/UpdateModel.php',
    //log
    'ziyue\log\Logger' => ZY_PATH . '/log/Logger.php',
    'ziyue\log\drivers\DBLog' => ZY_PATH . '/log/drivers/DBLog.php',
    'ziyue\log\drivers\FileLog' => ZY_PATH . '/log/drivers/FileLog.php',
    'ziyue\log\drivers\RedisLog' => ZY_PATH . '/log/drivers/RedisLog.php',
    //exception
    'ziyue\exception\InvalidCallException' => ZY_PATH . '/exception/InvalidCallException.php',
    'ziyue\exception\InvalidConfigException' => ZY_PATH . '/exception/InvalidConfigException.php',
    'ziyue\exception\NotFoundHttpException' => ZY_PATH . '/exception/NotFoundHttpException.php',
    'ziyue\exception\UnknownClassException' => ZY_PATH . '/exception/UnknownClassException.php',
    'ziyue\exception\UnknownMethodException' => ZY_PATH . '/exception/UnknownMethodException.php',
    'ziyue\exception\UnknownPropertyException' => ZY_PATH . '/exception/UnknownPropertyException.php',
    //web
    'ziyue\web\Application' => ZY_PATH . '/web/Application.php',
    'ziyue\web\Request' => ZY_PATH . '/web/Request.php',
    'ziyue\web\Response' => ZY_PATH . '/web/Response.php',
];