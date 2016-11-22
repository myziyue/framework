<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/23  上午12:37
 * @since 1.0
 */

namespace zy\web;

use Zy;

class ErrorHandler extends \zy\base\ErrorHandler
{

    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        Zy::p(self::convertExceptionToString($exception));
    }
}