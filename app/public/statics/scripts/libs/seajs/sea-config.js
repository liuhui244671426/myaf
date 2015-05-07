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
        'jqueryUI': 'libs/jquery/seajs/jquery.ui-seajs.min.js',
        'jquerySparkline': 'libs/jquery/seajs/jquery.sparkline.min-seajs.js',
        'jqueryFlot': 'libs/jquery/seajs/jquery.flot.min.js',
        'jqueryFlotResize': 'libs/jquery/seajs/jquery.flot.resize.min.js',
        'jqueryFlotSpline': 'libs/jquery/seajs/jquery.flot.spline.min.js',
        'jqueryDatatables': 'libs/jquery/seajs/jquery.datatables.min-seajs.js',
        'jqueryTransit': 'libs/jquery/jquery.transit.js',
        'jqueryCookie': 'libs/jquery/seajs/jquery.cookies-seajs.js',
        'jqueryToggles': 'libs/jquery/seajs/jquery.toggles.min-seajs.js',
        'jqueryMorris': 'libs/jquery/seajs/jquery.morris.min-seajs.js',//时间序列线图Morris
        //jQuery-plugin--

        'modernizr': 'libs/jquery/seajs/modernizr.min-seajs.js',
        'retina': 'libs/jquery/seajs/retina.min-seajs.js',
        'raphael': 'libs/jquery/seajs/raphael.min-seajs.js',

        //zepto is min jquery
        'zepto': 'libs/zepto/zepto.min.js',

        //editor
        'kindeditor': 'libs/kindeditor/kindeditor.js',

        //bootstrap
        'bootstrap': 'libs/jquery/seajs/bootstrap.min-seajs.js',

        //My Tool
        'core': 'utils/core.util.js',



    },
    preload: ['jquery']
});