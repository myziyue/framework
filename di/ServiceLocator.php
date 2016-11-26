<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  下午10:52
 * @since 1.0
 */

namespace zy\di;

use Zy;
use Closure;
use zy\base\Component;
use zy\exception\InvalidConfigException;

class ServiceLocator extends Component
{
    // 用于缓存服务、组件等的实例
    private $_components = [];

    // 用于保存服务和组件的定义，通常为配置数组，可以用来创建具体的实例
    private $_definitions = [];


    /**
     * 重载了 getter 方法，使得访问服务和组件就跟访问类的属性一样。
     * 同时，也保留了原来Component的 getter所具有的功能。
     * 请留意，ServiceLocator 并未重载 __set()，
     * 仍然使用 yii\base\Component::__set()
     *
     * @param string $name component or property name
     * @return mixed the named property value
     */
    public function __get($name)
    {
        // has() 方法就是判断 $_definitions 数组中是否已经保存了服务或组件的定义
        // 请留意，这个时候服务或组件仅是完成定义，不一定已经实例化
        if ($this->has($name)) {

            // get() 方法用于返回服务或组件的实例
            return $this->get($name);

            // 未定义的服务或组件，那么视为正常的属性、行为，
            // 调用 yii\base\Component::__get()
        } else {
            return parent::__get($name);
        }
    }

    /**
     * 对比Component，增加了对是否具有某个服务和组件的判断。
     *
     * @param string $name the property name or the event name
     * @return boolean whether the property value is null
     */
    public function __isset($name)
    {
        if ($this->has($name, true)) {
            return true;
        } else {
            return parent::__isset($name);
        }
    }

    /**
     * 当 $checkInstance === false 时，用于判断是否已经定义了某个服务或组件
     * 当 $checkInstance === true 时，用于判断是否已经有了某人服务或组件的实例
     *
     * @param string $id component ID (e.g. `db`).
     * @param boolean $checkInstance whether the method should check if the component is shared and instantiated.
     * @return boolean whether the locator has the specified component definition or has instantiated the component.
     * @see set()
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_components[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * 根据 $id 获取对应的服务或组件的实例
     *
     * @param string $id component ID (e.g. `db`).
     * @param boolean $throwException whether to throw an exception if `$id` is not registered with the locator before.
     * @return object|null the component of the specified ID. If `$throwException` is false and `$id`
     * is not registered before, null will be returned.
     * @throws InvalidConfigException if `$id` refers to a nonexistent component ID
     * @see has()
     * @see set()
     */
    public function get($id, $throwException = true)
    {
        // 如果已经有实例化好的组件或服务，直接使用缓存中的就OK了
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        // 如果还没有实例化好，那么再看看是不是已经定义好
        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];

            // 如果定义是个对象，且不是Closure对象，那么直接将这个对象返回
            if (is_object($definition) && !$definition instanceof Closure) {
                // 实例化后，保存进 $_components 数组中，以后就可以直接引用了
                return $this->_components[$id] = $definition;

                // 是个数组或者PHP callable，调用 Zy::createObject()来创建一个实例
            } else {
                // 实例化后，保存进 $_components 数组中，以后就可以直接引用了
                return $this->_components[$id] = Zy::createObject($definition);
            }
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown component ID: $id");

            // 即没实例化，也没定义，万能的Zy也没办法通过一个任意的ID，
            // 就给你找到想要的组件或服务呀，给你个 null 吧。
            // 表示Service Locator中没有这个ID的服务或组件。
        } else {
            return null;
        }
    }

    /**
     * 用于注册一个组件或服务，其中 $id 用于标识服务或组件。
     * $definition 可以是一个类名，一个配置数组，一个PHP callable，或者一个对象
     *
     * @param string $id component ID (e.g. `db`).
     * @param mixed $definition the component definition to be registered with this locator.
     * @throws InvalidConfigException if the definition is an invalid configuration array
     */
    public function set($id, $definition)
    {
        // 当定义为 null 时，表示要从Service Locator中删除一个服务或组件
        if ($definition === null) {
            unset($this->_components[$id], $this->_definitions[$id]);
            return;
        }
        // 确保服务或组件ID的唯一性
        unset($this->_components[$id]);
        // 定义如果是个对象或PHP callable，或类名，直接作为定义保存
        // 留意这里 is_callable的第二个参数为true，所以，类名也可以。
        if (is_object($definition) || is_callable($definition, true)) {
            // 定义的过程，只是写入了 $_definitions 数组
            $this->_definitions[$id] = $definition;
            // 定义如果是个数组，要确保数组中具有 class 元素
        } elseif (is_array($definition)) {
            if (isset($definition['class'])) {
                // 定义的过程，只是写入了 $_definitions 数组
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException(
                    "The configuration for the \"$id\" component must contain a \"class\" element.");
            }
            // 这也不是，那也不是，那么就抛出异常吧
        } else {
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" component: " . gettype($definition));
        }
    }

    /**
     * 删除一个服务或组件
     * @param string $id the component ID
     */
    public function clear($id)
    {
        unset($this->_definitions[$id], $this->_components[$id]);
    }

    /**
     * 用于返回Service Locator的 $_components 数组或 $_definitions 数组，同时也是 components 属性的getter函数
     * @param boolean $returnDefinitions whether to return component definitions instead of the loaded component instances.
     * @return array the list of the component definitions or the loaded component instances (ID => definition or instance).
     */
    public function getComponents($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_components;
    }

    /**
     * 批量方式注册服务或组件，同时也是 components 属性的setter函数
     * @param array $components component definitions or instances
     */
    public function setComponents($components)
    {
        foreach ($components as $id => $component) {
            $this->set($id, $component);
        }
    }
}