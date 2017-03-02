<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午6:28
 * @since 1.0
 */

namespace ziyue\core;

class ErrorHandler extends Object
{
    public $exception = null;
    public function register(){
        ini_set('display_errors', false);
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'shutdownFunction']);
    }

    /**
     * 处理错误
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @param $context
     * @param $backtrace
     */
    public function errorHandler($code, $message, $file, $line, $context){
        $this->shutdownFunction();
        $message = "Error: '{$message}' error code {$code}\n\nFile :{$file}\n\nLine :{$line}\n\n";
        \Ziyue::error($message);
        if(ZY_DEBUG){
            \Ziyue::p($message);
        } else {
            \Ziyue::p('An internal server error occurred.');
        }
    }

    /**
     * 处理异常
     * @param \Exception $exception
     */
    public function exceptionHandler(\Exception $exception){
        $this->exception = $exception;
        $this->shutdownFunction();
        $exceptionName = ($exception instanceof Exception || $exception instanceof ErrorException) ? $exception->getName() : 'Exception';
        $message = "{$exceptionName}: '{$exception->getMessage()}' \n\nin {$exception->getFile()}:{$exception->getLine()}\n\n"
            . "Stack trace:\n" . $exception->getTraceAsString();
        \Ziyue::error($message);
        if(ZY_DEBUG){
            \Ziyue::p($message);
        } else {
            \Ziyue::p('An internal server error occurred.');
        }
    }

    /**
     * 关闭自定义异常处理函数
     */
    public function shutdownFunction(){
        $e = error_get_last();
        if(isset($e['type'])) {
            $message = "Error: '{$e['message']}' File :{$e['file']}  Line :{$e['line']}\n\n";
            \Ziyue::warning($message);
        }
        if(ZY_DEBUG) {
            if(isset($e['type'])){
                \Ziyue::p($message);
            }
        }
        restore_error_handler();
        restore_exception_handler();
    }
}