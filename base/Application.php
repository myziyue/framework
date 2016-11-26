<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/10/21  下午8:06
 * @since 1.0
 *
 * @property \zy\base\ErrorHandler $errorHandler The error handler application
 */

namespace zy\base;

use Zy;
use zy\di\ServiceLocator;
use zy\exception\ExitException;
use zy\exception\InvalidConfigException;

abstract class Application extends ServiceLocator
{
    public $version = '1.0';
    public $name = 'My Ziyue Application';
    public $charset = 'UTF-8';
    public $language = 'en_US';
    public $extensions;
    public $bootstrap = [];
    public $loadedModules = [];
    public $components = [];

    /**
     *
     * Application constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        Zy::$app = $this;
        self::setInstance($this);

        $this->preInit($config);

        $this->registerErrorHandler($config);
    }

    public function run()
    {
        try {
            $request = $this->handleRequest($this->getRequest());
            $request->send();
        } catch (ExitException $ex) {
            if (ZY_ENV_TEST) {
                throw new ExitException($ex->getCode());
            } else {
                exit($ex->getCode());
            }
        }
    }

    /**
     * 预处理
     * @param $config
     */
    public function preInit(&$config)
    {
        if (isset($config['appPath'])) {
            $this->setAppPath($config['appPath']);
            unset($config['appPath']);
        } else {
            throw new InvalidConfigException('The "appPath" configuration for the Application is required.');
        }

        $this->getRuntimePath();
        $this->getViewPath();
        $this->getLayoutPath();

        if (isset($config['timeZone'])) {
            $this->setTimeZone($config['timeZone']);
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {
            $this->setTimeZone('UTC');
        }

        foreach ($this->getCoreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }

        foreach ($config['components'] as $id => $component) {
            $this->components[$id] = $component;
        }
    }

    protected function getErrorHandler()
    {
        return $this->get('errorHandler');
    }

    protected function getDb()
    {
        if (!isset($this->components['db'])) {
            throw new InvalidConfigException("Error: no db component is configured");
            exit(1);
        }
        $this->set('db', $this->components['db']);
        return $this->get('db');
    }

    protected function getLogger()
    {
        $this->set('logger', $this->components['logger']);
        return $this->get('logger')->createFactory();
    }

    protected function registerErrorHandler(&$config)
    {
        if (ZY_ENABLE_ERROR_HANDLER) {
            if (!isset($config['components']['errorHandler']['class'])) {
                throw new InvalidConfigException("Error: no errorHandler component is configured");
                exit(1);
            }
            $this->set('errorHandler', $config['components']['errorHandler']);
            unset($config['components']['errorHandler']);
            $this->getErrorHandler()->register();
        }
    }

    protected function getCoreComponents()
    {
        return [
            'logger' => 'zy\log\Logger',
            'errorHandler' => 'zy\base\ErrorHandler',
        ];
    }

    public function getRequest()
    {
        if (!isset($this->components['request'])) {
            throw new InvalidConfigException("Error: no request component is configured");
            exit(1);
        }
        $this->set('request', $this->components['request']);
        return $this->get('request');
    }

    abstract public function handleRequest($request);
}