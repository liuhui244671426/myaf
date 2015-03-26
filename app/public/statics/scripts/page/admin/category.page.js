/**
 * Created by huiliu on 14-9-18.
 * @Des:
 */
define(function(require){
    var $ = require('$'), core = require('core'), bootstrap = require('bootstrap');
    //var gridly = require('/scripts/libs/jquery/jquery.gridly.js').Gridly;

    main();

    function main()
    {
        delCategoryById();
        renameCategoryById();
        orderCategoryById();
    };

    //删除
    function delCategoryById()
    {
        $('.del_category').live('click', function(){
            var id = $(this).attr('data');
            var url = '/admin/cms/delcategory';
            var arg = 'id=' + id;
            if(confirm('确认需要删除该板块,强烈建议不要删除该板块,删除后不能恢复!!'))
            {
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
            }

        });
    };

    /*
    * 重命名操作
    * */
    function renameCategoryById()
    {
        $('.rename_category').live('focus', function(){
            console.log(this);
            var id = $(this).attr('data');
            var idName = '#category_name_' + id;

            console.log(idName);
            $(idName).blur(function(){
                $(this).change(function(){
                    alert('有改变');
                    return false;
                });
                var name = $(this).text();
                console.log(name);
                var url = '/admin/cms/renameCategory';
                var arg = 'id=' + id + '&name=' + name;

                $.get(url, arg, function(d){
                    //删除成功
                    if(d.code == 0)
                    {
                        alert('重命名完成');
                        location.reload();
                    }
                    else if(d.code == 2) //没有该权限
                    {
                        alert(d.msg);
                    }
                }, 'json');

            });
        });

    };

    /*
    * 板块排序操作
    * */
    function orderCategoryById()
    {
        $('.order_category').live('focus', function(){
            console.log(this);
            var id = $(this).attr('data');
            var idName = '#order_category_' + id;

            console.log(idName);
            $(idName).blur(function(){
                $(this).change(function(){
                    alert('有改变');
                    return false;
                });
                var orderId = $(this).text();
                console.log(orderId);
                var url = '/admin/cms/ordercategory';
                var arg = 'id=' + id + '&order_id=' + orderId;

                $.get(url, arg, function(d){
                    //删除成功
                    if(d.code == 0)
                    {
                        alert('排序完成');
                        location.reload();
                    }
                    else if(d.code == 2) //没有该权限
                    {
                        alert(d.msg);
                    }
                }, 'json');

            });
        });

    };
});