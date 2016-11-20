<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午10:40
 * @since 1.0
 */

namespace zy\base;

use \Ziyue;
use zy\exception\ExitException;

class ErrorHandler extends Component
{
    public $discardExistingOutput = true;
    public $memoryReserveSize = 262144;
    public $exception;
    private $_memoryReserve;
    private $_hhvmException;

    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        if (defined('HHVM_VERSION')) {
            set_error_handler([$this, 'handleHhvmError']);
        } else {
            set_error_handler([$this, 'handleError']);
        }
        if ($this->memoryReserveSize > 0) {
            $this->_memoryReserve = str_repeat('x', $this->memoryReserveSize);
        }
        register_shutdown_function([$this, 'handleFatalError']);
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function handleException($exception)
    {
        if ($exception instanceof ExitException) {
            return;
        }

        $this->exception = $exception;

        // disable error capturing to avoid recursive errors while handling exceptions
        $this->unregister();

        // set preventive HTTP status code to 500 in case error handling somehow fails and headers are sent
        // HTTP exceptions will override this value in renderException()
        if (PHP_SAPI !== 'cli') {
            http_response_code(500);
        }

        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
            if (!ZY_ENV_TEST) {
                \Ziyue::getLogger()->flush(true);
                if (defined('HHVM_VERSION')) {
                    flush();
                }
                exit(1);
            }
        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string) $e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string) $exception;
            if (ZY_DEBUG) {
                if (PHP_SAPI === 'cli') {
                    echo $msg . "\n";
                } else {
                    echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, \Ziyue::$app->charset) . '</pre>';
                }
            } else {
                echo 'An internal server error occurred.';
            }
            $msg .= "\n\$_SERVER = " . \Ziyue::p($_SERVER);
            error_log($msg);
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }

        $this->exception = null;
    }

    public function handleFatalError()
    {

    }
}