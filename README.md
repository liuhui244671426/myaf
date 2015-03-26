# myaf
nginx:

server {
        listen       80;
        server_name  myaf.me;

        root /var/www/myaf3/app/public;
        index index_dev.php;

        #rewrite url
        location / {
                try_files   $uri $uri/ /index_dev.php?$query_string;
        }

        location ~ \.php$ {
                include /usr/local/nginx/conf/fastcgi.conf;
                fastcgi_intercept_errors on;
                fastcgi_pass   127.0.0.1:9000;
                error_log       /usr/local/nginx/logs/3.myaf.com-error.log;
                access_log      /usr/local/nginx/logs/3.myaf.com-access.log;
        }
}