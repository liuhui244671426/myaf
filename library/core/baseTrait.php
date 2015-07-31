<?php

    /**
     * @Create Author : huiliu//刘辉
     * @Create Time: 14-9-17 上午11:25
     * @Desc :
     */
    trait baseTrait
    {
        protected $_appConfig;

        public function init()
        {
            //初始化配置数据
            $this->_appConfig = YafRegistry('config');

            $this->doInit();
        }

        public function doInit()
        {
        }

        //json format
        public function formatJson($code, $data)
        {
            $msg = codeUtil::resultCode(0);
            return json_encode(array('code' => $code, 'msg' => $msg, 'data' => $data));
        }
    }