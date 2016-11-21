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
use zy\exception\InvalidConfigException;
use zy\exception\InvalidParamException;

class Application extends ServiceLocator
{
    public $version = '1.0';
    public $name = 'My Ziyue Application';
    public $charset = 'UTF-8';
    public $language = 'en_US';
    private $coreComponent = [];
    public $loadedModules = [];
    public $appPath = '';
    public $logPath = '';
    public $cachePath = '';
    public $components = [];

    public function __construct($config = [])
    {
        try{
            Zy::$app = $this;
            // 系统初始化
            $this->preInit($config);
            // 配置文件
            $this->initConfig();
            // 注册异常错误处理句柄
            $this->registerErrorHandler();
            if(!empty($config)){
                Zy::configure($this, $config);
            }
            unset($config);
        } catch (\Exception $ex){
            Zy::p($ex);
        }

    }

    public function run()
    {
        try {
            Zy::createObject("logger", $this->coreComponent['logger']);
        } catch (\Exception $ex) {
            Zy::p($ex);
        }
    }

    /**
     * 预初始化
     * @param $config
     * @throws InvalidConfigException
     */
    public function preInit(&$config)
    {
        // 映射系统核心组件类
        $this->coreComponent = $this->coreComponent();

        // 设置应用目录
        if (isset($config['appPath'])) {
            $this->setAppPath($config['appPath']);
            unset($config['appPath']);
        } else {
            throw new InvalidConfigException("The 'basePath' configuration for the Application is required.");
        }
        // 设置日志目录
        if (isset($config['logPath'])) {
            $this->setLogPath($config['logPath']);
            unset($config['logPath']);
        } else {
            $this->setLogPath($this->getAppPath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR);
        }
        // 设置缓存目录
        if (isset($config['cachePath'])) {
            $this->setCachePath($config['cachePath']);
            unset($config['cachePath']);
        } else {
            $this->setCachePath($this->getAppPath() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
        }
        //设置时区
        if (isset($config['timeZone'])) {
            $this->setTimeZone($config['timeZone']);
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {
            $this->setTimeZone('UTC');
        }
        //  合并核心组件
        if (isset($config['components'])) {
            foreach ($this->coreComponent() as $id => $component) {
                if(!isset($config['components'][$id])){
                    $config['components'][$id] = $component;
                }
            }
        } else {
            throw new InvalidConfigException("The 'components' configuration for the Application is required.");
        }

    }

    public function getErrorHandler()
    {
        return $this->get('errorHandler');
    }

    public function coreComponent()
    {
        return [
            'action' => ['class' => 'zy\base\Action'],
            'cache' => ['class' => 'zy\base\Cache'],
            'controller' => ['class' => 'zy\base\Controller'],
            'config' => ['class' => 'zy\base\Config'],
            'db' => ['class' => 'zy\base\Db'],
            'errorHandler' => ['class' => 'zy\base\ErrorHandler'],
            'logger' => ['class' => 'zy\base\Logger'],
            'route' => ['class' => 'zy\base\Route'],
        ];
    }

    protected function registerErrorHandler()
    {
        if (ZY_ENABLE_ERROR_HANDLER) {
            if (!isset($this->coreComponent['errorHandler']['class'])) {
                echo "Error: no errorHandler component is configured.\n";
                exit(1);
            }
            $this->set('errorHandler', $this->coreComponent['errorHandler']);
            $this->getErrorHandler()->register();
        }
    }

    protected function initConfig()
    {
        $this->set('config', $this->coreComponent['config']);
        $this->get('config')->init();
    }

    public function getTimeZone()
    {
        return date_default_timezone_get();
    }

    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }

    public function setAppPath($appPath){
        if(file_exists($appPath)){
            $this->appPath = $appPath;
        } else {
            throw new InvalidParamException("The directory does not exist: $appPath");
        }
    }
    public function getAppPath(){
        return $this->appPath;
    }
    public function setLogPath($logPath){
        if(file_exists($logPath)){
            $this->logPath = $logPath;
        } else {
            throw new InvalidParamException("The directory does not exist: $logPath");
        }
    }
    public function getLogPath(){
        return $this->logPath;
    }
    public function setCachePath($cachePath){
        if(file_exists($cachePath)){
            $this->cachePath = $cachePath;
        } else {
            throw new InvalidParamException("The directory does not exist: $cachePath");
        }
    }
    public function getCachePath(){
        return $this->cachePath;
    }


}