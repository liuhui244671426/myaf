/**
 * Created by huiliu on 14-9-18.
 * @Des:
 */
define(function(require){
    var $ = require('$'), core = require('core'), bootstrap = require('bootstrap');

    main();

    function main()
    {
        delArticleById();
        batchDelArticles();
        publishArticleById();
        batchPublishArticle();
        //trHoverBindEvent();
        //注入search
        $(document).keypress(function(){
            if(event.keyCode == 13)
            {
                searchByKey();
            }
        });
        $('#searchBtn').bind('click', searchByKey);
        var option =
        {
            html: '<img src="/images/QRcode/79.png" />'
        };
        $("[data-toggle='popover']").popover(option);
    };

    /*
    * tr的hover事件,查看二维码图片
    * */
    function trHoverBindEvent()
    {
        $('tr img').bind('click', function(){
            var id = $(this).attr('data');

            var url = '/admin/cms/generateQR';
            var arg = 'id=' + id;
            $.get(url, arg, function(d){
                //请求成功
                if(d.code == 0)
                {
                    alert(d.data.src);
                }
                else if(d.code == 2) //没有该权限
                {
                    alert(d.msg);
                }
            }, 'json');
        });
    };

    //发布
    function publishArticleById()
    {
        $('.publish_aid').live('click', function(){
            var id = $(this).attr('data');
            var url = '/admin/cms/publish';
            var arg = 'id=' + id;
            $.get(url, arg, function(d){
                //删除成功
                if(d.code == 0)
                {
                    location.reload();
                }
                else if(d.code == 2) //没有该权限
                {
                    alert(d.msg);
                }
            }, 'json');

        });
    };

    //批量发布
    function batchPublishArticle()
    {
        $('#batch_publish').live('click', function(){
            var checked = $('input:checked');//选中的元素

            if(checked.length > 0)
            {
                if(confirm('确定变更状态?'))
                {
                    $(checked).each(function(n){
                        var id = $(this).val();
                        var url = '/admin/cms/publish';
                        var arg = 'id=' + id;
                        console.log(url);
                        console.log(arg);
                        $.get(url, arg, function(d){
                            console.log(d);
                            if(d.code == 2)
                            {
                                alert(d.msg);
                            }
                            else if(d.code == 0)
                            {
                                location.reload();
                            }
                        }, 'json');
                    });
                    //location.reload();
                }
            }
            else
            {
                alert('未选择需要上线的文章!');
            }
        });
    };

    //删除
    function delArticleById()
    {
        $('.del_aid').live('click', function(){
            var id = $(this).attr('data');
            var url = '/admin/cms/del';
            var arg = 'id=' + id;
            $.get(url, arg, function(d){
                //删除成功
                if(d.code == 0)
                {
                    location.reload();
                }
                else if(d.code == 2) //没有该权限
                {
                    alert(d.msg);
                }
            }, 'json');
        });
    };

    //批量删除
    function batchDelArticles()
    {
        $('#batch_del').live('click', function(){
            var checked = $('input:checked');//选中的元素

            if(checked.length > 0)
            {
                if(confirm('确定删除?'))
                {
                    $(checked).each(function(n){
                        var id = $(this).val();
                        var url = '/admin/cms/del';
                        var arg = 'id=' + id;

                        $.get(url, arg, function(d){
                            console.log(d);
                            if(d.code == 2)
                            {
                                alert(d.msg);
                            }
                            else if(d.code == 0)
                            {
                                location.reload();
                            }
                        }, 'json');
                    });

                }
            }
            else
            {
                alert('未选择需要删除的文章!');
            }
        });
    };
    //搜索
    function searchByKey()
    {
        //$('#searchBtn').bind('click', function(){
        var key = $('#key').val();
        if(key != '')
        {
            var url = '/admin/cms/search';
            var arg = 'key='+key;
            $.getJSON(url, arg, function(data){
                //console.log(data);
                if(data.code == 0)
                {
                    var container = $('.table-responsive');
                    container.empty();
                    container.html(data.data);
                }
            });
        }
        else
        {
            window.location.href = '/admin/cms/show';
        }
        //});
    }
});