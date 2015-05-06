/**
 * Created by huiliu on 14-9-18.
 * @Des: seajs config
 */
seajs.config({
    base: "/statics/scripts/",
    alias: {
        'jquery': "libs/jquery/jquery-seajs.js",
        'jQuery': "libs/jquery/jquery-seajs.js",
        '$': "libs/jquery/jquery-seajs.js",
        'zepto': 'libs/zepto/zepto.min.js',
        'kindeditor': 'libs/kindeditor/kindeditor.js',
        'bootstrap': 'libs/bootstrap/js/bootstrap.js',
        'swfupload': 'libs/swfupload/swfupload-seajs.js',
        'core': 'utils/core.util.js', //diy core
        'jqTransit': 'libs/jquery/jquery.transit.js',
        'cookie': 'libs/jquery/jquery.cookies-seajs.js'
    }
});