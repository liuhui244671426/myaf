<?php

/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-3-4
 * @Time: 下午6:55
 * @Desc: BaseController->YafController->Yaf_Controller_Abstract
 */
class YafController extends Yaf_Controller_Abstract
{

    /**
     * 获取合法参数
     * @param string $tag 字段名
     * @param string $legalType ('eid'|'id'|'time'|'int'|'str'|'trim_spec_str'|'enum'|'array'|'json'|'raw') 字段类型
     * @param array $legalList
     * @param mixed $default 字段默认值
     * @return mixed|false
     * */
    protected function getLegalParam($tag, $legalType, $legalList = array(), $default = null)
    {
        $param = $this->getRequest()->get($tag, $default);
        if ($param !== null) {
            switch ($legalType) {
                case 'eid': //encrypted id
                {
                    if ($param)
                        return aesDecrypt(hex2bin($param), AES_MJ_KEY);
                    else
                        return null;
                    break;
                }
                case 'id': {
                    if (preg_match('/^\d{1,20}$/', strval($param))) {
                        return strval($param);
                    }
                    break;
                }
                case 'time': {
                    return intval($param);
                    break;
                }
                case 'int': {
                    $val = intval($param);

                    if (count($legalList) == 2) {
                        if ($val >= $legalList[0] && $val <= $legalList[1])
                            return $val;
                    } else
                        return $val;
                    break;
                }
                case 'str': {
                    $val = strval($param);
                    if (count($legalList) == 2) {
                        if (strlen($val) >= $legalList[0] && strlen($val) <= $legalList[1])
                            return $val;
                    } else
                        return $val;
                    break;
                }
                case 'trim_spec_str': {
                    $val = trim(strval($param));
                    if (!preg_match("/['.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $val)) {
                        if (count($legalList) == 2) {
                            if (strlen($val) >= $legalList[0] && strlen($val) <= $legalList[1])
                                return $val;
                        } else
                            return $val;
                    }
                    break;
                }
                case 'enum': {
                    if (in_array($param, $legalList)) {
                        return $param;
                    }
                    break;
                }
                case 'array': {
                    if (count($legalList) > 0)
                        return explode($legalList[0], strval($param));
                    else {
                        if (empty($param))
                            return array();
                        return explode(',', strval($param));
                    }

                    break;
                }
                case 'json': {
                    return json_decode(strval($param), true);
                    break;
                }
                case 'raw': {
                    return $param;
                    break;
                }
                default:
                    break;
            }
        }
        return false;
    }

    protected function getPageParams()
    {
        $param['offset'] = $this->getLegalParam('offset', 'int', array(), 0);
        $param['length'] = $this->getLegalParam('length', 'int', array(), 20);

        return $param;
    }

    protected function getSharpParam()
    {
        $url = $_SERVER['REQUEST_URI'];
        $idx = stripos($url, "#");
        if ($idx === false)
            return array();
        $param = array();
        $paramstr = substr($url, $idx);
        return $paramstr;

    }

    /*protected function checkReferer()
    {
        $refer = $_SERVER['HTTP_REFERER'];
        if(empty($refer))
            $this->inputRefererErrorResult();
        else
        {
            $legalHost = array('weibo.com', 'weibo.cn', 'wfix.weibo.com', 'wfix.weibo.cn','wdev.weibo.com', 'wdev.weibo.cn', 'local.weibo.com', 'local.weibo.cn','renmai.weibo.com', 'renmai.weibo.cn');
            $url = parse_url($refer);
            $result = false;
            foreach($legalHost as $v)
            {
                $pos = stripos($url['host'],$v);
                if($pos!==false)
                {
                    $result = true;
                    break;
                }
            }
            if($result===false)
                $this->inputRefererErrorResult();
            else
            {
                if($_REQUEST['trace_type']!='ajax')
                    $this->inputRefererErrorResult();
            }
        }
    }*/

    protected function getLegalParamArray($fields)
    {
        $params = array();
        foreach ($fields as $f => $type) {
            $params[$f] = $this->getLegalParam($f, $type);
        }
        return $params;
    }

    protected function getRequestDate($year = 'year', $month = 'month', $day = 'day')
    {
        $y = $this->getLegalParam($year, 'int');
        $m = $this->getLegalParam($month, 'int');
        $d = $this->getLegalParam($day, 'int');
        return mktime(0, 0, 0, $m, $d, $y);
    }

    protected function inputIdResult($result, $model)
    {
        if ($result < 0)
            $this->inputErrorResult($result, $model);
        else
            $this->inputResult(array('id' => $result));
    }

    protected function inputStateResult($result, $model)
    {
        if ($result < 0)
            $this->inputErrorResult($result, $model);
        else
            $this->inputResult(array('state' => $result));
    }

    protected function inputNullResult($result, $model)
    {
        if ($result < 0)
            $this->inputErrorResult($result, $model);
        else
            $this->inputResult();
    }

    protected function inputUpgradeResult($result, $model)
    {
        $desc = $model->getErrorText($result['code']);
        echo json_encode(array('data' => $result, 'code' => $result['code'], 'desc' => $desc));
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function inputResult($data = null)
    {
// 		if (isset($data['html']))
// 			$data['html'] = $this->filterHtml($data['html']);
//
// 		if($this->uid>0)
// 		{
// 			$feedModel = new FeedModel();
// 			$heart = $feedModel->heart($this->uid);
//
// 			echo json_encode(array('heart'=>$heart,'data'=>$data,'code'=>0));
// 		}
// 		else
// 		{
//        MojiLoger::mojilog($data,"inputResult data");

        echo json_encode(array('data' => $data, 'code' => 0));
// 		}

        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    /*protected function inputBase64Result($data=null)
    {
        $data['base64'] = true;
        if (isset($data['html']))
        {
            $data['html'] = base64_encode($data['html']);
        }

        echo json_encode(array('data'=>$data,'code'=>0));

        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function inputErrorResult($code, $desc = '')
    {
        if ( empty($desc) ) $desc = ErrorCode::errorMsgByCode($code);
        echo json_encode(array('code'=>$code,'desc'=>$desc));
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function inputParamErrorResult()
    {
        echo json_encode(array('code'=>-100,'desc'=>'param error'));

        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function inputRefererErrorResult()
    {
        echo json_encode(array('code'=>-101,'desc'=>'referer error'));
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function _forward($action,$controller='',$parameters=array())
    {
        $this->forward('Index', $controller, $action, $parameters);
    }

    protected function render($tpl, array $parameters = null)
    {
        $this->display($tpl, $parameters);
    }*/
}