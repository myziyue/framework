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
use zy\exception\InvalidParamException;

class Application extends Component
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
        $this->getErrorHandler()->register();

    }


    public function init(){
        // 映射系统核心组件类
        $this->coreComponent = $this->coreComponent();
    }

    public function getErrorHandler()
    {
        if(isset($this->coreComponent['errorHandler'])){
            return Ziyue::createObject($this->coreComponent['errorHandler']);
        }
        return false;
    }

    public function coreComponent(){
        return [
          'errorHandler' => ['class' => 'zy\base\ErrorHandler'],
        ];
    }
}