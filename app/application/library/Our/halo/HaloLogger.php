<?php
namespace Our\Halo;

defined('HALO_LOG_DEBUG') || define('HALO_LOG_DEBUG', 0);
defined('HALO_LOG_WARNNING') || define('HALO_LOG_WARNNING', 1);
defined('HALO_LOG_INFO') || define('HALO_LOG_INFO', 2);
defined('HALO_LOG_TRACKER') || define('HALO_LOG_TRACKER', 3);
defined('HALO_LOG_EEROR') || define('HALO_LOG_EEROR', 4);
defined('HALO_LOG_FATAL') || define('HALO_LOG_FATAL', 5);

define('SERVER_NAME', 'WContact');


class HaloLogger
{
    private $_domain = '';

    public static $PLATFORM = 0; //0 web 1 wap
    const  PLATFORM_WEB = 0;
    const  PLATFORM_WAP = 1;
    const  USER_TRACE_LOG_KEY = 'USER_TRACE_LOG_KEY';
    const  MC_TRCACE_LOG_KEY = 'MC_TRCACE_LOG_KEY';

    public static $log2Console = false;
    public static $logLevel = null;

    public function __construct($domain)
    {
        $this->_domain = $domain;
    }

    public static function isDebugEnabled()
    {
        $logLevel = HaloEnv::logLevel();
        return (intval($logLevel) == HALO_LOG_DEBUG);
    }

    /* class method */
    private static function write($level, &$domain, &$info, $file = '', $line = '', $output = 'file')
    {
        $logLevel = self::$logLevel;

        if ($level < $logLevel) {
            return;
        }

        $level_str_list = array('debug', 'warnning', 'info', 'tracker', 'error', 'fatal');
        $level_str = $level_str_list[$level];

        $debugInfo = debug_backtrace();
        $lineNum = $debugInfo[1]['line'];
        $filename = $debugInfo[1]['file'];

        $file = (empty($file)) ? $filename: substr($file, strlen($_SERVER['DOCUMENT_ROOT']));
        $line = (empty($line)) ? $lineNum : $line;

        $time = date('H:i:s');
        $info = var_export($info, true);
        $message = sprintf("%s-[%s]-%s:%s  %s\r\n", $time, $level_str, $file, $line, $info);

        if ($output == 'mem') {
            $_REQUEST['MEM_LOG'][] = $message;
        } else {
            $filepath = HaloLogger::loggerFileName($domain);
            @file_put_contents($filepath, $message, FILE_APPEND);
            if (self::$log2Console)
                printf("%s", $message);
        }
    }

    public static function loggerFileName($domain, $ext = 'log')
    {
        $date = date('Y-m-d');
        $hour = date('H');
        $filepath = $_SERVER['DOCUMENT_ROOT'] . '/../../logs/' . $date . '/';
        //$path = ensureFilePath($filepath, true);
        if (strlen($domain) > 0) {
            $filepath = sprintf('%s%s-%02d.%s', $filepath, $domain, $hour, $ext);
        } else {
            $filepath = sprintf('%s%d.%s', $filepath, $hour, $ext);
        }
        return $filepath;
    }

    public static function flush()
    {
        $messages = $_REQUEST['MEM_LOG'];
        if ($messages != null) {
            $uri = $_SERVER['REQUEST_URI'];
            $filepath = HaloLogger::loggerFileName('mem-log');
            $text = '============start ' . $uri . '============' . "\r\n" . implode($messages, '') . '============';
            file_put_contents($filepath, $text, FILE_APPEND);
            unset($_REQUEST['MEM_LOG']);
        }
        HaloLogger::traceMcEnd();
        HaloLogger::traceUserEnd();
    }

