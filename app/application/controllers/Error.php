<?php

/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 15-01-16 下午2:56
 * @Desc : 错误
 */
class ErrorController extends BaseController
{
    /**
     * 异常捕获,并记录异常日志
     * @param mixed $exception 异常
     * @return display 404
     */
    public function errorAction($exception)
    {

        $msg = $exception->getMessage();
        $msg2str = $exception->__toString();
        $code = $exception->getCode();

        $errMsg = '<?php exit;?>' . date('Y-m-d H:i:s', TODAY) . ' | code: ' . $code . ' | msg: ' . PHP_EOL . $msg2str . PHP_EOL;
        error_log($errMsg, 3, ROOT_PATH . '/logs/sysExceptionHandler.log');

        switch ($code) {
            case YAF_ERR_NOTFOUND_MODULE:
            case YAF_ERR_NOTFOUND_CONTROLLER:
            case YAF_ERR_NOTFOUND_ACTION:
            case YAF_ERR_NOTFOUND_VIEW:
                $httpCode = 404;
                break;
            default:
                $httpCode = 404;
                break;
        }
        import(LIBRARY_PATH . 'netFunctions.php');
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