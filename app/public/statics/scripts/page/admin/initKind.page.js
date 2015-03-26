/**
 * Created by huiliu on 14-9-24.
 * @Des:
 */
define(function(require, exports){
    var kind = require('kindeditor'), $ = require('jquery');

    //初始化kindeditor
    exports.initKindEditor = function ()
    {
        kind.create('#kind_content', {
            afterChange : function(){
                this.sync();
            },
            basePath: '/scripts/libs/kindeditor/',
            allowPreviewEmoticons : false,
            allowImageUpload : true,
            minHeight:400,
            minWidth:'100%'
//            items : [
//                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
//                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
//                'insertunorderedlist', '|', 'image', 'link', 'unlink']
        });
    };

    //预览图片
    exports.initPreview = function (context, elementId, showImageId, showImageTipId)
    {
        var files = context.files;
        console.log('files: ', files);
        for(var i = 0; i < files.length; i ++)
        {
            var file = files[i], imageType = /image.*/;
            var img = document.createElement('img'),
                showImageTip = document.getElementById(showImageTipId),
                preview = document.getElementById(elementId);

            if(!file.type.match(imageType))
            {
                continue;
            }
            //imgSize.innerText = '此图size: ' + Math.ceil(file.size / 1024) + ' kb,建议将封面图片控制在60kb以内';

            img.classList.add('obj');
            img.id = showImageId;
            img.file = file;

            preview.innerHTML = '';
            preview.appendChild(img);
            //console.log(img);

            var reader = new FileReader();
            reader.onload = (function(aimg){
                console.log(aimg);
                return function(e)
                {
                    //console.log(e);
                    aimg.src = e.target.result;//storage image data
                    var widthImg = aimg.width, heightImg = aimg.height; //图片的高度和宽度
                    var sizeImg = Math.ceil(file.size / 1024);
                    if(widthImg > 700)
                    {
                        showImageTip.innerHTML = '图片体积: ' + sizeImg + ' kb, 宽度: ' + widthImg + 'px, 高度: ' + heightImg + 'px, <span class="label label-danger">由于宽度较大,请务必将宽度调整为660px~680px</span>';
                    }
                    else if(heightImg > 300)
                    {
                        showImageTip.innerHTML = '图片体积: ' + sizeImg + ' kb, 宽度: ' + widthImg + 'px, 高度: ' + heightImg + 'px, <span class="label label-danger">由于高度较大,请将高度调整为200px~220px</span>';
                    }
                    else
                    {
                        showImageTip.innerHTML = '图片体积: ' + sizeImg + ' kb, 宽度: ' + widthImg + 'px, 高度: ' + heightImg + 'px, <span class="label label-success">建议将封面图片控制在60kb以内</span>';
                    }
                }
            })(img);
            //console.log(img);
            reader.readAsDataURL(file);
        }
    };

    exports.initSubmitEvent = function ()
    {
        $('form').submit(function(){
            var categoryId = $('#categoryId option:selected'), kind_content = $('#kind_content');
            var categoryIdVal = categoryId.val(), kind_contentVal = kind_content.val();
            console.log(categoryIdVal);

            if(kind_contentVal.length < 5)
            {
                alert('内容最少5个字符吧,真的不能在少了');
                return false;
            }

            if(categoryIdVal == 0 || categoryIdVal == undefined)//未选择板块就不允许提交
            {
                alert('版块名称为空，无法保存草稿/提交');
                return false;
            }

        });
    }
});