/**
 * Created by huiliu on 14-9-15.
 * @Des: 后台使用ajax
 */
define(function (require, exports, module) {

    exports.vardump = function (msg, type) {
        var type = type || 'html';
        type = 'log';
        if (type == 'html') {
            var pre = document.createElement('pre'), id = 'jsDump';
            pre.class = id;
            id = id + Math.floor(Math.random() * 12340);
            pre.id = id;
            pre.style.backgroundColor = 'gold';
            document.body.appendChild(pre);

            var tip = document.getElementById(id);
            tip.innerHTML = msg;
        }
        else if (type == 'log') {
            console.log(msg);
        }
        else if (type == 'img') {
            var img = new Image();
            img.src = 'http://192.168.1.195:3333';
        }
        else if (type == 'other') {
            return false;
        }
    };

    /*
     * 默认传递方式:get
     * */
    exports.coreAjax = function (url, callback, type) {
        this.vardump(url);
        var ajaxType = (typeof type == 'string' && type) || 'get';
        this.vardump(ajaxType);
        $.ajax({
            url: url,
            type: ajaxType,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType: 'json',
            error: function (e) {
                console.log(e);
            },
            success: callback
        });
    };

    /*
     * 拆分URL
     * @Return: /admin/cms/add
     * */
    exports.splitUrl = function (url) {
        var newUrl = url.split('#');
        this.vardump('splitUrl var newUrl: ' + newUrl);
        return newUrl[1];
    };

});

