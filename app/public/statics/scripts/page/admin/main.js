/**
 * Created by liuhui on 15-3-2.
 */
function postAjax(url, data, callback){
    $.ajax({
            url:url,
            type:'POST',
            data:data,
            contentType:'application/x-www-form-urlencoded; charset=UTF-8',
            dataType:'json',
            error:function(e){
                console.log(e);
            },
            success:callback
        }
    );
}