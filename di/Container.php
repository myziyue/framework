<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午11:14
 * @since 1.0
 */

namespace zy\di;

use ReflectionClass;
use zy\base\Component;
use zy\base\Exception;
use zy\exception\InvalidConfigException;
use zy\exception\UnknownClassException;

class Container extends Component
{
    // 用于保存单例Singleton对象，以对象类型为键
    private $_singletons = [];

    // 用于保存依赖的定义，以对象类型为键
    private $_definitions = [];

    // 用于保存构造函数的参数，以对象类型为键
    private $_params = [];

    // 用于缓存ReflectionClass对象，以类名或接口名为键
    private $_reflections = [];

    // 用于缓存依赖信息，以类名或接口名为键
    private $_dependencies = [];

    public function get($class, $params = [], $config = [])
    {
        // 已经有一个完成实例化的单例，直接引用这个单例
        if (isset($this->_singletons[$class])) {
            return $this->_singletons[$class];

            // 是个尚未注册过的依赖，说明它不依赖其他单元，或者依赖信息不用定义，
            // 则根据传入的参数创建一个实例
        } elseif (!isset($this->_definitions[$class])) {
            return $this->build($class, $params, $config);
        }

        // 注意这里创建了 $_definitions[$class] 数组的副本
        $definition = $this->_definitions[$class];

        // 依赖的定义是个 PHP callable，调用之
        if (is_callable($definition, true)) {
            $params = $this->resolveDependencies($this->mergeParams($class, $params));
            $object = call_user_func($definition, $this, $params, $config);

            // 依赖的定义是个数组，合并相关的配置和参数，创建之
        } elseif (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);

            // 合并将依赖定义中配置数组和参数数组与传入的配置数组和参数数组合并
            $config = array_merge($definition, $config);
            $params = $this->mergeParams($class, $params);

            if ($concrete === $class) {
                // 这是递归终止的重要条件
                $object = $this->build($class, $params, $config);
            } else {
                // 这里实现了递归解析
                $object = $this->get($concrete, $params, $config);
            }

            // 依赖的定义是个对象则应当保存为单例
        } elseif (is_object($definition)) {
            return $this->_singletons[$class] = $definition;
        } else {
            throw new InvalidConfigException(
                "Unexpected object definition type: " . gettype($definition));
        }

        // 依赖的定义已经定义为单例的，应当实例化该对象
        if (array_key_exists($class, $this->_singletons)) {
            $this->_singletons[$class] = $object;
        }

