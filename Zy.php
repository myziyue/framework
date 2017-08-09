<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午4:07
 * @since 1.0
 */

require __DIR__ . '/Ziyue.php';

class Zy extends \ziyue\Ziyue
{
}
spl_autoload_register(['Zy', 'autoload'], true, true);
Zy::$classmap = require(__DIR__ . '/classmap.php');