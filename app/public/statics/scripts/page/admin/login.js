/**
 * Created by liuhui on 15-5-6.
 */
define(function(require, exports){
    var jQuery = require('jquery'), cookie = require('cookie');

    jQuery(document).ready(function () {

        // Please do not use the code below
        // This is for demo purposes only
        var c = cookie.cookie('change-skin');
        if (c && c == 'greyjoy') {
            jQuery('.btn-success').addClass('btn-orange').removeClass('btn-success');
        } else if (c && c == 'dodgerblue') {
            jQuery('.btn-success').addClass('btn-primary').removeClass('btn-success');
        } else if (c && c == 'katniss') {
            jQuery('.btn-success').addClass('btn-primary').removeClass('btn-success');
        }
    });
});