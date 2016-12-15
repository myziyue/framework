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
use zy\helper\Dumper;
use zy\web\HttpException;

abstract class ErrorHandler extends Component
{
    public $discardExistingOutput = false;
    public $memoryReserveSize = 262144;
    public $exception;

    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);

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
        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string)$e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string)$exception;
            if (ZY_DEBUG) {
                if (PHP_SAPI === 'cli') {
                    echo $msg . "\n";
                } else {
                    echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, Zy::$app->charset) . '</pre>';
                }
            } else {
                echo 'An internal server error occurred.';
            }
            $msg .= "\n\$_SERVER = " . Dumper::export($_SERVER);

            error_log($msg);
            exit(1);
        }
    }

    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            // load ErrorException manually here because autoloading them will not work
            // when error occurs while autoloading a class
            $exception = new ErrorException($message, $code, $code, $file, $line);

            // in case error appeared in __toString method we can't throw any exception
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);
            foreach ($trace as $frame) {
                if ($frame['function'] === '__toString') {
                    $this->handleException($exception);
                    exit(1);
                }
            }

            throw $exception;
        }
        return false;
    }

    public function handleFatalError()
    {
        $error = error_get_last();

        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);

            $this->exception = $exception;

            $this->logException($exception);

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }

            $this->renderException($exception);
            exit(1);
        }
    }

    public function logException($exception)
    {
        $category = get_class($exception);
        if ($exception instanceof HttpException) {
            $category = 'zy\\web\\HttpException:' . $exception->statusCode;
        } elseif ($exception instanceof \ErrorException) {
            $category .= ':' . $exception->getSeverity();
        }
        Zy::error($exception, $category);


    }

    public function clearOutput()
    {
        // the following manual level counting is to deal with zlib.output_compression set to On
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }

    public static function convertExceptionToString($exception)
    {
        if ($exception instanceof Exception && !ZY_DEBUG) {
            $message = "{$exception->getName()}: {$exception->getMessage()}";
        } elseif (ZY_DEBUG) {
            if ($exception instanceof Exception) {
                $message = "Exception ({$exception->getName()})";
            } elseif ($exception instanceof ErrorException) {
                $message = "{$exception->getName()}";
            } else {
                $message = 'Exception';
            }
            $message .= " '" . get_class($exception) . "' with message '{$exception->getMessage()}' \n\nin "
                . $exception->getFile() . ':' . $exception->getLine() . "\n\n"
                . "Stack trace:\n" . $exception->getTraceAsString();
        } else {
            $message = 'Error: ' . $exception->getMessage();
        }
        return $message;
    }

    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    abstract protected function renderException($exception);
}