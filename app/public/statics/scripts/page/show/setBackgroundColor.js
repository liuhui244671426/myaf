/**
 * Created by huiliu on 14-10-14.
 * @Des:
 */
define(function(require, exports){
    var $ = require('jquery');

    function setColor(id, value)
    {
        console.log('id: ' + id + ' value: ' + value);
        var lv1 = [], lv2 = [], lv3 = [], lv4 = [];
        if(id == 20)
        {
            //穿衣
            lv1 = ['较冷', '寒冷', '冷', '凉', '温凉', '较凉', '稍凉', '温良', 8, 9, 10, 11, 12, 13, 14], lv2 = ['暖', '舒适', '较舒适', '较暖', '凉爽', 4, 5, 6, 7], lv3 = ['热', '炎热', '较热', '很热', '极热', 1, 2, 3];
            if (inArray(lv2, value)) {
                $('body > div').attr('id', 'clothes-index-sute');
            } else if (inArray(lv1, value)) {
                $('body > div').attr('id', 'clothes-index-cold');
            } else if (inArray(lv3, value)) {
                $('body > div').attr('id', 'clothes-index-hot');
            } else {
                $('body > div').attr('id', 'clothes-index-sute');
            }
        }
        else if(id == 21)
        {
            //紫外线
            lv1 = ['最弱', '弱', '较弱', '低', 2, 1], lv2 = ['中等', '高', 3], lv3 = ['很强', '强', '较强', 4, 5];
            if (inArray(lv2, value)) {
                $('body > div').attr('id', 'ray-index-mid');
            } else if (inArray(lv3, value)) {
                $('body > div').attr('id', 'ray-index-strong');
            } else if (inArray(lv1, value)) {
                $('body > div').attr('id', 'ray-index-weak');
            } else {
                $('body > div').attr('id', 'ray-index-mid');
            }
        }
        else if(id == 17)
        {
            //洗车
            lv1 = ['适宜', '较适宜', '非常适宜', 1, 2, 3, 4, 5, 6], lv2 = ['较不宜', 7, 8, 9], lv3 = ['不宜', 10, 11, 12];
            if (inArray(lv1, value)) {
                $('body > div').attr('id', 'washCar01');
            } else if (inArray(lv2, value)) {
                $('body > div').attr('id', 'washCar02');
            } else if (inArray(lv3, value)) {
                $('body > div').attr('id', 'washCar03');
            } else {
                $('body > div').attr('id', 'washCar01');
            }
        }
        else if(id == 26)
        {
            //运动
            lv1 = ['适宜', '较适宜', 1, 2, 3, 4, 5, 6], lv2 = ['较不宜', '不宜', '较不适宜', 7 ,8, 9, 10, 11, 12, 13, 14, 15, 16];
            if (inArray(lv1, value)) {
                $('body > div').attr('id', 'sport-index-ok');
            } else if (inArray(lv2, value)) {
                $('body > div').attr('id', 'sport-index-no');
            } else {
                $('body > div').attr('id', 'sport-index-ok');
            }
        }
        else if(id == 28)
        {
            //钓鱼
            lv1 = ['适宜', '较适宜', 1, 2, 3, 4, 5, 6, 7, 8], lv2 = ['较不宜', '不宜', '不适宜', 9, 10, 11, 12, 13, 14, 15, 16, 17];
            if (inArray(lv1, value)) {
                $('body > div').attr('id', 'fishing-page01');
            } else if (inArray(lv2, value)) {
                $('body > div').attr('id', 'fishing-page02');
            } else {
                $('body > div').attr('id', 'fishing-page01');
            }
        }
        else if(id == 12)
        {
            //感冒
            lv1 = ['少发', '可能', 1, 2], lv2 = ['较易发', 3], lv3 = ['易发', 4], lv4 = ['极易发', 5];
            if (inArray(lv1, value)) {
                $('body > div').attr('id', 'flu-index-01');
            } else if (inArray(lv2, value)) {
                $('body > div').attr('id', 'flu-index-02');
            } else if(inArray(lv3, value)){
                $('body > div').attr('id', 'flu-index-03');
            } else if(inArray(lv4, value)) {
                $('body > div').attr('id', 'flu-index-04');
            } else{
                $('body > div').attr('id', 'flu-index-03');
            }
        }
        else if(id == 1){}
        else if(id == 7){}
    }
    /*
    * php in_array
    * */
    function inArray(arr, word){
        var leng = arr.length;
        for(var i = 0; i <= leng; i ++){
            if(arr[i] == word){
                return true;
            }
        }
        return false;
    }
    exports.setColor = setColor;
});