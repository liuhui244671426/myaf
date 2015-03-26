<?php

    /*
     * ipUtil::find('127.0.0.1');
        Code for PHP 5.3+ only
    */

    class ipUtil
    {
        private static $ip = NULL;

        private static $fp = NULL;
        private static $offset = NULL;
        private static $index = NULL;

        private static $cached = array();

        public static function find($ip)
        {
            header('Content-Type:text/html; charset=utf-8'); //fix utf8
            if (empty($ip) === TRUE) {
                return 'N/A';
            }

            $nip = gethostbyname($ip);
            $ipdot = explode('.', $nip);

            if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4) {
                return 'N/A';
            }

            if (isset(self::$cached[$nip]) === TRUE) {
                return self::$cached[$nip];
            }

            if (self::$fp === NULL) {
                self::init();
            }

            $nip = pack('N', ip2long($nip));

            $tmp_offset = (int)$ipdot[0] * 4;
            $start = unpack('Vlen', self::$index[$tmp_offset] . self::$index[$tmp_offset + 1] . self::$index[$tmp_offset + 2] . self::$index[$tmp_offset + 3]);

            $index_offset = $index_length = NULL;
            $max_comp_len = self::$offset['len'] - 1028;
            for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
                if (self::$index{$start} . self::$index{$start + 1} . self::$index{$start + 2} . self::$index{$start + 3} >= $nip) {
                    $index_offset = unpack('Vlen', self::$index{$start + 4} . self::$index{$start + 5} . self::$index{$start + 6} . "\x0");
                    $index_length = unpack('Clen', self::$index{$start + 7});

                    break;
                }
            }

            if ($index_offset === NULL) {
                return 'N/A';
            }

            fseek(self::$fp, self::$offset['len'] + $index_offset['len'] - 1024);

            self::$cached[$nip] = explode("\t", fread(self::$fp, $index_length['len']));

            return self::$cached[$nip];
        }

        private static function init()
        {
            if (self::$fp === NULL) {
                self::$ip = new ipUtil();

                self::$fp = fopen(sprintf('%s/%s', ROOT_PATH, 'ipdb.dat'), 'rb');
                if (self::$fp === FALSE) {
                    throw new Exception('Invalid ipdb.dat file!');
                }

                self::$offset = unpack('Nlen', fread(self::$fp, 4));
                if (self::$offset['len'] < 4) {
                    throw new Exception('Invalid ipdb.dat file!');
                }

                self::$index = fread(self::$fp, self::$offset['len'] - 4);
            }
        }

        public function __destruct()
        {
            if (self::$fp !== NULL) {
                fclose(self::$fp);
            }
        }

        /*
         * 获取客户端ip地址
         *
         * */
        static public function getClientIp()
        {
            if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            {
                $ip = getenv("HTTP_CLIENT_IP");
            }
            else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            }
            else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            {
                $ip = getenv("REMOTE_ADDR");
            }
            else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $ip = "unknown";
            }

            return ($ip);
        }
    }

?>