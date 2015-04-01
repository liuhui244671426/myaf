<?php

class HaloLog
{
    private static $instance = null;
    private $host = null;
    private $port = null;
    
    private static function instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new HaloLog();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        $config =  HaloEnv::getConfig();
        $this->host = $config['log']['host'];
        $this->port = $config['log']['port'];
        $this->prefix =$config['debug'] == 1?'debug_' : '';
    }
    
    private function post($url, $data)
    {
        $fp = @fsockopen($this->host, $this->port, $errno, $errstr, 15);
        if (!$fp)
        {
            $server = $this->host . ":" . $this->port;
            return false;
        }
        
        $body = json_encode($data);
        $send = "POST $url HTTP/1.1\r\nContent-Type: application/json\r\nContent-Length: ".strlen($body)."\r\n\r\n".$body."\r\n";
        fputs($fp, $send);
        
        $lineState = fgets($fp);
        list ($p, $code, $msg) = explode(' ', $lineState);
        if ($code != '200')
        {
            fclose($fp);
            return false;
        }

        $content = $lineState;
        while (!feof($fp))
        {
            $content .= fgets($fp);
        }
        fclose($fp);

        $pos = strpos($content, "\r\n\r\n");
        if ($pos)
        {
            $body = trim(substr($content, $pos));
            $result = json_decode($body, true);
            if(!$result || !isset($result['code']))
                return false;
            return $result;
        }
        return false;
    }
    
    public function log($time, $category, $data)
    {
        $this->post('/log', array('time'=>$time, 'cate'=>$this->prefix.$category, 'data'=>$data));
    }
    
    public static function logs($time, $category, $data)
    {
        self::instance()->log($time, $category, $data);
    }
}