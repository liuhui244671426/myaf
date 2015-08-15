/**
 * Created by liuhui on 15-5-12.
 */
define(function(require, exports){
    var jQuery = require('jquery');
    require('jqueryGritter');

    exports.gritter = function (data){
        var title = data.msg, text = data.msg, className = {
            0: 'growl-success',
            1: 'growl-warning',
            2: 'growl-info',
            3: 'growl-primary',
            4: 'growl-danger'
        }, image = '/statics/bracket/images/screen.png';

        jQuery.gritter.add({
            title: title,
            text: text,
            class_name: className[data.code],
            image: image,
            sticky: false,
            time: ''
        });
        return false;
    }
});