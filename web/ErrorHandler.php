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

    public $exception;
    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    public function renderException($exception)
    {
        $this->exception = $exception;
        if(ZY_DEBUG){
            Zy::p(self::convertExceptionToString($exception));
        } else {
            Zy::$app->runAction($this->errorAction, Zy::$app->getRequest()->getQueryParams());
        }
    }
}