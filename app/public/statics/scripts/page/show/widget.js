/**
 * Created with JetBrains WebStorm.
 * User: Youlu-Z0112
 * Date: 13-3-25
 * Time: 下午3:37
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports) {
    var $ = require('jquery'), setColor = require('/scripts/page/show/setBackgroundColor.js').setColor, codeid = $('#codeid').html();

    $(document).ready(function () {
        if (codeid == 28) {
            require('jqTransit');
            console.log(codeid);
            var totateDeg = 0;
            setInterval(function () {
                totateDeg = 22.5 + totateDeg;
                $(".f-wrap").transition({
                    rotate: totateDeg + 'deg'
                });
            }, 3000);
        }
        //---------------
//        if(navigator.onLine)
//        {
//            var wCache = window.applicationCache;
//            console.log(wCache.status);
//            wCache.update();
//            console.log(wCache.status);
//        }

        //---------------
        var showNum = $('#showNum').text();
        console.log(showNum);
        if (showNum == 3) {
            $("#tabDiv").Tab({
                currentIndex: 0,
                onclass: "active",
                offclass: "",
                action: "click"
            });
        }

    });

    ;
    (function ($) {
        $.fn.Tab = function (option) {
            var opt = {
                currentIndex: 0,
                onclass: "active",
                offclass: "",
                action: "click",
                onTabIndexChanged: null,
                isDisplayArrow: true //是否显示滑动小下标
            }
            var $this = $(this);
            $.extend(opt, option);
            var currentIndex = opt.currentIndex;
            //define
            var tabnav = $this.find(".tabs ul li"),
                tabcot = $this.find(".tab-cot > div");

            $this.getIndex = function () {
                return currentIndex;
            };
            var doSetIndex = function () {
                //默认显示小标
                var left = (currentIndex * 2 + 1) * (1 - (parseInt(tabcot.css("padding-right")) - 15) / tabcot.parent().width()) / 6 * 100, shadow = $('.shadow');
//            var margin_left = -14 - Math.round((parseInt(tabcot.css("padding-right")) - 15)/3*(currentIndex+1));
                tabcot.parent(".tab-cot").find(".arrow").css({"left": left + "%", "margin-left": -14 + "px"}).show();

                tabnav.eq(currentIndex).addClass(opt.onclass).removeClass(opt.offclass).siblings().addClass(opt.offclass).removeClass(opt.onclass);

                //Add Author :hui.liu//刘辉
                $(".mojitab ul li").eq(currentIndex).addClass(opt.onclass).removeClass(opt.offclass).siblings().addClass(opt.offclass).removeClass(opt.onclass);
                if (tabcot.length > 1) {
                    tabcot.eq(currentIndex).show().siblings(".tab-cot > div").hide();

                    //Add Author :hui.liu//刘辉
                    $(".mojitab ul li").hide();
                    $(".mojitab ul li").eq(currentIndex).show();
                    var weather = $('.level_id').eq(currentIndex), weatherVal = weather.text();
                    //console.log(weatherVal);
                    setColor(codeid, weatherVal);
                }
            };
            $this.setIndex = function (index) {
                if (index != currentIndex && index < tabnav.length) {
                    currentIndex = index;
                }
                console.log(currentIndex);
                doSetIndex();
                if ($.isFunction(opt.onTabIndexChanged))
                    opt.onTabIndexChanged(currentIndex, tabcot);
            };

            tabnav.each(function (i, item) {
                $("a", item).html($("a", item).attr("title"));
                var itemdiv = $(item);
                itemdiv.unbind().bind(opt.action, function () {
                    $this.setIndex(i);
                    return false;
                });
            });

            //initialize;
            $this.setIndex(currentIndex);

            return $this;
        }
    })($);


});
