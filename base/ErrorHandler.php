<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午10:40
 * @since 1.0
 */

namespace zy\base;

use Zy;
use zy\exception\ExitException;
use zy\web\HttpException;

class ErrorHandler extends Component
{
    public $discardExistingOutput = true;
    public $memoryReserveSize = 262144;
    public $exception;
    private $_memoryReserve;
    private $_hhvmException;

    public function register()
    {
        ini_set('display_errors', true);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);

        register_shutdown_function([$this, 'handleFatalError']);
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function handleFatalError()
    {
        $error = error_get_last();

        if (ErrorException::isFatalError($error)) {
            if (!empty($this->_hhvmException)) {
                $exception = $this->_hhvmException;
            } else {
                $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            }
            $this->exception = $exception;

            $this->logException($exception);

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);

            // need to explicitly flush logs because exit() next will terminate the app immediately
            Zy::getLogger()->flush(true);
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }
    }

    public function logException($exception)
    {
        $category = get_class($exception);
        if ($exception instanceof HttpException) {
            $category = 'yii\\web\\HttpException:' . $exception->statusCode;
        } elseif ($exception instanceof \ErrorException) {
            $category .= ':' . $exception->getSeverity();
        }
        Zy::error($exception, $category);
    }

}