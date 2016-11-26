<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/21  上午12:08
 * @since 1.0
 */

namespace zy\base;


class ErrorException extends \ErrorException
{
    /**
     * This constant represents a fatal error in the HHVM engine.
     *
     * PHP Zend runtime won't call the error handler on fatals, HHVM will, with an error code of 16777217
     * We will handle fatal error a bit different on HHVM.
     * @see https://github.com/facebook/hhvm/blob/master/hphp/runtime/base/runtime-error.h#L62
     * @since 2.0.6
     */
    const E_HHVM_FATAL_ERROR = 16777217; // E_ERROR | (1 << 24)


    /**
     * Constructs the exception.
     * @link http://php.net/manual/en/errorexception.construct.php
     * @param $message [optional]
     * @param $code [optional]
     * @param $severity [optional]
     * @param $filename [optional]
     * @param $lineno [optional]
     * @param $previous [optional]
     */
    public function __construct($message = '', $code = 0, $severity = 1, $filename = __FILE__, $lineno = __LINE__, \Exception $previous = null)
    {
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
    }



    /**
     * Returns if error is one of fatal type.
     *
     * @param array $error error got from error_get_last()
     * @return boolean if error is one of fatal type
     */
    public static function isFatalError($error)
    {
        return isset($error['type']) && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, self::E_HHVM_FATAL_ERROR]);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        static $names = [
            E_COMPILE_ERROR => 'PHP Compile Error',
            E_COMPILE_WARNING => 'PHP Compile Warning',
            E_CORE_ERROR => 'PHP Core Error',
            E_CORE_WARNING => 'PHP Core Warning',
            E_DEPRECATED => 'PHP Deprecated Warning',
            E_ERROR => 'PHP Fatal Error',
            E_NOTICE => 'PHP Notice',
            E_PARSE => 'PHP Parse Error',
            E_RECOVERABLE_ERROR => 'PHP Recoverable Error',
            E_STRICT => 'PHP Strict Warning',
            E_USER_DEPRECATED => 'PHP User Deprecated Warning',
            E_USER_ERROR => 'PHP User Error',
            E_USER_NOTICE => 'PHP User Notice',
            E_USER_WARNING => 'PHP User Warning',
            E_WARNING => 'PHP Warning',
            self::E_HHVM_FATAL_ERROR => 'HHVM Fatal Error',
        ];

        return isset($names[$this->getCode()]) ? $names[$this->getCode()] : 'Error';
    }
}