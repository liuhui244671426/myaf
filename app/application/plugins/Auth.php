<?php

/**
 * hook
 * 调用顺序一次如下:
 * @hook routeStartup 在路由之前触发，这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
 * @hook routerShutdown 路由结束之后触发，此时路由一定正确完成, 否则这个事件不会触发
 * @hook dispatchLoopStartup 分发循环开始之前被触发
 * @hook preDispatch 分发之前触发,如果在一个请求处理过程中,发生了forward, 则这个事件会被触发多次
 * @hook postDispatch 分发结束之后触发，此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次
 * @hook dispatchLoopShutdown 分发循环结束之后触发，此时表示所有的业务逻辑都已经运行完成, 但是响应还没有发送
 * @hook preResponse 响应(Yaf_Response)前被触发
 */
class AuthPlugin extends Yaf_Plugin_Abstract
{

    public function routeStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        HaloLogger::INFO(__METHOD__);
        HaloLogger::INFO($request->module);
        HaloLogger::INFO($request->action);

        if (strcasecmp($request->module, 'Admin') == 0 && strcasecmp($request->action, 'login') != 0) {
            $isLogin = ((empty($_SESSION['user']['uid'])) || (!isset($_SESSION['user']['uid'])) || ($_SESSION['user']['uid'] == null))
                ? false : true;
            HaloLogger::INFO('$request: ');
            HaloLogger::INFO($request);
            HaloLogger::INFO('$isLogin: ');
            HaloLogger::INFO($isLogin);
            //缺少auth
            if (!$isLogin) {
                header('location:/admin/index/login');
                return false;
            }

        }
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function preResponse(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }
}
