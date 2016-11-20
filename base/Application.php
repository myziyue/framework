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

use Ziyue;
use zy\base\Component;
use zy\di\ServiceLocator;
use zy\exception\InvalidParamException;

class Application extends ServiceLocator
{
    public $version = '1.0';
    public $name = 'My Ziyue Application';
    public $charset = 'UTF-8';
    public $language = 'en_US';
    private $coreComponent = [];

    public function __construct()
    {
        Ziyue::$app = $this;
        // 系统初始化
        $this->init();
        // 注册异常错误处理句柄
        $this->registerErrorHandler();

    }


    public function init(){
        // 映射系统核心组件类
        $this->coreComponent = $this->coreComponent();
    }

    public function getErrorHandler()
    {
        return $this->get('errorHandler');
    }

    public function coreComponent(){
        return [
          'errorHandler' => ['class' => 'zy\base\ErrorHandler'],
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
}