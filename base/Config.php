<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午8:07
 * @since 1.0
 */

namespace zy\base;

use Zy;
use zy\di\ServiceLocator;

class Config extends ServiceLocator
{
    private $defaultConfigName = 'main.php';
    public function init()
    {
        Zy::$app->getAppPath() . DIRECTORY_SEPARATOR  . 'config' . DIRECTORY_SEPARATOR . $this->defaultConfigName;
    }
}