        return $object;
    }

    public function set($class, $definition = [], array $params = [])
    {
        // 规范化 $definition 并写入 $_definitions[$class]
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);

        // 将构造函数参数写入 $_params[$class]
        $this->_params[$class] = $params;

        // 删除$_singletons[$class]
        unset($this->_singletons[$class]);
        return $this;
    }

    public function setSingleton($class, $definition = [], array $params = [])
    {
        // 规范化 $definition 并写入 $_definitions[$class]
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);

        // 将构造函数参数写入 $_params[$class]
        $this->_params[$class] = $params;

        // 将$_singleton[$class]置为null，表示还未实例化
        $this->_singletons[$class] = null;
        return $this;
    }

    protected function normalizeDefinition($class, $definition)
    {
        // $definition 是空的转换成 ['class' => $class] 形式
        if (empty($definition)) {
            return ['class' => $class];

            // $definition 是字符串，转换成 ['class' => $definition] 形式
        } elseif (is_string($definition)) {
            return ['class' => $definition];

            // $definition 是PHP callable 或对象，则直接将其作为依赖的定义
        } elseif (is_callable($definition, true) || is_object($definition)) {
            return $definition;

            // $definition 是数组则确保该数组定义了 class 元素
        } elseif (is_array($definition)) {
            if (!isset($definition['class'])) {
                if (strpos($class, '\\') !== false) {
                    $definition['class'] = $class;
                } else {
                    throw new InvalidConfigException(
                        "A class definition requires a \"class\" member.");
                }
            }
            return $definition;
            // 这也不是，那也不是，那就抛出异常算了
        } else {
            throw new InvalidConfigException(
                "Unsupported definition type for \"$class\": "
                . gettype($definition));
        }
    }

    protected function getDependencies($class)
    {
        // 如果已经缓存了其依赖信息，直接返回缓存中的依赖信息
        if (isset($this->_reflections[$class])) {
            return [$this->_reflections[$class], $this->_dependencies[$class]];
        }

        $dependencies = [];

        // 使用PHP5 的反射机制来获取类的有关信息，主要就是为了获取依赖信息
        if(class_exists($class)){
            $reflection = new ReflectionClass($class);
        } else {
            throw new UnknownClassException('Class ' . $class . ' does not exist');
            exit(1);
        }

        // 通过类的构建函数的参数来了解这个类依赖于哪些单元
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if ($param->isDefaultValueAvailable()) {

                    // 构造函数如果有默认值，将默认值作为依赖。即然是默认值了，
                    // 就肯定是简单类型了。
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();

                    // 构造函数没有默认值，则为其创建一个引用。
                    // 就是前面提到的 Instance 类型。
                    $dependencies[] = Instance::of($c === null ? null :
                        $c->getName());
                }
            }
        }

        // 将 ReflectionClass 对象缓存起来
        $this->_reflections[$class] = $reflection;

        // 将依赖信息缓存起来
        $this->_dependencies[$class] = $dependencies;

        return [$reflection, $dependencies];
    }

    protected function resolveDependencies($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency) {

            // 前面getDependencies() 函数往 $_dependencies[] 中
            // 写入的是一个 Instance 数组
            if ($dependency instanceof Instance) {
                if ($dependency->id !== null) {

                    // 向容器索要所依赖的实例，递归调用 yii\di\Container::get()
                    $dependencies[$index] = $this->get($dependency->id);
                } elseif ($reflection !== null) {
                    $name = $reflection->getConstructor()
                        ->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new InvalidConfigException(
                        "Missing required parameter \"$name\" when instantiating \"$class\".");
                }
            }
        }
        return $dependencies;
    }

    protected function build($class, $params, $config)
    {
        // 调用上面提到的getDependencies来获取并缓存依赖信息，留意这里 list 的用法
        list ($reflection, $dependencies) = $this->getDependencies($class);

        // 用传入的 $params 的内容补充、覆盖到依赖信息中
        foreach ($params as $index => $param) {
            $dependencies[$index] = $param;
        }

        // 这个语句是两个条件：
        // 一是要创建的类是一个 yii\base\Object 类，
        // 留意我们在《Yii基础》一篇中讲到，这个类对于构造函数的参数是有一定要求的。
        // 二是依赖信息不为空，也就是要么已经注册过依赖，
        // 要么为build() 传入构造函数参数。
        if (!empty($dependencies) && is_a($class, 'yii\base\Object', true)) {
            // 按照 Object 类的要求，构造函数的最后一个参数为 $config 数组
            $dependencies[count($dependencies) - 1] = $config;

            // 解析依赖信息，如果有依赖单元需要提前实例化，会在这一步完成
            $dependencies = $this->resolveDependencies($dependencies, $reflection);

            // 实例化这个对象
            return $reflection->newInstanceArgs($dependencies);
        } else {
            // 会出现异常的情况有二：
            // 一是依赖信息为空，也就是你前面又没注册过，
            // 现在又不提供构造函数参数，你让Yii怎么实例化？
            // 二是要构造的类，根本就不是 Object 类。
            $dependencies = $this->resolveDependencies($dependencies, $reflection);
            $object = $reflection->newInstanceArgs($dependencies);
            foreach ($config as $name => $value) {
                $object->$name = $value;
            }
            return $object;
        }
    }

    protected function mergeParams($class, $params)
    {
        if (empty($this->_params[$class])) {
            return $params;
        } elseif (empty($params)) {
            return $this->_params[$class];
        } else {
            $ps = $this->_params[$class];
            foreach ($params as $index => $value) {
                $ps[$index] = $value;
            }
            return $ps;
        }
    }
}