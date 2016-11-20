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

class Application extends Component
{
    public $version = '1.0';
    public $name = 'My Ziyue Application';
    public $charset = 'UTF-8';
    public $language = 'en_US';

    public function __construct()
    {
        Ziyue::$app = $this;

        $this->getErrorHandler()->register();

    }


    public function init(){

    }

    public function getErrorHandler()
    {
        return $this->get('errorHandler');
    }
}