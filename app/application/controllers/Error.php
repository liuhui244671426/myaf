<?php

/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 15-01-16 下午2:56
 * @Desc : 错误
 */

class ErrorController extends \Our\Controller\YafController
{
    /**
     * 异常捕获,并记录异常日志
     * @param mixed $exception 异常
     * @return string
     */
    public function errorAction($exception)
    {
        $msg = $exception->getMessage();
        $msg2str = $exception->__toString();
        $code = $exception->getCode();

        \Our\Halo\HaloLogger::sysException($code, $msg2str);

        switch ($code) {
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                $httpCode = 404;
                break;
            default:
                $httpCode = 404;
                break;
        }

        importFunc('netFunctions');

        header(httpStatus($httpCode));
        $isSupportTwig = initConfig::isSupportExtendConfig('twig');
        if ($isSupportTwig) {
            $this->_view->display('404.phtml', array('msg' => $msg, 'code' => $code));
        } else {
            $this->_view->display('404.phtml', array('msg' => $msg, 'code' => $code));
        }

        exit;
    }
}