<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午8:05
 * @since 1.0
 */
require(__DIR__ . '/BaseZiyue.php');

class Ziyue extends \zy\BaseZiyue
{
}

spl_autoload_register(['Ziyue', 'autoload'], true, true);
Ziyue::$classMap = require(__DIR__ . '/classmap.php');
Ziyue::$container = new \zy\di\Container();
