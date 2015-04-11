<?php

class HaloService
{
    protected $host;
    protected $port;
    public $wait;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->wait = true;
    }

    public function postJson($data, $url)
    {
        return $this->post(json_encode($data), $url, "application/json");
    }

    public function postText($data, $url)
    {
        return $this->post($data, $url, "plain/text");
    }

    public function post($data, $url, $type)
    {
        return $this->tcpPost($data, $url, $type);

        //$url = sprintf("http://%s:%d%s", $this->host, $this->port, $url);
        //return $this->curlPost($data, $url, $type);
    }

    protected function tcpPost($data, $url, $type)
    {
        $count = 3;
        $fp = null;

        while (!$fp && $count > 0) {
            $fp = @fsockopen($this->host, $this->port, $errno, $errstr, 15);
            $count--;
        }
        if (!$fp) {
            return false;
        }

        $len = strlen($data);
        $send = "POST $url HTTP/1.1\r\nContent-Type: $type\r\nContent-Length: $len\r\n\r\n$data\r\n";
        fputs($fp, $send);

        if (!$this->wait) {
            usleep(100);
            fclose($fp);
            return true;
        }

        $lineState = fgets($fp);
        list ($p, $code, $msg) = explode(' ', $lineState);
        if ($code == '200') {
            $content = $lineState;
            while (!feof($fp)) {
                $content .= fgets($fp);
            }
            fclose($fp);

            $pos = strpos($content, "\r\n\r\n");
            if ($pos) {
                $body = trim(substr($content, $pos));
                $result = json_decode($body, true);
                if (!$result || !isset($result['code']))
                    return false;
                return $result;
            }
        } else {
            fclose($fp);
        }
        return false;
    }

    protected function curlPost($data, $url, $type)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . $type,
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        if (!$response)
            return false;

        $result = json_decode($response, true);
        if (!$result || !isset($result['code']))
            return false;

        return $result;
    }
}
