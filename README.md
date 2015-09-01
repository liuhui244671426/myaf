# myaf #
>##webserver##
>1.nginx.conf

    server{
        listen       80;
        server_name  myaf.me;
    
        root /var/www/myaf/app/public;
        index index.php;
    
        location / {
            try_files   $uri $uri/ /index.php?$query_string;
        }
    
        location ~ \.php$ {
            include /usr/local/nginx/conf/fastcgi.conf;
            fastcgi_intercept_errors on;
            fastcgi_pass   127.0.0.1:9000;
            error_log       /usr/local/nginx/logs/myaf.me-error.log;
            access_log      /usr/local/nginx/logs/myaf.me-access.log;
        }
    }


>2.apache

>##前台##
> 1.seajs,jquery,bootstrap(bracket)
> 2.文件在public/statics/

>##后台##
>1.命名空间的yaf搭建框架原型,UTF8编码

>2.支持特点:PDO,redis,memcached等功能,memcache不建议使用

>3.程序纪录日志使用\Our\HaloLogger 类

>4.后台模版渲染使用layout布局

>5.session 默认纪录到Memcached中

>6.

>##API Test unit##
>1. cd Tests
>2. phpunit --bootstrap ./bootstrap.php application/library/Our/Test/PHPUnit/ControllerTestCase.php
