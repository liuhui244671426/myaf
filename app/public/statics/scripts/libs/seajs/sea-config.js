/**
 * Created by huiliu on 14-9-18.
 * @Des: seajs config
 */
seajs.config({
    base: "/statics/scripts/",
    alias: {
        //jQuery
        'jquery': "libs/jquery/jquery-seajs.js",
        'jQuery': "libs/jquery/jquery-seajs.js",
        '$': "libs/jquery/jquery-seajs.js",
        //jQuery-plugin--
        'jqueryUI': 'libs/jquery/seajs/jquery.ui.min-seajs.js',
        'jquerySparkline': 'libs/jquery/seajs/jquery.sparkline.min-seajs.js',

        'jqueryFlot': 'libs/jquery/seajs/jquery.flot.min-seajs.js',
        'jqueryFlotResize': 'libs/jquery/seajs/jquery.flot.resize.min-seajs.js',
        'jqueryFlotSpline': 'libs/jquery/seajs/jquery.flot.spline.min-seajs.js',
        'jqueryFlotCategories': 'libs/jquery/seajs/jquery.flot.categories.min-seajs.js',
        'jqueryFlotCrosshair': 'libs/jquery/seajs/jquery.flot.crosshair.min-seajs.js',

        'jqueryDatatables': 'libs/jquery/seajs/jquery.datatables.min-seajs.js',

        'jqueryCookie': 'libs/jquery/seajs/jquery.cookies-seajs.js',
        'jqueryToggles': 'libs/jquery/seajs/jquery.toggles.min-seajs.js',
        'jqueryMorris': 'libs/jquery/seajs/jquery.morris.min-seajs.js',//时间序列线图Morris
        'jqueryTransit': 'libs/jquery/seajs/jquery.transit-seajs.js',//动画
        'jqueryGridly': 'libs/jquery/seajs/jquery.gridly-seajs.js',//拖动

        //jQuery-plugin--

        'modernizr': 'libs/jquery/seajs/modernizr.min-seajs.js',
        'retina': 'libs/jquery/seajs/retina.min-seajs.js',
        'raphael': 'libs/jquery/seajs/raphael.min-seajs.js',

        //zepto is min jquery
        'zepto': 'libs/zepto/zepto.min.js',

        //editor
        'kindeditor': 'libs/kindeditor/kindeditor.js',
        'ckeditor': 'libs/ckeditor/ckeditor-seajs.js',

        //bootstrap
        'bootstrap': 'libs/jquery/seajs/bootstrap.min-seajs.js',

        //My Tool
        'core': 'utils/core.util.js',



    },
    preload: ['jquery']
});