<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/14  下午5:20
 * @since 1.0
 */

namespace ziyue\db\ext;

abstract class Base
{
    abstract public function buildSql($model);
}