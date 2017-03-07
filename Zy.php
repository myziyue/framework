<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午8:05
 * @since 1.0
 */
require(__DIR__ . '/BaseZiyue.php');

class Zy extends \ziyue\BaseZiyue
{
}

spl_autoload_register(['Zy', 'autoload'], true, true);
Zy::$classMap = require(__DIR__ . '/classmap.php');