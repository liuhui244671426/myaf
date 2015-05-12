/**
 * Created by liuhui on 15-5-7.
 */
define(function (require, exports) {
    var $ = require('jquery');
    var gr = require('/statics/scripts/page/admin/gritter.js');


    CKEDITOR_BASEPATH = '/statics/scripts/libs/ckeditor/';
    require('ckeditor');
    CKEDITOR.replace('wysiwyg');//id


    $('form').submit(function(e){
        e.preventDefault();
        $('#wysiwyg').val(CKEDITOR.instances.wysiwyg.getData());

        var data = $(this).serialize();
        $.post('/admin/article/postData', data, function(d){
            console.debug(d);
            if(d.code == 0){
                gr.gritter(d);
                setTimeout(function(){
                    location.href = '/admin/article/list'
                }, 1500);
            }
        });
        return false;
    });
});