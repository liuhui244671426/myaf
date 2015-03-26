<?php
/*
 * hook认证
 * */
class AuthPlugin extends Yaf_Plugin_Abstract{

    public function routeStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}

    /**
     * 登陆拦截
     * @param mixed $request
     * @param mixed $response
     */
    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        Yaflog(__METHOD__);
        Yaflog(strcasecmp($request->module, 'Admin'));
        Yaflog(strcasecmp($request->action, 'login'));

        if(strcasecmp($request->module, 'Admin') == 0 && strcasecmp($request->action, 'login') != 0 )
        {
            $isLogin = ((empty($_SESSION['isLogin'])) || (!isset($_SESSION['isLogin'])) || ($_SESSION['isLogin'] == null))
                ? false : true;
            Yaflog('$request: ');
            Yaflog($request);
            Yaflog('$isLogin: ');
            Yaflog($isLogin);
            //缺少auth
            if(!$isLogin)
            {
                header('location:/admin/index/login');
                return false;
            }

        }
        //dump('dispatchLoopStartup');

    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}

    public function preResponse(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response){}
}
