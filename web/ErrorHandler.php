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
    public $errorAction = 'ziyue/error';

    public $exception = null;

    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        $this->exception = self::convertExceptionToString($exception);
        if(ZY_DEBUG){
            Zy::p($this->exception);
        } else {
            Zy::$app->runAction($this->errorAction, Zy::$app->getRequest()->getQueryParams());
        }
    }
}