    public static function DEBUG($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_DEBUG, $domain, $info, $file, $line, $output);
    }

    public static function INFO($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_INFO, $domain, $info, $file, $line, $output);
    }

    public static function WARNNING($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_WARNNING, $domain, $info, $file, $line, $output);
    }

    public static function ERROR($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_EEROR, $domain, $info, $file, $line, $output);
    }

    public static function FATAL($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_FATAL, $domain, $info, $file, $line, $output);
    }

    public static function TRACKER($info, $file = '', $line = '', $domain = '', $output = 'file')
    {
        self::write(HALO_LOG_TRACKER, $domain, $info, $file, $line, $output);
    }


    /* instance method */
    public function __DEBUG__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_DEBUG, $this->_domain, $info, $file, $line, $output);
    }

    public function __INFO__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_INFO, $this->_domain, $info, $file, $line, $output);
    }

    public function __WARNNING__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_WARNNING, $this->_domain, $info, $file, $line, $output);
    }

    public function __ERROR__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_EEROR, $this->_domain, $info, $file, $line, $output);
    }

    public function __FATAL__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_FATAL, $this->_domain, $info, $file, $line, $output);
    }

    public function __TRACKER__($info, $file = '', $line = '', $output = 'file')
    {
        HaloLogger::write(HALO_LOG_TRACKER, $this->_domain, $info, $file, $line, $output);
    }

    public static function LOG($domain)
    {
        $log = new HaloLogger($domain);
        return $log;
    }

    public static function LOG_TIMEDEBUG($str, $type = 'SQL')
    {
        $date = date('Y-m-d');
        $hour = date('H');
        $filepath = $_SERVER['DOCUMENT_ROOT'] . '/../../logs/timedebug/' . $date . '/';
        ensureFilePath($filepath, true);

        $filepath = sprintf('%s%s-%02d.log', $filepath, $type, $hour);

        @file_put_contents($filepath, $str, FILE_APPEND);
    }

    public static function timeDebug($cmd = '', $data = '')
    {
        $config = HaloEnv::getConfig();
        if ($config->log->timedebug->enable) {
            if (function_exists('microtime')) {
                list($usec, $sec) = explode(" ", microtime());
                $time = ((float)$usec + (float)$sec);
                //记录协议运行开时间
                if ($cmd == 'start') {
                    $_ENV['timedebug']['start'] = $time;
                } //记录协议运行结束时间，并写入log
                elseif ($cmd == 'end') {
                    $startTime = $_ENV['timedebug']['start'];
                    $endTime = $time;
                    $_ENV['timedebug']['end'] = $time;
                    $pagetime = $endTime - $startTime;

                    if ($pagetime > $config->log->timedebug->timeout) {
                        $str = "Time:" . $pagetime . sprintf("\t[%s--%s]", date("i:s", intval($startTime)), date("i:s", intval($endTime))) . "\t[" . $startTime . ',' . $endTime . "]\t" . $_SERVER["REQUEST_URI"] . "\n";
                        if (isset($_ENV['timedebug']['url'])) {
                            foreach ($_ENV['timedeubg']['url'] AS $k => $v) {
                                $str .= sprintf("|  |--[%s]--Timeout:%f->%s\n", date("i:s"), $v['start'] - $v['end'], $k);
                            }
                        }
                        if (isset($_ENV['timedebug']['sql'])) {
                            foreach ($_ENV['timedebug']['sql'] AS $k => $v) {
                                $str .= sprintf("|--[%s]-[%s]--Timeout:%f->%s\n", date("i:s", $v['request']), date("i:s", $v['response']), ($v['start'] - $v['end']), $k);
                            }
                        }
                        if (isset($_ENV['timedebug']['sendToNotifyCenter'])) {
                            foreach ($_ENV['timedebug']['sendToNotifyCenter'] AS $k => $v) {
                                $str .= sprintf("|--[%s]-[%s]--Timeout:%f->%s\n", date("i:s", $v['request']), date("i:s", $v['response']), ($v['start'] - $v['end']), $k);
                            }
                        }
                        if ($str) {
//                            YafDebug::dump($str);
                            static::LOG_TIMEDEBUG($str);
                        }

                        if ($pagetime >= 3) {
                            HaloLogger::traceUser(0, 3001, 1, array('time' => $pagetime * 1000));
                        }
                    }
                } elseif ($cmd == 'url') {
                    if (!empty($data)) {
                        if (isset($_ENV['timedeubg'][$cmd][$data]['start'])) {
                            $_ENV['timedeubg'][$cmd][$data]['end'] = $time;
                        } else {
                            $_ENV['timedeubg'][$cmd][$data]['start'] = $time;
                        }
                    }
                } elseif ($cmd == 'sql') {
                    if (!empty($data)) {
                        $_ENV[$data . 'sql_index'] = isset($_ENV[$data . 'sql_index']) ? intval($_ENV[$data . 'sql_index']) : 1;
                        $c1 = "[" . $_ENV[$data . 'sql_index'] . ']' . $data;
                        if (isset($_ENV['timedebug'][$cmd][$c1]['end'])) {
                            $_ENV[$data . 'sql_index'] += 1;
                        }
                        $c1 = "[" . $_ENV[$data . 'sql_index'] . ']' . $data;
                        $data = $c1;
                        if (isset($_ENV['timedebug'][$cmd][$data]['start'])) {
                            $_ENV['timedebug'][$cmd][$data]['end'] = $time;
                            $_ENV['timedebug'][$cmd][$data]['response'] = time();

                            $interval = $time - $_ENV['timedebug'][$cmd][$data]['start'];
                            if ($interval > 0.5) {
                                HaloLogger::traceUser(0, 3004, array('time' => $interval * 1000, 'sql' => $data));
                            }
                        } else {
                            $_ENV['timedebug'][$cmd][$data]['start'] = $time;
                            $_ENV['timedebug'][$cmd][$data]['request'] = time();
                        }
                    }
                } elseif ($cmd == 'sendToNotifyCenter') {
                    if (!empty($data)) {
                        $data = str_replace("\r\n", "|", $data);
                        $_ENV[$data . 'sendToNotifyCenter'] = isset($_ENV[$data . 'sendToNotifyCenter']) ? intval($_ENV[$data . 'sendToNotifyCenter']) : 1;
                        $c1 = "[" . $_ENV[$data . 'sendToNotifyCenter'] . ']' . $data;
                        if (isset($_ENV['timedebug'][$cmd][$c1]['end'])) {
                            $_ENV[$data . 'sendToNotifyCenter'] += 1;
                        }
                        $c1 = "[" . $_ENV[$data . 'sendToNotifyCenter'] . ']' . $data;
                        $data = $c1;
                        if (isset($_ENV['timedebug'][$cmd][$data]['start'])) {
                            $_ENV['timedebug'][$cmd][$data]['end'] = $time;
                            $_ENV['timedebug'][$cmd][$data]['response'] = time();
                        } else {
                            $_ENV['timedebug'][$cmd][$data]['start'] = $time;
                            $_ENV['timedebug'][$cmd][$data]['request'] = time();
                        }
                    }
                }
            }
        }

        if ($cmd == 'end')
            HaloLogger::flush();
    }

    public static function isTraceUserEnabled()
    {
        $config = HaloEnv::getConfig();
        return $config->log->trace->user->enable;
    }

    public static function traceUser($uid, $protocol, $msg = array())
    {
        if (!self::isTraceUserEnabled())
            return;

        if (!isset($_ENV[self::USER_TRACE_LOG_KEY]))
            $_ENV[self::USER_TRACE_LOG_KEY] = array();

        //protocol^time^platform^uid^client_ip^proxy_ip^type^uri^refer^msg
        $_ENV[self::USER_TRACE_LOG_KEY][] = sprintf('%s^%s^%s^%s^%s^%s^%s^%s^%s^%s',
            $protocol, time(), self::$PLATFORM, $uid, $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'], $_SERVER['HTTP_REFERER'], json_encode($msg));

    }

    public static function traceRealtime($type, $msg)
    {
        $config = HaloEnv::getConfig();
        if (!$config->log->trace->realtime->enable) {
            return;
        }

        //$type^time^platform^uid^client_ip^proxy_ip^type^uri^refer^msg

        $data['type'] = $type;
        $data['time'] = time();
        $data['platform'] = self::$PLATFORM;
        $data['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $data['remote_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['method'] = $_SERVER['REQUEST_METHOD'];
        $data['url'] = $_SERVER['REQUEST_URI'];
        $data['refer'] = $_SERVER['HTTP_REFERER'];
        $data['msg'] = $msg;

        try {
            $context = new ZMQContext();
            $requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
            $host = sprintf('tcp://%s:%s', $config->log->trace->realtime->host, $config->log->trace->realtime->port);
            $conection = $requester->connect($host);
            $requester->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);
            $requester->send(json_encode($data), ZMQ::MODE_DONTWAIT);
            $read = $write = array();
            $poll = new ZMQPoll();
            $poll->add($requester, ZMQ::POLL_IN);
            $events = $poll->poll($read, $write, 1000);
            if ($events > 0) {
                $reply = $requester->recv();
            }
        } catch (Exception $e) {
            HaloLogger::ERROR('Zmq Trace Log:[' . $e->getMessage() . ']', __FILE__, __LINE__, ERROR_LOG_FILE);
        }

    }

    public static function traceUserEnd()
    {
        if (!self::isTraceUserEnabled())
            return;

        $config = HaloEnv::getConfig();
        $basedir = $config->log->trace->basedir;

        if (empty($basedir))
            return;

        $platformDir = self::PLATFORM_WAP == self::$PLATFORM ? 'mobile' : 'web';
        $basedir = sprintf("%s/trace-user/%s", $basedir, $platformDir);
        @mkdir($basedir, 0755, true);

        $filePath = sprintf("%s/trace-%s", $basedir, date('Y-m-d.H'));

        if (count($_ENV[self::USER_TRACE_LOG_KEY])) {
            $content = implode("\n", $_ENV[self::USER_TRACE_LOG_KEY]);
            @file_put_contents($filePath, $content . "\n", FILE_APPEND);
            $_ENV[self::USER_TRACE_LOG_KEY] = array();
        }
    }

    public static function isTraceMcEnabled()
    {
        $config = HaloEnv::getConfig();
        return $config->log->trace->mc->enable;
    }

    public static function traceMcBegin()
    {
        $_ENV[self::MC_TRCACE_LOG_KEY] = array();
    }

    public static function traceMc($key, $hit)
    {
        if (!self::isTraceMcEnabled())
            return false;

        if (!isset($_ENV[self::MC_TRCACE_LOG_KEY]))
            $_ENV[self::MC_TRCACE_LOG_KEY] = array();

        $_ENV[self::MC_TRCACE_LOG_KEY][] = sprintf('%s %s %s', time(), $key, $hit);
    }

    public static function traceMcEnd()
    {
        if (!self::isTraceMcEnabled())
            return false;

        $config = HaloEnv::getConfig();
        $basedir = $config->log->trace->basedir;

        if (empty($basedir))
            return;

        $platformDir = self::PLATFORM_WAP == self::$PLATFORM ? 'mobile' : 'web';
        $basedir = sprintf("%s/trace-mc/%s", $basedir, $platformDir);
        @mkdir($basedir, 0755, true);
        $filePath = sprintf("%s/trace-%s", $basedir, date('Y-m-d.H'));


        if (count($_ENV[self::MC_TRCACE_LOG_KEY])) {
            $content = implode("\n", $_ENV[self::MC_TRCACE_LOG_KEY]);
            @file_put_contents($filePath, $content . "\n", FILE_APPEND);
            $_ENV[self::MC_TRCACE_LOG_KEY] = array();
        }
    }
}