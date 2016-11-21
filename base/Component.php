<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/19  下午11:17
 * @since 1.0
 */
namespace zy\base;

use Zy;
use zy\exception\InvalidCallException;
use zy\exception\UnknownPropertyException;

class Component
{
    private $_behaviors;

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return ;
        }

        if (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->$getter();
        } else {
            \Zy::createObject($name);
        }
        if (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Zy::$app->loadedModules[$class]) ? Zy::$app->loadedModules[$class] : null;
    }

    /**
     * Sets the currently requested instance of this module class.
     * @param Module|null $instance the currently requested instance of this module class.
     * If it is null, the instance of the calling class will be removed, if any.
     */
    public static function setInstance($instance)
    {
        if ($instance === null) {
            unset(Zy::$app->loadedModules[get_called_class()]);
        } else {
            Zy::$app->loadedModules[get_class($instance)] = $instance;
        }
    }
}