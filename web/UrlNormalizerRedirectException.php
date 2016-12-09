<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/12/9  下午3:56
 * @since 1.0
 */

namespace zy\web;

class UrlNormalizerRedirectException extends \zy\base\Exception
{
    public function getName()
    {
        return 'Url Normalizer Redirect Exception';
    }
}