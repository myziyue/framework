<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/22  下午10:46
 * @since 1.0
 */
namespace zy\helper;

class Dumper
{
    static $_output = '';
    public static function export($var)
    {
        self::$_output = '';
        self::exportInternal($var, 0);
        return self::$_output;
    }

    private static function exportInternal($var, $level)
    {
        switch (gettype($var)) {
            case 'NULL':
                self::$_output .= 'null';
                break;
            case 'array':
                if (empty($var)) {
                    self::$_output .= '[]';
                } else {
                    $keys = array_keys($var);
                    $outputKeys = ($keys !== range(0, count($var) - 1));
                    $spaces = str_repeat(' ', $level * 4);
                    self::$_output .= '[';
                    foreach ($keys as $key) {
                        self::$_output .= "\n" . $spaces . '    ';
                        if ($outputKeys) {
                            self::exportInternal($key, 0);
                            self::$_output .= ' => ';
                        }
                        self::exportInternal($var[$key], $level + 1);
                        self::$_output .= ',';
                    }
                    self::$_output .= "\n" . $spaces . ']';
                }
                break;
            case 'object':
                if ($var instanceof \Closure) {
                    self::$_output .= self::exportClosure($var);
                } else {
                    try {
                        $output = 'unserialize(' . var_export(serialize($var), true) . ')';
                    } catch (\Exception $e) {
                        // serialize may fail, for example: if object contains a `\Closure` instance
                        // so we use a fallback
                        if ($var instanceof Arrayable) {
                            self::exportInternal($var->toArray(), $level);
                            return;
                        } elseif ($var instanceof \IteratorAggregate) {
                            $varAsArray = [];
                            foreach ($var as $key => $value) {
                                $varAsArray[$key] = $value;
                            }
                            self::exportInternal($varAsArray, $level);
                            return;
                        } elseif ('__PHP_Incomplete_Class' !== get_class($var) && method_exists($var, '__toString')) {
                            $output = var_export($var->__toString(), true);
                        } else {
                            $outputBackup = self::$_output;
                            $output = var_export(self::dumpAsString($var), true);
                            self::$_output = $outputBackup;
                        }
                    }
                    self::$_output .= $output;
                }
                break;
            default:
                self::$_output .= var_export($var, true);
        }
    }
}