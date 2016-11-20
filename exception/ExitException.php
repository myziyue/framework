<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午9:56
 * @since 1.0
 */
namespace zy\exception;


class ExitException extends \zy\base\Exception
{
    /**
     * @var integer the exit status code
     */
    public $statusCode;


    /**
     * Constructor.
     * @param integer $status the exit status code
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($status = 0, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->statusCode = $status;
        parent::__construct($message, $code, $previous);
    }
}